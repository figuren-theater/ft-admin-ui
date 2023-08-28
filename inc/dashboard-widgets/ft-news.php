<?php
/**
 * Dashboard Widgets with RSS from websites.fuer.figuren.theater and meta.figuren.theater
 *
 * @package figuren-theater\ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Dashboard_Widgets\FT_News;

use ABSPATH;

use function add_action;
use function apply_filters;
use function set_current_screen;
use function wp_add_dashboard_widget;
use function wp_dashboard_cached_rss_widget;
use function wp_die;
use function wp_unslash;
use function wp_widget_rss_output;
use function __;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	/**
	 * Register the dashboard widget.
	 *
	 * @see https://developer.wordpress.org/apis/handbook/dashboard-widgets/
	 */
	add_action( 'admin_init', __NAMESPACE__ . '\\ajax' );

	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\dashboard_setup' );
	add_action( 'wp_network_dashboard_setup', __NAMESPACE__ . '\\dashboard_setup' );

	// Special, rarely used view at /wp-admin/user/.
	add_action( 'wp_user_dashboard_setup', __NAMESPACE__ . '\\dashboard_setup' );

	add_action( 'admin_print_footer_scripts-index.php', __NAMESPACE__ . '\\scripts_and_styles', 999 );
}

/**
 * Defer loading of the RSS feed using the same (ajax) technique like the default "WordPress Events & News" dashboard widget.
 *
 * @return void
 */
function ajax() : void {
	add_action( 'wp_ajax_ft_dashboard_widgets', __NAMESPACE__ . '\\wp_ajax_ft_dashboard_widgets', 1 );
}

/**
 * Registers the f.t news/changelog/status widget to the dashboard.
 *
 * @return void
 */
function dashboard_setup() : void {
	wp_add_dashboard_widget(
		'ft_dashboard_primary',
		__( 'Neues aus dem figuren.theater Netzwerk', 'figurentheater' ),
		__NAMESPACE__ . '\\dashboard_news',
		null,
		null,
		'column3',
		'high'
	);

}

/**
 * Renders the f.t dashboard widget.
 *
 * @since WP 4.8.0
 *
 * @return void
 */
function dashboard_news() : void {
	?>
	<div class="wordpress-news hide-if-no-js">
		<?php ft_dashboard_primary(); ?>
	</div>

	<p class="community-events-footer">
		<?php
			printf(
				'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
				'https://websites.fuer.figuren.theater/changelog/',
				esc_html( __( 'Changelog', 'figurentheater' ) ),
				/* translators: Accessibility text. */
				esc_html( __( '(opens in a new tab)', 'figurentheater' ) )
			);
		?>

		|

		<?php
			printf(
				'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
				'https://meta.figuren.theater/blog/',
				esc_html( __( 'Status-Blog', 'figurentheater' ) ),
				/* translators: Accessibility text. */
				esc_html( __( '(opens in a new tab)', 'figurentheater' ) )
			);
		?>

		|

		<?php
			printf(
				'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
				/* translators: If a Rosetta site exists (e.g. https://es.wordpress.org/news/), then use that. Otherwise, leave untranslated. */
				'https://meta.figuren.theater/deine-unterstuetzung/',
				esc_html( __( 'figuren.theater braucht Dich', 'figurentheater' ) ),
				/* translators: Accessibility text. */
				esc_html( __( '(opens in a new tab)', 'figurentheater' ) )
			);
		?>
	</p>
	<?php
}

/**
 * 'WordPress Events and News' dashboard widget.
 *
 * @since WP 2.7.0
 * @since WP 4.8.0 Removed popular plugins feed.
 *
 * @return void
 */
function ft_dashboard_primary() : void {
	$feeds = [
		'webs'   => [

			/**
			 * Filters the primary link URL for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.5.0
			 *
			 * @param string $link The widget's primary link URL.
			 */
			'link'         => apply_filters( 'ft_dashboard_primary_link', __( 'https://websites.fuer.figuren.theater/', 'figurentheater' ) ),

			/**
			 * Filters the primary feed URL for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.3.0
			 *
			 * @param string $url The widget's primary feed URL.
			 */
			'url'          => apply_filters( 'ft_dashboard_primary_feed', __( 'https://websites.fuer.figuren.theater/feed/', 'figurentheater' ) ),

			/**
			 * Filters the primary link title for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.3.0
			 *
			 * @param string $title Title attribute for the widget's primary link.
			 */
			'title'        => apply_filters( 'ft_dashboard_primary_title', __( 'websites.fuer.figuren.theater Changelog', 'figurentheater' ) ),
			'items'        => 2,
			'show_summary' => 1,
			'show_author'  => 0,
			'show_date'    => 1,
		],
		'meta' => [

			/**
			 * Filters the secondary link URL for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.3.0
			 *
			 * @param string $link The widget's secondary link URL.
			 */
			'link'         => apply_filters( 'ft_dashboard_secondary_link', __( 'https://meta.figuren.theater/', 'figurentheater' ) ),

			/**
			 * Filters the secondary feed URL for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.3.0
			 *
			 * @param string $url The widget's secondary feed URL.
			 */
			'url'          => apply_filters( 'ft_dashboard_secondary_feed', __( 'https://meta.figuren.theater/feed/', 'figurentheater' ) ),

			/**
			 * Filters the secondary link title for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 2.3.0
			 *
			 * @param string $title Title attribute for the widget's secondary link.
			 */
			'title'        => apply_filters( 'ft_dashboard_secondary_title', __( 'meta.figuren.theater Statusblog', 'figurentheater' ) ),

			/**
			 * Filters the number of secondary link items for the 'WordPress Events and News' dashboard widget.
			 *
			 * @since WP 4.4.0
			 *
			 * @param int $items How many items to show in the secondary feed.
			 */
			'items'        => apply_filters( 'ft_dashboard_secondary_items', 1 ),
			'show_summary' => 1,
			'show_author'  => 1,
			'show_date'    => 1,
		],
	];

	wp_dashboard_cached_rss_widget( 'ft_dashboard_primary', __NAMESPACE__ . '\\ft_dashboard_primary_output', $feeds );
}
/**
 * Add some needed CSS & JS inline to the admin-footer of index.php views.
 *
 * @return void
 */
