<?php
/**
 * Figuren_Theater Admin_UI Dashboard_Widgets.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Dashboard_Widgets;

use function add_action;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() {

	// Hook onto 'admin_init' to make sure AJAX is available.
	add_action( 'admin_init', __NAMESPACE__ . '\\load', 0 );
}

/**
 * Load all modifications.
 *
 * @return void
 */
function load(): void {

	FT_News\bootstrap();
	Recent_Drafts\bootstrap();
}
