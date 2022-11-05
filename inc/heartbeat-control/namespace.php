<?php
/**
 * Figuren_Theater Admin_UI Heartbeat_Control.
 *
 * @package figuren-theater/admin_ui/heartbeat_control
 */

namespace Figuren_Theater\Admin_UI\Heartbeat_Control;

use Altis;

use function add_action;
use function apply_filters;
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
const BASENAME = 'heartbeat-control/heartbeat-control.php';

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
	
}

function load_plugin() {

	require_once Altis\ROOT_DIR . '/vendor/' . BASENAME;
	
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );


}


function filter_options() {
		
		$_options = [
			'rules_dash' => [
				0 => [
					'heartbeat_control_behavior' => 'modify',
					'heartbeat_control_frequency' => '93',
				]
			],
			'rules_front' => [
				0 => [
					// do not 'disable' any of the options, because WP Admin will error you either
					'heartbeat_control_behavior' => 'modify', 
					'heartbeat_control_frequency' => '183',
				]
			],
			'rules_editor' => [
				0 => [
					'heartbeat_control_behavior' => 'modify',
					'heartbeat_control_frequency' => '93',
				],
			],
		];

		// gets added to the 'OptionsCollection' 
		// from within itself on creation
		new Options\Option(
			'heartbeat_control_settings',
			$_options,
			BASENAME
		);
		new Options\Option(
			'imagify_settings',
			0,
			BASENAME
		);
		new Options\Option(
			'imagify_settings',
			0,
			BASENAME,
			'site_option'
		);
	}

function remove_menu( ) : void {
	remove_submenu_page( 'options-general.php', 'heartbeat_control_settings' );
}
