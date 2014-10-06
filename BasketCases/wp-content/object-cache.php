<?php
/*
Plugin Name: APC Object Cache
Description: APC backend for the WP Object Cache.
Version: 2.0.6
URI: http://txfx.net/wordpress-plugins/apc/
Author: Mark Jaquith
Author URI: http://coveredwebservices.com/

Install this file to wp-content/object-cache.php

Based on Ryan Boren's Memcached object cache backend
http://wordpress.org/extend/plugins/memcached/

*/

$oc_logged_in = false;
foreach ( $_COOKIE as $k => $v ) {
	if ( preg_match( '/^comment_author|wordpress_logged_in_[a-f0-9]+|woocommerce_items_in_cart|PHPSESSID_|edd_wp_session|edd_items_in_cartcc_cart_key|ccm_token/', $k ) ) {
		$oc_logged_in = true;
	}
}
$oc_blocked_page = ( defined('WP_ADMIN') || defined( 'DOING_AJAX' ) || defined( 'XMLRPC_REQUEST' ) || defined( 'DOING_CRON' ) || 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) );

if ( 'cli' !== php_sapi_name() && function_exists( 'apc_fetch' ) && !$oc_logged_in && !$oc_blocked_page ) :

if ( version_compare( '5.2.4', phpversion(), '>=' ) ) {
	wp_die( 'The APC object cache backend requires PHP 5.2 or higher. You are running ' . phpversion() . '. Please remove the <code>object-cache.php</code> file from your content directory.' );
}

if ( function_exists( 'wp_cache_add' ) ) {
	// Regular die, not wp_die(), because it gets sandboxed and shown in a small iframe
	die( '<strong>ERROR:</strong> This is <em>not</em> a plugin, and it should not be activated as one.<br /><br />Instead, <code>' . str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ) . '</code> must be moved to <code>' . str_replace( $_SERVER['DOCUMENT_ROOT'], '', trailingslashit( WP_CONTENT_DIR ) ) . 'object-cache.php</code>' );
} else {

// Users with setups where multiple installs share a common wp-config.php can use this
// to guarantee uniqueness for the keys generated by this object cache
if ( !defined( 'WP_APC_KEY_SALT' ) )
	define( 'WP_APC_KEY_SALT', 'wp' );

function wp_cache_add( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	if ( 'transient' == $group ) {
		__save_transient( $key, $data, $expire );
		return $wp_object_cache->add( "_transient_$key", $data, 'options', $expire );
	} elseif ( 'site-transient' == $group ) {
		__save_transient( $key, $data, $expire, true );
		return $wp_object_cache->add( "_site_transient_$key", $data, 'site-options', $expire );
	} else {
		return $wp_object_cache->add( $key, $data, $group, $expire );
	}
}

function wp_cache_incr( $key, $n = 1, $group = '' ) {
	global $wp_object_cache;

	return $wp_object_cache->incr2( $key, $n, $group );
}

function wp_cache_decr( $key, $n = 1, $group = '' ) {
	global $wp_object_cache;

	return $wp_object_cache->decr( $key, $n, $group );
}

function wp_cache_close() {
	return true;
}

function wp_cache_delete( $key, $group = '' ) {
	global $wp_object_cache, $wpdb;

	if ( 'transient' == $group ) {
		if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
			$flag = $wpdb->suppress_errors;
			$wpdb->suppress_errors( true );
			$wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->prefix}options` WHERE option_name IN ( '%s', '%s' )",
						"_transient_$key", "_transient_timeout_$key" ) );
			$wpdb->suppress_errors( $flag );
		}

		$wp_object_cache->delete( "_transient_timeout_$key", 'options' );

		// Update alloptions
		$alloptions = $wp_object_cache->get( 'alloptions', 'options' );
		unset( $alloptions[ "_transient_$key" ] );
		unset( $alloptions[ "_transient_timeout_$key" ] );
		$wp_object_cache->set( 'alloptions', $alloptions, 'options' );

		return $wp_object_cache->delete( "_transient_$key", 'options' );
	} elseif ( 'site-transient' == $group ) {
		if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
			$table = $wpdb->options;
			if ( is_multisite() ) {
				$table = $wpdb->sitemeta;
			}
			$flag = $wpdb->suppress_errors;
			$wpdb->suppress_errors( true );
			$wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->sitemeta}` WHERE option_name IN ( '%s', '%s' )",
						"_transient_$key", "_transient_timeout_$key" ) );
			$wpdb->suppress_errors( $flag );
		}
		$wp_object_cache->delete( "_transient_timeout_$key", 'site-options' );

		// Update alloptions
		$alloptions = $wp_object_cache->get( 'alloptions', 'options' );
		unset( $alloptions[ "_site_transient_$key" ] );
		unset( $alloptions[ "_site_transient_timeout_$key" ] );
		$wp_object_cache->set( 'alloptions', $alloptions, 'options' );
		
		return $wp_object_cache->delete( "_site_transient_$key", 'site-options' );
	}

	return $wp_object_cache->delete( $key, $group );
}