function scripts_and_styles() {
	?>
	<style>
		#ft_dashboard_primary .widget-loading{
			padding:12px 12px 0;
			margin-bottom:1em!important
		}
		#ft_dashboard_primary .inside .notice{
			margin:0
		}
		#ft_dashboard_primary .inside {
			margin: 0;
			padding: 0;
		}
		#ft_dashboard_primary .rss-widget:last-child {
			border-bottom: none;
			padding-bottom: 0;
		}
		#ft_dashboard_primary .rss-widget {
			border-bottom: 1px solid #eee;
			font-size: 13px;
			padding: 12px;
		}
	</style>
	<script>
		//		console.log( Window );
		//		console.log( Window.ajaxWidgets );

		jQuery(document).ready( function($) {

			window.ajaxWidgets = ['ft_dashboard_primary'];

			/**
			 * Triggers widget updates via Ajax.
			 *
			 * @since 2.7.0
			 *
			 * @global
			 *
			 * @param {string} el Optional. Widget to fetch or none to update all.
			 *
			 * @return {void}
			 */
			window.ajaxPopulateWidgets = function(el) {
				/**
				 * Fetch the latest representation of the widget via Ajax and show it.
				 *
				 * @param {number} i Number of half-seconds to use as the timeout.
				 * @param {string} id ID of the element which is going to be checked for changes.
				 *
				 * @return {void}
				 */
				function show(i, id) {
					var p, e = $('#' + id + ' div.inside:visible').find('.widget-loading');
					// If the element is found in the dom, queue to load latest representation.
					if ( e.length ) {
						p = e.parent();
						setTimeout( function(){
							// Request the widget content.
							p.load( ajaxurl + '?action=ft_dashboard_widgets&widget=' + id + '&pagenow=' + pagenow, '', function() {
								// Hide the parent and slide it out for visual fancyness.
								p.hide().slideDown('normal', function(){
									$(this).css('display', '');
								});
							});
						}, i * 500 );
					}
				}

				// If we have received a specific element to fetch, check if it is valid.
				if ( el ) {
					el = el.toString();
					// If the element is available as Ajax widget, show it.
					if ( $.inArray(el, ajaxWidgets) !== -1 ) {
						// Show element without any delay.
						show(0, el);
					}
				} else {
					// Walk through all ajaxWidgets, loading them after each other.
					$.each( ajaxWidgets, show );
				}
			};



			ajaxPopulateWidgets();

		});
	</script>
	<?php
}

/**
 * Displays the WordPress events and news feeds.
 *
 * @since 3.8.0
 * @since 4.8.0 Removed popular plugins feed.
 *
 * @param string                $widget_id Widget ID.
 * @param array<string, array<string, string>>  $feeds     Array of RSS feeds.
 *
 * @return void
 */
function ft_dashboard_primary_output( $widget_id, $feeds ) : void {

	foreach ( $feeds as $args ) {
		echo '<div class="rss-widget">';
			echo '<h3>' . esc_html( $args['title'] ) . '</h3>';
				wp_widget_rss_output( $args['url'], $args );

		echo '</div>';
	}
}

/**
 * Ajax handler for dashboard widgets.
 *
 * COPIED FROM CORE
 * BECAUSE OF MISSING FILTER
 *
 * @todo find nicer way to handle this
 *
 * @since WP 3.4.0
 *
 * @return void
 */
function wp_ajax_ft_dashboard_widgets() : void {

	// Needed for functions used inside of ft_dashboard_primary.
	require_once ABSPATH . 'wp-admin/includes/dashboard.php';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $_GET['pagenow'] ) || ! isset( $_GET['widget'] ) ) {
		wp_die();
	}

	$pagenow = wp_unslash( \getenv( 'pagenow' ) );
	if ( 'dashboard-user' === $pagenow || 'dashboard-network' === $pagenow || 'dashboard' === $pagenow ) {
		set_current_screen( $pagenow );
	}

	switch ( \getenv( 'widget' ) ) {
		case 'ft_dashboard_primary':
			ft_dashboard_primary();
			break;
	}
	wp_die();
}
