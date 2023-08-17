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
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Pending_Posts_Bubble;

use DAY_IN_SECONDS;
use function add_action;
use function delete_transient;
use function get_post_types_by_support;
use function set_transient;
use function wp_count_posts;
use WP_Post;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() {

	// Hook onto 'admin_init' to make sure AJAX is available.
	add_action( 'admin_init', __NAMESPACE__ . '\\load' );
}
/**
 * Load all modifications.
 *
 * @return void
 */
function load() :void {

	add_action( 'admin_menu', __NAMESPACE__ . '\\pending_posts_bubble', 1 );

	add_action( 'transition_post_status', __NAMESPACE__ . '\\pending_posts_bubble__reset', 10, 3 );
}

/**
 * Get post types that support the 'ft_pending_bubbles' feature.
 *
 * @return array An array of post type names.
 */
function get_supported_post_types() :array {
	return get_post_types_by_support( 'ft_pending_bubbles' );
}

/**
 * Count pending and draft posts for a given post type.
 *
 * @param string $post_type The post type name.
 *
 * @return int The total count of pending and draft posts.
 */
function count_pending_and_draft_posts( $post_type ) :int {
	$cpt_count = wp_count_posts( $post_type, 'readable' );
	return $cpt_count->pending + $cpt_count->draft;
}

/**
 * Modify the menu item to include a bubble notification.
 *
 * @param string $menu_link The URL of the menu item.
 * @param int    $count     The count to be displayed in the bubble.
 *
 * @return string The modified menu link HTML.
 */
function add_bubble_to_menu_item( $menu_link, $count ) :string {
	return $menu_link . sprintf(
		'<span class="update-plugins count-%1$s" style="margin-left:5px;"><span class="plugin-count">%1$s</span></span>',
		$count
	);
}

/**
 * Adds bubble notifications for pending posts to admin menu items.
 *
 * This function adds bubble notifications to admin menu items for post types
 * that have pending or draft posts. It counts the number of pending and draft posts
 * for each post type and adds a bubble notification to the respective menu item.
 *
 * @global $menu
 *
 * @return void
 */
function pending_posts_bubble() :void {
	global $menu;

	$supported_post_types = get_supported_post_types();
	$ft_pending_bubbles = [];

	foreach ( $supported_post_types as $pt ) {
		$ft_pending_bubbles[ $pt ] = count_pending_and_draft_posts( $pt );
	}

	set_transient( '_ft_pending_bubbles', $ft_pending_bubbles, DAY_IN_SECONDS );

	foreach ( $ft_pending_bubbles as $pt => $count ) {
		if ( $count ) {
			$suffix = ( 'post' === $pt ) ? '' : "?post_type=$pt";
			$menu_link = "edit.php$suffix";

			$key = array_search(
				$menu_link,
				array_column( $menu, 2 ),
				true
			);

			if ( false !== $key ) {
				// @todo #10 Find a more standard-friendly way of changing global $menu
				// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$menu[ $key ][0] = add_bubble_to_menu_item( $menu[ $key ][0], $count );
			}
		}
	}
}

/**
 * Do stuff only when posts are actually transitioned from one status to another.
 *
 * @see https://developer.wordpress.org/reference/hooks/transition_post_status/
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 *
 * @return void
 */
function pending_posts_bubble__reset( string $new_status, string $old_status, WP_Post $post ) :void {
	if ( $old_status === $new_status ) {
		return;
	}

	if ( ( $old_status !== 'draft' && $old_status !== 'pending' )
		&&
		( $new_status !== 'draft' && $new_status !== 'pending' )
	) {
		return;
	}

	delete_transient( '_ft_pending_bubbles' );
}

/**
 * Search recursively within array-values.
 *
 * @source http://www.php.net/manual/en/function.array-search.php#91365
 *
 * @param mixed $needle   What to look for?
 * @param array $haystack Where to look for?
 *
 * @return mixed Returns the key of a found value or false, if non exists.
 */
function recursive_array_search_php_91365( $needle, $haystack ) :mixed {

	foreach ( $haystack as $key => $value ) {
		$current_key = $key;
		if ( $needle === $value
			or (
				is_array( $value )
				&& recursive_array_search_php_91365( $needle, $value ) !== false
			)
		) {
			return $current_key;
		}
	}
	return false;
}