function wp_cache_flush( $local_flush = false ) {
	global $wp_object_cache, $wpdb;

	if ( !$local_flush ) {
		if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
			$flag = $wpdb->suppress_errors;
			$wpdb->suppress_errors( true );
			$wpdb->query( $wpdb->prepare( "INSERT INTO `{$wpdb->options}` (`option_name`, `option_value`, `autoload`) VALUES (%s, UNIX_TIMESTAMP(NOW()), '%s') ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
					'gd_system_last_cache_flush', 'no' ) );
			$wpdb->suppress_errors( $flag );
		}
	}

	return $wp_object_cache->flush();
}

function __prune_transients() {
	global $wpdb;

	if ( !empty( $wpdb ) && $wpdb instanceof wpdb && function_exists( 'is_main_site') && function_exists( 'is_main_network' ) ) {

		$flag = $wpdb->suppress_errors;
		$wpdb->suppress_errors( true );

		// Lifted straight from schema.php

		// Deletes all expired transients.
		// The multi-table delete syntax is used to delete the transient record from table a,
		// and the corresponding transient_timeout record from table b.
		$time = time();
		$wpdb->query("DELETE a, b FROM $wpdb->options a, $wpdb->options b WHERE
				a.option_name LIKE '\_transient\_%' AND
				a.option_name NOT LIKE '\_transient\_timeout\_%' AND
				b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
				AND b.option_value < $time");

		if ( is_main_site() && is_main_network() ) {
			$wpdb->query("DELETE a, b FROM $wpdb->options a, $wpdb->options b WHERE
				a.option_name LIKE '\_site\_transient\_%' AND
				a.option_name NOT LIKE '\_site\_transient\_timeout\_%' AND
				b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
				AND b.option_value < $time");
		}

		
		$wpdb->suppress_errors( $flag );
	}
}

function wp_cache_get( $key, $group = '', $force = false ) {
	global $wp_object_cache, $wpdb;
	
	if ( 'transient' == $group ) {		
		$alloptions = $wp_object_cache->get( 'alloptions', 'options' );		
		if ( isset( $alloptions["_transient_$key" ] ) && isset( $alloptions["_transient_timeout_$key" ] ) && $alloptions["_transient_timeout_$key" ] > time() ) {
			return maybe_unserialize( $alloptions["_transient_$key" ] );
		}
		$transient = $wp_object_cache->get( "_transient_$key" , 'options', $force );
		$timeout = $wp_object_cache->get( "_transient_timeout_$key" , 'options', $force );
		if ( false !== $transient && !empty( $timeout ) && $timeout > time() ) {
			return maybe_unserialize( $transient );
		}
		if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
			$flag = $wpdb->suppress_errors;
			$wpdb->suppress_errors( true );
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM `{$wpdb->options}` WHERE option_name IN( '%s', '%s' ) UNION SELECT 'current_time', UNIX_TIMESTAMP(NOW()) AS option_value",
					"_transient_$key", "_transient_timeout_$key" ), ARRAY_A );
			$wpdb->suppress_errors( $flag );
			if ( !empty( $result ) ) {
				$transient = false;
				$timeout = false;
				$current_time = time();
				foreach ( $result as $row ) {
					switch ( $row['option_name'] ) {
						case "_transient_$key" :
							$transient = $row['option_value'];
							break;
						case "_transient_timeout_$key" :
							$timeoout = $row['option_value'];
							break;
						case '_current_time' :
							$current_time = $row['option_value'];
							break;
					}
				}
				if ( false !== $transient && !empty( $timeout ) && $timeout > $current_time ) {
					return maybe_unserialize( $transient );
				}
			}
		}
		return false;
	} elseif ( 'site-transient' == $group ) {
		$transient = $wp_object_cache->get( "_site_transient_$key" , 'options', $force );
		$timeout = $wp_object_cache->get( "_site_transient_timeout_$key" , 'options', $force );
		if ( false !== $transient && !empty( $timeout ) && $timeout > time() ) {
		    return maybe_unserialize( $transient );
		}
		if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
			$table = $wpdb->options;
			if ( is_multisite() ) {
				$table = $wpdb->sitemeta;
			}
			$flag = $wpdb->suppress_errors;
			$wpdb->suppress_errors( true );
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM `{$table}` WHERE option_name IN( '%s', '%s' ) UNION SELECT 'current_time', UNIX_TIMESTAMP(NOW()) AS option_value",
					"_site_transient_$key", "_site_transient_timeout_$key" ), ARRAY_A );
			$wpdb->suppress_errors( $flag );
			if ( !empty( $result ) ) {
				$transient = false;
				$timeout = false;
				$current_time = time();
				foreach ( $result as $row ) {
					switch ( $row['option_name'] ) {
						case "_site_transient_$key" :
							$transient = $row['option_value'];
							break;
						case "_site_transient_timeout_$key" :
							$timeoout = $row['option_value'];
							break;
						case '_current_time' :
							$current_time = $row['option_value'];
							break;
					}
				}
				if ( false !== $transient && !empty( $timeout ) && $timeout > $current_time ) {
					return maybe_unserialize( $transient );
				}
			}
		}
		return false;
	} else {
		return $wp_object_cache->get( $key, $group, $force );
	}
}

