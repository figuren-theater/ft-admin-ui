<?php
/**
 * Figuren_Theater Admin_UI Heartbeat_Control.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Heartbeat_Control;

use Figuren_Theater\Options;

use FT_VENDOR_DIR;

use function add_action;
use function remove_action;
use function remove_submenu_page;

const BASENAME   = 'heartbeat-control/heartbeat-control.php';
const PLUGINPATH = '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );

	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );

}

/**
 * Conditionally load the plugin itself and its modifications.
 *
 * @return void
 */
function load_plugin() {

	require_once FT_VENDOR_DIR . PLUGINPATH; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\remove_scripts', 9 );

	// Might be a little too global but,
	// this saves: 30kb on each /wp-admin request.
	add_filter( 'cmb2_enqueue_css', '__return_false', 0 );
	add_filter( 'load_textdomain_mofile', __NAMESPACE__ . '\\unload_i18n', 0, 2 );
}

/**
 * Unloads the specified MO file for localization based on the domain.
 *
 * This function unloads the specified MO file for localization based on the provided domain.
 * If the domain is 'cmb2', the function returns an empty string, effectively
 * preventing the MO file from being loaded. Otherwise, the function returns the original MO file path.
 *
 * @param string $mofile The path to the MO file for localization.
 * @param string $domain The domain associated with the localization.
 *
 * @return string The path to the MO file or an empty string if unloading is needed.
 */
function unload_i18n( string $mofile, string $domain ) : string {
	// Check if the domain is 'cmb2'.
	if ( 'cmb2' === $domain ) {
		// If the domain is 'cmb2', prevent loading and return an empty string.
		return '';
	}

	// If the domain is not 'cmb2', return the original MO file path.
	return $mofile;
}

/**
 * Handle options
 *
 * @return void
 */
function filter_options() :void {

	$_options = [
		'rules_dash'   => [
			0 => [
				'heartbeat_control_behavior'  => 'modify',
				'heartbeat_control_frequency' => '93',
			],
		],
		'rules_front'  => [
			0 => [
				// Do not 'disable' any of the options,
				// because WP Admin will error you either.
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

	/*
	 * Gets added to the 'OptionsCollection'
	 * from within itself on creation.
	 */
	new Options\Option(
		'heartbeat_control_settings',
		$_options,
		BASENAME
	);

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_data = \get_plugin_data( FT_VENDOR_DIR . PLUGINPATH, false, false );
	new Options\Option(
		'heartbeat_control_version',
		$plugin_data['Version'], // We can safely set this, as it marks the version, we controll with exact THIS-FILEs code.
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

/**
 * Remove the plugins admin-menu.
 *
 * @return void
 */
function remove_menu() : void {
	remove_submenu_page( 'options-general.php', 'heartbeat_control_settings' );
}

/**
 * Remove CMB2 JS files from /wp-admin
 *
 * @return void
 */
function remove_scripts() : void {
	// Might be a little too global but,
	// this saves: 30kb on each /wp-admin request.
	remove_action( 'admin_footer', [ 'CMB2_JS', 'enqueue' ], 8 );
}
