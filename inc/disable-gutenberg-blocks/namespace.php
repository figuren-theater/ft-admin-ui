<?php
/**
 * Figuren_Theater Admin_UI Disable_Gutenberg_Blocks.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Disable_Gutenberg_Blocks;

use Figuren_Theater\Options;

use FT_VENDOR_DIR;

use function add_action;
use function apply_filters;
use function remove_submenu_page;

const BASENAME   = 'disable-gutenberg-blocks/class-disable-gutenberg-blocks.php';
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

	// the Plugin hooks itself on 50
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_menu', 50 +1 );
}


function filter_options() {

	$blocks_to_disable = [
		// 'core/freeform',
		// 'core/html',
		'core/loginout',
		'core/text-columns',
		[ 'core/embed' => 'animoto' ],
		[ 'core/embed' => 'cloudup' ],
		[ 'core/embed' => 'collegehumor' ],
		[ 'core/embed' => 'crowdsignal' ],
		[ 'core/embed' => 'dailymotion' ],
		[ 'core/embed' => 'imgur' ],
		[ 'core/embed' => 'reverbnation' ],
		[ 'core/embed' => 'smugmug' ],
		[ 'core/embed' => 'speaker-deck' ],
		[ 'core/embed' => 'videopress' ],
		[ 'core/embed' => 'wordpress-tv' ],
		[ 'core/embed' => 'amazon-kindle' ],
	];

	\apply_filters_deprecated(
		'ft-disable-blocks',
		[ $blocks_to_disable ],
		'2.11',
		__NAMESPACE__,
		'Take part in the future now: Replace your filter calls!'
	);


	$_options = apply_filters(
		__NAMESPACE__,
		$blocks_to_disable
	);

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
