<?php

/**
 * Here placed any acfcp hooks
 *
 * @since 1.1
 * @uses add_action() calls to trigger the hooks.
 *
 */

/**
 * add action after title in loop ad meta (in line with author and category)
 *
 * @since 1.1
 * @param object $post
 *
 */
function acf_loop_top( $post ) {
	do_action( 'acf_loop_top', $post );
}

/**
 * add action after description in loop ad meta (in line with posted and total viewed)
 *
 * @since 1.1
 * @param object $post
 *
 */
function acf_loop_bottom( $post ) {
	do_action( 'acf_loop_bottom', $post );
}

?>