function wp_cache_init() {
	global $wp_object_cache;
	if ( mt_rand( 1, 100 ) == 42 ) {
		__prune_transients();
	}
	add_action( 'muplugins_loaded', '__init_sync_cache' );
	$wp_object_cache = new APC_Object_Cache();
}

function wp_cache_replace( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	return $wp_object_cache->replace( $key, $data, $group, $expire );
}

function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	if ( defined('WP_INSTALLING') == false ) {
		if ( 'transient' == $group ) {
			return __save_transient( $key, $data, $expire );
		} elseif ( 'site-transient' == $group ) {
			return __save_transient( $key, $data, $expire, true );
		} else {
			return $wp_object_cache->set( $key, $data, $group, $expire );
		}
	} else {
		return $wp_object_cache->delete( $key, $group );
	}
}

/**
 * Save the transients to the DB.  The explanation is a bit too long
 * for code.  The tl;dr of it is that we don't have a single 'fast cache'
 * source yet (like memcached) and so some long lived items like transients
 * are still best cached in the db and then brought back into APC
 *
 * @param string $transient
 * @param mixed $value
 * @param int $expire
 * @param boolean $site = false
 */

function __save_transient( $transient, $value, $expire, $site = false ) {
	global $wp_object_cache;

	// The 'special' transient option names
	$transient_timeout = ( $site ? '_site' : '' ) . '_transient_timeout_' . $transient;
	$transient = ( $site ? '_site' : '' ) . '_transient_' . $transient;

	// Cap expiration at 24 hours to avoid littering the DB
	if ( $expire == 0 ) {
		$expire = 24 * 60 * 60;
	}
	
	// Save to object cache
	$wp_object_cache->set( $transient, $value, 'options', $expire );
	$wp_object_cache->set( $transient_timeout, time() + $expire, 'options', $expire );

	// Update alloptions
	$alloptions = $wp_object_cache->get( 'alloptions', 'options' );
	$alloptions[ $transient ] = $value;
	$alloptions[ $transient_timeout ] = time() + $expire;
	$wp_object_cache->set( 'alloptions', $alloptions, 'options' );
	
	// Use the normal update option logic
	global $wpdb;
	if ( !empty( $wpdb ) && $wpdb instanceof wpdb ) {
		$flag = $wpdb->suppress_errors;
		$wpdb->suppress_errors( true );
		if ( $site && is_multisite() ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO `{$wpdb->sitemeta}` (`option_name`, `option_value`, `autoload`) VALUES (%s, UNIX_TIMESTAMP( NOW() ) + %d, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
					$transient_timeout, $expire, 'yes' ) );
			$wpdb->query( $wpdb->prepare( "INSERT INTO `{$wpdb->sitemeta}` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
					$transient, maybe_serialize( $value ), 'yes' ) );
		} else {
			$wpdb->query( $wpdb->prepare( "INSERT INTO `{$wpdb->options}` (`option_name`, `option_value`, `autoload`) VALUES (%s, UNIX_TIMESTAMP( NOW() ) + %d, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
					$transient_timeout, $expire, 'yes' ) );			
			$wpdb->query( $wpdb->prepare( "INSERT INTO `{$wpdb->options}` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
					$transient, maybe_serialize( $value ), 'yes' ) );
		}
		$wpdb->suppress_errors( $flag );
	}
	
	return true;
}

