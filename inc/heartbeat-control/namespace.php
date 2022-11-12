<?php
/**
 * Figuren_Theater Admin_UI Heartbeat_Control.
 *
 * @package figuren-theater/admin_ui/heartbeat_control
 */

namespace Figuren_Theater\Admin_UI\Heartbeat_Control;

use Figuren_Theater\Options;

use FT_VENDOR_DIR;

use function add_action;
use function remove_action;
use function remove_submenu_page;

const BASENAME   = 'heartbeat-control/heartbeat-control.php';
const PLUGINPATH = FT_VENDOR_DIR . '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
	
}

function load_plugin() {

	require_once PLUGINPATH;
	
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\remove_scripts', 9 );

	// might be a little too global
	// but this saves: 30kb
	add_filter( 'cmb2_enqueue_css', '__return_false', 0 );

	
	add_filter( 'load_textdomain_mofile', __NAMESPACE__ . '\\unload_i18n', 0, 2 );
}

function unload_i18n( string $mofile, string $domain ) : string {
	if ( 'cmb2' === $domain ) {
		return '';
	}
	return $mofile;
}


function filter_options() {
	
	$_options = [
		'rules_dash'   => [
			0 => [
				'heartbeat_control_behavior'  => 'modify',
				'heartbeat_control_frequency' => '93',
			],
		],
		'rules_front'  => [
			0 => [
				// do not 'disable' any of the options, because WP Admin will error you either
				'heartbeat_control_behavior'  => 'modify', 
				'heartbeat_control_frequency' => '183',
			],
		],
		'rules_editor' => [
			0 => [
				'heartbeat_control_behavior'  => 'modify',
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

function remove_menu() : void {
	remove_submenu_page( 'options-general.php', 'heartbeat_control_settings' );
}

function remove_scripts() : void {
	// might be a little too global
	// but this saves: 30kb
	remove_action( 'admin_footer', [ 'CMB2_JS', 'enqueue' ], 8 );
}
