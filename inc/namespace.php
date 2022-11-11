<?php
/**
 * Figuren_Theater Admin_UI.
 *
 * @package figuren-theater/admin_ui
 */

namespace Figuren_Theater\Admin_UI;

use Altis;
use function Altis\register_module;

/**
 * Register module.
 */
function register() {
	Altis\register_module(
		'admin_ui',
		DIRECTORY,
		'Admin_UI',
		[
			'defaults' => [
				'enabled' => true,
			],
		],
		__NAMESPACE__ . '\\bootstrap'
	);
}

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	Heartbeat_Control\bootstrap();
	Dashboard_Widgets\bootstrap();
	Disable_Gutenberg_Blocks\bootstrap();
	Multisite_Enhancements\bootstrap();
}