/**
 * If another cache was flushed or updated, sync across all servers / processes using
 * the database as the authority.  This uses the database as the authority for timestamps
 * as well to avoid drift between servers.
 * @return void
 */
function __init_sync_cache() {
	global $wpdb;
	if ( empty( $wpdb ) || ! ( $wpdb instanceof wpdb ) ) {
		return;
	}

	$flag = $wpdb->suppress_errors;
	$wpdb->suppress_errors( true );
	$result = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM `{$wpdb->options}` WHERE option_name = '%s' UNION SELECT 'current_time', UNIX_TIMESTAMP(NOW()) AS option_value", 'gd_system_last_cache_flush' ), ARRAY_A );
	$wpdb->suppress_errors( $flag );

	if ( empty ( $result ) ) {
		return;
	}

	$master_flush = false;
	foreach ( $result as $row ) {
		switch ( $row['option_name'] ) {
			case 'current_time' :
				$current_time = $row['option_value'];
				break;
			case 'gd_system_last_cache_flush' :
				$master_flush = $row['option_value'];
				break;
		}
	}

	$local_flush = apc_fetch( 'gd_system_last_cache_flush' );

	if ( false === $local_flush || $local_flush < $master_flush ) {
		wp_cache_flush( true );
		apc_store( 'gd_system_last_cache_flush', $current_time );
	}
}

function wp_cache_switch_to_blog( $blog_id ) {
	global $wp_object_cache;

	return $wp_object_cache->switch_to_blog( $blog_id );
}

function wp_cache_add_global_groups( $groups ) {
	global $wp_object_cache;

	$wp_object_cache->add_global_groups( $groups );
}

function wp_cache_add_non_persistent_groups( $groups ) {
	global $wp_object_cache;

	$wp_object_cache->add_non_persistent_groups( $groups );
}

class WP_Object_Cache {
	var $global_groups = array();

	var $no_mc_groups = array();

	var $cache = array();
	var $stats = array( 'get' => 0, 'delete' => 0, 'add' => 0 );
	var $group_ops = array();

	var $cache_enabled = true;
	var $default_expiration = 0;
	var $abspath = '';
	var $debug = false;

