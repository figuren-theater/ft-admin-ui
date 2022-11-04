<?php
/**
 * Figuren_Theater Admin_UI Heartbeat_Control.
 *
 * @package figuren-theater/admin_ui/heartbeat_control
 */

namespace Figuren_Theater\Admin_UI\Heartbeat_Control;

// use Altis;

use function remove_submenu_page;

/**
 * Register module.
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
 */

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'admin_init', __NAMESPACE__ . '\\load_plugin' );
	
}

function load_plugin() {

	require_once WP_PLUGIN_DIR . '/heartbeat-control/heartbeat-control.php';
	
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu' );


}

function remove_menu( ) : void {
	remove_submenu_page( 'options-general.php', 'heartbeat_control_settings' );
}
