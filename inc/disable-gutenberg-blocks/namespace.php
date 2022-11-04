<?php
/**
 * Figuren_Theater Admin_UI Heartbeat_Control.
 *
 * @package figuren-theater/admin_ui/heartbeat_control
 */

namespace Figuren_Theater\Admin_UI\Disable_Gutenberg_Blocks;

// use Altis;
use Figuren_Theater\Options;

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
const BASENAME = 'disable-gutenberg-blocks/class-disable-gutenberg-blocks.php';

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'Figuren_Theater\loaded', __NAMESPACE__ . '\\filter_options', 11 );
	
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
}

function load_plugin() {

	require_once WP_PLUGIN_DIR . '/' . BASENAME;
	
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 11 );

}



function filter_options() {
		
		$_options = \apply_filters( 'ft-disable-blocks', [
			'core/freeform',
			'core/html',
			'core/loginout',
			'core/text-columns',
			['core/embed'=>'animoto'],
			['core/embed'=>'cloudup'],
			['core/embed'=>'collegehumor'],
			['core/embed'=>'crowdsignal'],
			['core/embed'=>'dailymotion'],
			['core/embed'=>'imgur'],
			['core/embed'=>'reverbnation'],
			['core/embed'=>'smugmug'],
			['core/embed'=>'speaker-deck'],
			['core/embed'=>'videopress'],
			['core/embed'=>'wordpress-tv'],
			['core/embed'=>'amazon-kindle'],
		]);

		// gets added to the 'OptionsCollection' 
		// from within itself on creation
		new Options\Option(
			'dgb_disabled_blocks',
			$_options,
			BASENAME
		);
	}


function remove_menu() {
	remove_submenu_page( 'options-general.php', 'disable-blocks' );
}