	function add( $id, $data, $group = 'default', $expire = 0 ) {
		$key = $this->key( $id, $group );

		if ( is_object( $data ) )
			$data = clone $data;

		$store_data = $data;

		if ( is_array( $data ) )
			$store_data = new ArrayObject( $data );

		if ( in_array( $group, $this->no_mc_groups ) ) {
			$this->cache[$key] = $data;
			return true;
		} elseif ( isset( $this->cache[$key] ) && $this->cache[$key] !== false ) {
			return false;
		}

		$expire = ( $expire == 0 ) ? $this->default_expiration : $expire;

		$result = apc_add( $key, $store_data, $expire );
		if ( false !== $result ) {
			@ ++$this->stats['add'];
			$this->group_ops[$group][] = "add $id";
			$this->cache[$key] = $data;
		}

		return $result;
	}

	function add_global_groups( $groups ) {
		if ( !is_array( $groups ) )
			$groups = (array) $groups;

		$this->global_groups = array_merge( $this->global_groups, $groups );
		$this->global_groups = array_unique( $this->global_groups );
	}

	function add_non_persistent_groups( $groups ) {
		if ( !is_array( $groups ) )
			$groups = (array) $groups;

		$this->no_mc_groups = array_merge( $this->no_mc_groups, $groups );
		$this->no_mc_groups = array_unique( $this->no_mc_groups );
	}

	// This is named incr2 because Batcache looks for incr
	// We will define that in a class extension if it is available (APC 3.1.1 or higher)
	function incr2( $id, $n = 1, $group = 'default' ) {
		$key = $this->key( $id, $group );
		if ( function_exists( 'apc_inc' ) )
			return apc_inc( $key, $n );
		else
			return false;
	}

	function decr( $id, $n = 1, $group = 'default' ) {
		$key = $this->key( $id, $group );
		if ( function_exists( 'apc_dec' ) )
			return apc_dec( $id, $n );
		else
			return false;
	}

	function close() {
		return true;
	}

	function delete( $id, $group = 'default' ) {
		$key = $this->key( $id, $group );

		if ( in_array( $group, $this->no_mc_groups ) ) {
			unset( $this->cache[$key] );
			return true;
		}

		$result = apc_delete( $key );

		@ ++$this->stats['delete'];
		$this->group_ops[$group][] = "delete $id";

		if ( false !== $result )
			unset( $this->cache[$key] );

		return $result;
	}

	function flush() {
		// Don't flush if multi-blog.
		if ( function_exists( 'is_site_admin' ) || defined( 'CUSTOM_USER_TABLE' ) && defined( 'CUSTOM_USER_META_TABLE' ) )
			return true;

		$this->cache = array();
		return apc_clear_cache( 'user' );
	}

	function get($id, $group = 'default', $force = false) {
		$key = $this->key($id, $group);

		if ( isset($this->cache[$key]) && ( !$force || in_array($group, $this->no_mc_groups) ) ) {
			if ( is_object( $this->cache[$key] ) )
				$value = clone $this->cache[$key];
			else
				$value = $this->cache[$key];
		} else if ( in_array($group, $this->no_mc_groups) ) {
			$this->cache[$key] = $value = false;
		} else {
			$value = apc_fetch( $key );
			if ( is_object( $value ) && 'ArrayObject' == get_class( $value ) )
				$value = $value->getArrayCopy();
			if ( NULL === $value )
				$value = false;
			$this->cache[$key] = ( is_object( $value ) ) ? clone $value : $value;
		}

		@ ++$this->stats['get'];
		$this->group_ops[$group][] = "get $id";

		if ( 'checkthedatabaseplease' === $value ) {
			unset( $this->cache[$key] );
			$value = false;
		}

		return $value;
	}

	function key( $key, $group ) {
		if ( empty( $group ) )
			$group = 'default';
		
		if ( false !== array_search( $group, $this->global_groups ) )
			$prefix = $this->global_prefix;
		else
			$prefix = $this->blog_prefix;

		return WP_APC_KEY_SALT . ':' . $this->abspath . ":$prefix$group:$key";
	}

	function replace( $id, $data, $group = 'default', $expire = 0 ) {
		return $this->set( $id, $data, $group, $expire );
	}

