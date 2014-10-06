<?php
/**
 * codedForest::DNA Framework
 *
 * The framework is developed to create a plugin settings page with one or more settings tabs.
 * This file has all the necessary logic to this.
 *
 * The framework logic is divided into four main branches,
 * depending on the place execution of the script:
 *		1. When the script runs in the Frontend,
 *	the framework files are not connected to the script
 *	and the framework does not work at all.
 *		2. When the script runs in the Backend but not in plugin page,
 *	the framework current file connected to the script
 *	and just initiate plugin menu.
 *		3. When the script runs in the Backend and shows plugin page,
 *	current file initiate plugin menu and then loads plugin page file
 *	for output page HTML.
 *		4. When processing ajax actions, current file loads, but without menu initiation.
 *	Activates only ajax actions, and after processing exits from the script.
 *
 *
 * @author Artem Frolov (dikiyforester)
 * @version 1.0.2
 * @package dna
 */
$dna_path = __FILE__;

if ( !class_exists( 'DNA_Framework' ) ) {

	class DNA_Framework {

		/********************************************************************
		 * ========================= MAIN OPTIONS ===========================
		 * ******************************************************************/
		/** @var string Current DNA folder */
		var $dna_path;
		/** @var string Main Plugin file path */
		var $plugin_path = '';
		/** @var array Default options */
		var $default_options = array();
		/** @var array Options which must be deleted on deactivation */
		var $delete_options = array();
		/** @var bool|string Trigger for self deactivaion. If string - returns wp_die( $self_deactivation ) message */
		var $self_deactivation = false;
		/** @var string Plugin hook; */
		var $hook;

		/********************************************************************
		 * =========================== ACTIONS ==============================
		 * ******************************************************************/
		/**
		 * @var array Contains all pending actions
		 *
		 * Standard actions used in ajax requests:
		 * dna_submit - activates on form submit
		 * dna_get_option_tab - activates on tab content request
		 * any other actions can be added through plugin config file
		 */
		var $actions = array();

		/********************************************************************
		 * ======================= MENU INITIATION ==========================
		 * ******************************************************************/
		/** @var string Init action */
		var $init_action = 'admin_menu';
		/** @var bool Menu level */
		var $submenu = true;
		/** @var string Parent slug */
		var $parent_slug = 'options-general.php';
		/** @var string Page title */
		var $page_title = 'Default settings page of the "codedForest::DNA" framework';
		/** @var string Menu title */
		var $menu_title = 'codedForest::DNA';
		/** @var string User Capability */
		var $capability = 'manage_options';
		/** @var string Menu slug */
		var $menu_slug = 'dna-default';
		/** @var string Get HTML function name */
		var $function = 'form_html';
		/** @var string Menu icon url */
		var $icon_url = '';
		/** @var string Page icon url */
		var $page_icon_id = 'icon-options-general';
		/** @var string  Plugin text domain */
		protected $td = false;

		/********************************************************************
		 * ======================= SETTINGS PAGE ============================
		 * ******************************************************************/
		/** @var array Options form html attributes */
		var $form = array(
			'id' => 'dna_options_form',
			'name' => 'dna_options_form',
			'class' => 'dna_options_form',
			'method' => 'post',
			'action' => '',
			'enctype' => 'multipart/form-data',
		);
		/** @var array Options tabs array (if needed)*/
		var $tabs = array('dna'=> array ( 'title' => '' ));

		/********************************************************************
		 * ====================== SCRIPTS / STYLES ==========================
		 * ******************************************************************/
		var $css = array();
		var $js = array();
		var $js_vars = array();


		function __construct( $plugin_path ) {
			global $dna_path;
			$this->plugin_path = plugin_basename( $plugin_path );
			$this->dna_path = dirname( $dna_path );
			add_action( 'init', array( &$this, 'load_textdomain' ) );
			// Decide which branch of logic to initiate
			if ( isset( $_POST[ 'dna_nonce' ] ) && isset( $_POST[ 'dna_plugin' ] ) && dirname( $this->plugin_path ) === $_POST[ 'dna_plugin' ] ) {
				// This means, that dna should process ajax actions and nothing else
				add_action( 'wp_ajax_dna_ajax', array( &$this, 'ajax' ) );
			} else {
				// init menu action
				add_action( 'init', array( &$this, 'init_action' ) );
				add_action( 'admin_init', array( &$this, 'self_deactivation' ) );
				register_activation_hook( $plugin_path, array( &$this, 'activate' ) );
				register_deactivation_hook( $plugin_path, array( &$this, 'deactivate' ) );
			}

		}

		function set_textdomain( $domain, $path ){
			if( ! $domain || ! $path )
				return;

			$this->td[] = array(
				'domain' => $domain,
				'path' => $path
			);
		}

		public function load_textdomain() {
			if ( $this->td ) {
				foreach ( $this->td as $td ) {
					$locale = apply_filters( 'plugin_locale', get_locale(), $td['domain'] );
					$base = $td['path'];
					load_textdomain( $td['domain'], WP_LANG_DIR . "/plugins/$base-$locale.mo" );
				}
			}
		}


		function init_menu () {
			if ( $this->submenu ) {
				$this->hook = add_submenu_page(
					$this->parent_slug, $this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array ( &$this, $this->function )
				);
			} else {
				$this->hook = add_menu_page(
					$this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array ( &$this, $this->function ), $this->icon_url
				);
			}
			// Hooks on the hook for some events
			$this->do_action( 'dna_viewer' );
			// Only if we are on plugin page - we can load DNA admin page file (init third logic branch)
			if ( isset( $_GET['page'] ) && $this->menu_slug === $_GET['page'] ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 100 );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );
				//add_action( 'admin_print_styles-' . $this->hook, array( &$this, 'enqueue_styles' ), 100 );
				//add_action( 'admin_print_scripts-' . $this->hook, array( &$this, 'enqueue_scripts' ), 100 );
			}
		}

		function init_action () {
			add_action( $this->init_action, array( &$this, 'init_menu' ) );
		}

		function ajax () {
			if ( ! wp_verify_nonce( $_POST[ 'dna_nonce' ], 'dna-nonce-' . dirname( $this->plugin_path ) ) )
				die( 'Permissions check failed' );
			if ( ! isset( $_POST[ 'dna_tab' ] ) )
				die( 'Options tab not found' );
			if ( ! isset( $_POST[ 'dna_action' ] ) )
				die( 'Action not found' );

			$this->do_action( $_POST[ 'dna_action' ], $_POST[ 'dna_tab' ] );
			die();
		}

		function tabs() {
			return $this->apply_filters( 'tabs_array', $this->tabs );
		}

		function tab_content ( $func ) {
			$this->add_action( 'dna_get_option_tab', $func );
		}

		function submit ( $func ) {
			$this->add_action( 'dna_submit', $func );
		}

		function activate () {
			$this->install_options();
		}

		function deactivate () {
			$this->delete_options();
		}

		function self_deactivation() {
			$this->self_deactivation = $this->apply_filters( 'self_deactivation', $this->self_deactivation );
			if( $this->self_deactivation ) {
				deactivate_plugins( $this->plugin_path ); // Deactivate ourself
				wp_die( $this->self_deactivation );
			}
		}

		function install_options () {
			$this->update_options( $this->apply_filters( 'default_options', $this->default_options ) );
		}

		function delete_options () {
			$options = $this->apply_filters( 'delete_options', $this->delete_options );
			foreach ( $options as $option ) {
				delete_option( $option );
			}
		}

		function update_options ( $options = array(), $clear = false ) {
			if ( ! current_user_can( 'manage_options' ) )
				return;
			foreach ( $options as $name => $value ) {
				if ( $clear || ! get_option( $name ) )
					update_option( $name, $value );
			}
		}

		function import_options ( $import ) {
			require_once( $this->dna_path . '/Ini.php' );
			$this->update_options( DNA_Ini::import( 'importSettings', $import ), true );
		}

		function export_options ( $export ) {
			require_once( $this->dna_path . '/Ini.php' );
			DNA_Ini::export( $export );
		}

		function clear_options () {
			$this->update_options( $this->apply_filters( 'default_options', $this->default_options ), true );
		}

		function add_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
			if ( $src ) {
				wp_register_style( $handle, $src, $deps, $ver, $media );
				$this->css[] = $handle;
			}
		}

		function enqueue_styles( $hook ) {

			if ( $this->hook !== $hook )
				return;

			// Enqueue own styles
			wp_enqueue_style( 'dna-style', plugin_dir_url( __FILE__ ) . 'css/dna-style.css' );
			// Enqueue plugin registered styles
			foreach ( $this->css as $style ) {
				wp_enqueue_style( $style );
			}
		}

		function add_script( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
			if ( $src ) {
				wp_register_script( $handle, $src, $deps, $ver, $in_footer );
				$this->js[] = $handle;
			}
		}

		function enqueue_scripts( $hook ) {

			if ( $this->hook !== $hook )
				return;

			// Enqueue own scripts
			wp_enqueue_script( 'jquery-form' );
			wp_enqueue_script( 'easytooltip', get_template_directory_uri() . '/includes/js/easyTooltip.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'dna-script', plugin_dir_url( __FILE__ ) . 'js/dna-script.js', array( 'jquery' ) );
			// Enqueue plugin registered scripts
			foreach ( $this->js as $script ) {
				wp_enqueue_script( $script );
			}
			$this->enqueue_js_vars();
		}

		function add_js_var ( $handle, $var = '' ) {
			$this->js_vars[ $handle ] = $var;
		}

		function enqueue_js_vars () {
			$tabs = array_keys( $this->tabs() );
			$this->js_vars = array(
					'dna_options_form' => $this->form['id'],
					'dna_default_tab' => reset( $tabs ),
					//'dna_nonce' => wp_create_nonce( 'dna-nonce' )
			);
			wp_localize_script( 'dna-script', 'dna_vars', $this->js_vars );
		}

		function add_action ( $tag = false, $func = false ) {
			if ( $tag && $func )
				$this->actions[ $tag ][] = $func;
		}

		function add_filter ( $tag = false, $func = false ) {
			$this->add_action( $tag, $func );
		}

		function apply_filters ( $tag = '', $args = array() ) {
			$num_args = func_num_args();
			if ( $num_args < 2 )
				return;
			elseif ( $num_args >= 2 )
				$args = array_slice( func_get_args(), 1 );
			$val = false;
			if ( $tag && in_array( $tag, array_keys( $this->actions ) ) ){
				$funcs = $this->actions[ $tag ];
				foreach ( $funcs as $func ) {
					if ( function_exists( $func ) )
						$val = call_user_func_array( $func, $args );
				}
			}
			return $val;
		}

		function do_action ( $tag = '', $args = array() ) {
			$num_args = func_num_args();
			if ( $num_args < 1 )
				return;
			elseif ( $num_args > 1 )
				$args = array_slice( func_get_args(), 1 );

			if ( $tag && in_array( $tag, array_keys( $this->actions ) ) ){
				$funcs = $this->actions[ $tag ];
				foreach ( $funcs as $func ) {
					if ( function_exists( $func ) )
						call_user_func_array( $func, $args );
				}
			}
		}


		function form_html() {
			?>
			<div class="wrap">
				<div id="<?php echo esc_attr( $this->page_icon_id ); ?>" class="dna-ico icon32"><br /></div>
				<h2><?php echo esc_html( $this->page_title ); ?></h2><br/>
				<div>
					<form id="<?php echo esc_attr( $this->form[ 'id' ] ); ?>" class="<?php echo esc_attr( $this->form[ 'class' ] ); ?>" name="<?php echo esc_attr( $this->form[ 'name' ] ); ?>" action="<?php echo esc_attr( $this->form[ 'action' ] ); ?>" method="<?php echo esc_attr( $this->form[ 'method' ] ); ?>" enctype="<?php echo esc_attr( $this->form[ 'enctype' ] ); ?>">
						<?php echo $this->print_tabs(); ?>
						<div id="dna_option_tab"></div>
						<input type="hidden" id="dna_nonce" name="dna_nonce" value="<?php echo wp_create_nonce( 'dna-nonce-'. dirname( $this->plugin_path ) ); ?>">
						<input type="hidden" id="dna_plugin" name="dna_plugin" value="<?php echo dirname( $this->plugin_path ); ?>">
						<input type="hidden" name="dna_submit_hidden" value="Y">
						<p><input type="submit" id="dna_submit_btn" class="button-primary" name="Save_bottom" value="<?php esc_attr_e( 'Save Changes' );?>" style="display:none;"/></p>
					</form>
				</div>
			</div>
			<?php
		}

		function print_tabs() {
			$tabs = $this->tabs();?>
			<h3 class="nav-tab-wrapper">
				<div id="dna-preloader-wrap">
					<span id="dna-preloader" class="dna-preloader" style="display:none; margin-left:0;"></span>
				</div>
				<?php
					if ( is_array( $tabs ) ) {
						foreach ( $tabs as $tab => $val ) { ?>
							<span id="dna-tab-<?php echo esc_attr( $tab ); ?>" dna_tab="<?php echo esc_attr( $tab ); ?>" class="nav-tab dna-tab tabtip" tip="<?php echo ( isset( $val['desc'] ) ) ? esc_attr( $val['desc'] ) : ''; ?>" <?php if(count( $tabs ) < 2) { echo 'style="display:none;"';  } ?>><?php echo esc_html( $val[ 'title' ] ); ?></span>
						<?php }
					} ?>
			</h3>
			<?php
		}

		function print_subtabs( $subtabs ) {
			if ( is_array( $subtabs ) && count( $subtabs ) < 2 )
				return false;
			?>
				<div class="slidwrap widefat">
					<h3 class="nav-tab-wrapper">
						<?php foreach ( $subtabs as $subtab => $val ) { ?>
							<span id="col<?php echo esc_attr( $subtab ); ?>" class="nav-tab options-column"><?php echo esc_html( $val[ 'title' ] ); ?></span>
						<?php } ?>
					</h3>
				</div>
			<?php
		}
	}

	function __call( $name, $arguments ) {
		return false;
	}
}