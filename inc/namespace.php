<?php
/**
 * Figuren_Theater Admin_UI.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI;

use Altis;

use function is_admin;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {

	$default_settings = [
		'enabled'       => is_admin(), // Is needed by Altis!
		'emoji-toolbar' => false,
	];
	$options          = [
		'defaults' => $default_settings,
	];

	Altis\register_module(
		'admin_ui',
		DIRECTORY,
		'Admin_UI',
		$options,
		__NAMESPACE__ . '\\bootstrap'
	);
}

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	// Plugins
	Emoji_Toolbar\bootstrap();
	Heartbeat_Control\bootstrap();
	Disable_Gutenberg_Blocks\bootstrap();

	// Best Practices & Misc.
	Dashboard_Widgets\bootstrap();
	Featured_Image_Column\bootstrap();
	Pending_Posts_Bubble\bootstrap();
}
