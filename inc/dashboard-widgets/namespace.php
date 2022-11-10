<?php
/**
 * Figuren_Theater Admin_UI Dashboard_Widgets.
 *
 * @package figuren-theater/admin_ui/dashboard_widgets
 */

namespace Figuren_Theater\Admin_UI\Dashboard_Widgets;

use function add_action;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'admin_menu', __NAMESPACE__ . '\\load', 0 );
}

function load() {

	FT_News\bootstrap();
	Recent_Drafts\bootstrap();

}
