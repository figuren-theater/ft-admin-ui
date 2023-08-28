<?php
/**
 * Modified Recent-Drafts Dashboard-Widget which shows all public post_types, not only the built-in ones.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Dashboard_Widgets\Recent_Drafts;

use function add_filter;
use function get_post_types;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {
	add_filter( 'dashboard_recent_drafts_query_args', __NAMESPACE__ . '\\recent_drafts_query_args' );
}

/**
 * Show all public post_types in list of recent drafts.
 *
 * @see https://developer.wordpress.org/reference/hooks/dashboard_recent_drafts_query_args/
 *
 * @param array<string, string|int> $query_args Arguments for WP_Query
 *
 * @return array<string, mixed>
 */
function recent_drafts_query_args( array $query_args ) : array {

	$args = [
		'public'   => true,
		// Include post & page (and attachment, which is useless).
		// '_builtin' => false // !
	];

	// phpcs:ignore // Can be 'names' or 'objects' (default: 'names').
	$output = 'names';
	// phpcs:ignore // Can be 'and' or 'or' (default: 'and').
	$operator = 'and';

	$post_types = get_post_types( $args, $output, $operator );
	$query_args['post_type'] = $post_types;

	// Using <= 3 disables 'View all drafts' Link.
	//
	// This leads to a 'posts' list-table,
	// that is not suiteable for other post_types,
	// using more than 3 is also useless,
	// because the "quick draft" widgets only shows 3 drafts at a time maximum.
	$query_args['posts_per_page'] = 3;

	// Query for all users aka authors.
	unset( $query_args['author'] );

	return $query_args;
}
