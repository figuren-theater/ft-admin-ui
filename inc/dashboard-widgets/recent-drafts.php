<?php


namespace Figuren_Theater\Admin_UI\Dashboard_Widgets\Recent_Drafts;

use function add_filter;
use function get_post_types;

function bootstrap() {
	add_filter( 'dashboard_recent_drafts_query_args', __NAMESPACE__ . '\\recent_drafts_query_args' );
}


/**
 * Show all public post_types in list of recent drafts.
 * 
 * @see https://developer.wordpress.org/reference/hooks/dashboard_recent_drafts_query_args/ 
 */
function recent_drafts_query_args( array $query_args ) : array {

	$args = array(
	   'public'   => true,
	#   '_builtin' => false  // include post & page (and attachment, which is useless)
	);
	  
	$output   = 'names'; // 'names' or 'objects' (default: 'names')
	$operator = 'and'; // 'and' or 'or' (default: 'and')
	  
	$post_types = get_post_types( $args, $output, $operator );
	
	
	$query_args[ 'post_type' ]      = $post_types;
	$query_args[ 'posts_per_page' ] = 3; // using <= 3 disables 'View all drafts' Link, which leads to a 'posts' list-table, that is not suiteable for other post_types, using more than 3 is also useless, because the "quick draft" widgets only shows 3 drafts at a time maximum

	// for all users/authors
	unset( $query_args[ 'author' ] );

	return $query_args;
}
