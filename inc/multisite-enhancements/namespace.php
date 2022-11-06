<?php
/**
 * Figuren_Theater Admin_UI Multisite_Enhancements.
 *
 * @package figuren-theater/admin_ui/multisite_enhancements
 */

namespace Figuren_Theater\Admin_UI\Multisite_Enhancements;

use Figuren_Theater\Options;

use function add_action;

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
const BASENAME = 'multisite-enhancements/multisite-enhancements.php';

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
	
}

function load_plugin() {

	require_once FT_VENDOR_DIR . '/' . BASENAME;
}


function filter_options() {
		
		$_options = [
			'remove-logo'         => 1,
			// this saves (Websites*2)-DB requests per Admin-Bar-ified page-load
			// so: 20 Websites * 2 = 40 DB requests saved
			'add-favicon'         => 0,
			'add-blog-id'         => 1,
			'add-css'             => 1,
			'add-plugin-list'     => 1,
			'add-theme-list'      => 1,
			'add-site-status'     => 1,
			'add-ssl-identifier'  => 1,
			'add-manage-comments' => 1,
			'add-new-plugin'      => 1,
			'filtering-themes'    => 1,
			'change-footer'       => 1,
			'delete-settings'     => 1,
		];

		// gets added to the 'OptionsCollection' 
		// from within itself on creation
		new Options\Option(
			'wpme_options',
			$_options,
			BASENAME,
			'site_option'
		);
	}