	function set( $id, $data, $group = 'default', $expire = 0 ) {
		$key = $this->key( $id, $group );
		if ( isset( $this->cache[$key] ) && ('checkthedatabaseplease' === $this->cache[$key] ) )
			return false;

		if ( is_object( $data ) )
			$data = clone $data;

		$store_data = $data;

		if ( is_array( $data ) )
			$store_data = new ArrayObject( $data );

		$this->cache[$key] = $data;

		if ( in_array( $group, $this->no_mc_groups ) )
			return true;

		$expire = ( $expire == 0 ) ? $this->default_expiration : $expire;
		$result = apc_store( $key, $store_data, $expire );

		return $result;
	}

	function switch_to_blog( $blog_id ) {
		global $table_prefix;

		$blog_id = (int) $blog_id;
 		$this->blog_prefix = ( is_multisite() ? $blog_id : $table_prefix ) . ':';
	}

	function colorize_debug_line( $line ) {
		$colors = array(
			'get' => 'green',
			'set' => 'purple',
			'add' => 'blue',
			'delete' => 'red');

		$cmd = substr( $line, 0, strpos( $line, ' ' ) );

		$cmd2 = "<span style='color:{$colors[$cmd]}'>$cmd</span>";

		return $cmd2 . substr( $line, strlen( $cmd ) ) . "\n";
	}

	function stats() {
		echo "<p>\n";
		foreach ( $this->stats as $stat => $n ) {
			echo "<strong>$stat</strong> $n";
			echo "<br/>\n";
		}
		echo "</p>\n";
		echo "<h3>APC:</h3>";
		foreach ( $this->group_ops as $group => $ops ) {
			if ( !isset( $_GET['debug_queries'] ) && 500 < count( $ops ) ) {
				$ops = array_slice( $ops, 0, 500 );
				echo "<big>Too many to show! <a href='" . add_query_arg( 'debug_queries', 'true' ) . "'>Show them anyway</a>.</big>\n";
			}
			echo "<h4>$group commands</h4>";
			echo "<pre>\n";
			$lines = array();
			foreach ( $ops as $op ) {
				$lines[] = $this->colorize_debug_line($op);
			}
			print_r($lines);
			echo "</pre>\n";
		}
		if ( $this->debug ) {
			$apc_info = apc_cache_info();
			echo "<p>";
			echo "<strong>Cache Hits:</strong> {$apc_info['num_hits']}<br/>\n";
			echo "<strong>Cache Misses:</strong> {$apc_info['num_misses']}\n";
			echo "</p>\n";
		}
	}

	function WP_Object_Cache() {
		$this->abspath = md5( ABSPATH );

		global $blog_id, $table_prefix;
		$this->global_prefix = '';
		$this->blog_prefix = '';
		if ( function_exists( 'is_multisite' ) ) {
			$this->global_prefix = ( is_multisite() || defined('CUSTOM_USER_TABLE') && defined('CUSTOM_USER_META_TABLE') ) ? '' : $table_prefix;
			$this->blog_prefix = ( is_multisite() ? $blog_id : $table_prefix ) . ':';
		}

		$this->cache_hits =& $this->stats['get'];
		$this->cache_misses =& $this->stats['add'];
	}
}

if ( function_exists( 'apc_inc' ) ) {
	class APC_Object_Cache extends WP_Object_Cache {
		function incr( $id, $n = 1, $group = 'default' ) {
			return parent::incr2( $id, $n, $group );
		}
	}
} else {
	class APC_Object_Cache extends WP_Object_Cache {
		// Blank
	}
}

} // !function_exists( 'wp_cache_add' )

else : // No APC
	function apc_not_actually_running() {
		$GLOBALS['_wp_using_ext_object_cache'] = false;
		unset( $GLOBALS['wp_filter']['all'][-100]['apc_not_actually_running'] );
	}
	$GLOBALS['_wp_using_ext_object_cache'] = false; // This will get overridden as of WP 3.5, so we have to hook in to 'all':
	$GLOBALS['wp_filter']['all'][-100]['apc_not_actually_running'] = array( 'function' => 'apc_not_actually_running', 'accepted_args' => 0 );
	require_once ( ABSPATH . WPINC . '/cache.php' );
endif;
