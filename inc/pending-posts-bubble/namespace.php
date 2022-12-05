<?php
/**
 * Add a Admin Menu Notification Bubble
 * with the count of pending-reviews for each post_type.
 *
 * Inspiration:
 *
 * @see http://wordpress.stackexchange.com/a/95058/20992
 *
 * Figuren_Theater Admin_UI Pending_Posts_Bubble.
 *
 * @package figuren-theater/admin_ui/pending_posts_bubble
 */

namespace Figuren_Theater\Admin_UI\Pending_Posts_Bubble;

use function add_action;
use function add_post_type_support;
use function delete_transient;
use function get_post_types_by_support;
use function get_transient;
use function set_transient;
use function wp_count_posts;
/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	// Hook onto 'admin_init' to make sure AJAX is available.
	add_action( 'admin_init', __NAMESPACE__ . '\\load' );
}

function load() {

	add_action( 'admin_menu', __NAMESPACE__ . '\\pending_posts_bubble', 1 );

	//
	add_action( 'transition_post_status', __NAMESPACE__ . '\\pending_posts_bubble__reset', 10, 3 );
}



function pending_posts_bubble() {

	global $menu;

	// prepare builtin post_types
	add_post_type_support( 'post', 'ft_pending_bubbles' );
	add_post_type_support( 'page', 'ft_pending_bubbles' );


	// Check for transient. If none, then execute WP_Query
	if ( false === ( $ft_pending_bubbles = get_transient( '_ft_pending_bubbles' ) ) ) {

		$post_types = get_post_types_by_support( 'ft_pending_bubbles' );

		$ft_pending_bubbles = [];

		foreach( $post_types as $pt )
		{
			// Count posts
			$cpt_count = wp_count_posts( $pt, 'readable' );

			// sum drafts and pending posts
			$ft_pending_bubbles[ $pt ] = $cpt_count->pending + $cpt_count->draft;
		}

		// Put the results in a transient. Expire after 1 hour.
		set_transient( '_ft_pending_bubbles', $ft_pending_bubbles, \DAY_IN_SECONDS );
	}



	foreach( $ft_pending_bubbles as $pt => $count )
	{

		if ( $count ) 
		{

			// Menu link suffix, Post is different from the rest
			$suffix = ( 'post' == $pt ) ? '' : "?post_type=$pt";

			// Locate the key of 
			$key = __recursive_array_search_php_91365( "edit.php$suffix", $menu );

			// Not found, just in case 
			if( !$key )
				return;

			// Modify menu item
			$menu[$key][0] .= sprintf(
				'<span class="update-plugins count-%1$s" style="margin-left:5px;"><span class="plugin-count">%1$s</span></span>',
				$count 
			);
		}
	}
}

/**
 * Do stuff only when posts are actually transitioned from one status to another.
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 */
function pending_posts_bubble__reset( $new_status, $old_status, $post ) {
	if ( $old_status === $new_status )
		return;
	
	if ( 
		( $old_status !== 'draft' && $old_status !== 'pending' )
		&&
		( $new_status !== 'draft' && $new_status !== 'pending' )
	)
		return;
	
	delete_transient( '_ft_pending_bubbles' );
}

// http://www.php.net/manual/en/function.array-search.php#91365
function __recursive_array_search_php_91365( $needle, $haystack ) {

	foreach( $haystack as $key => $value ) 
	{
		$current_key = $key;
		if( 
			$needle === $value 
			OR ( 
				is_array( $value )
				&& __recursive_array_search_php_91365( $needle, $value ) !== false 
			)
		) 
		{
			return $current_key;
		}
	}
	return false;
}
