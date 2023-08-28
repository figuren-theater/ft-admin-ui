<?php
/**
 * Figuren_Theater Admin_UI Emoji_Toolbar.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Emoji_Toolbar;

use Figuren_Theater;

use FT_VENDOR_DIR;
use function add_action;

use function is_admin;
use function is_network_admin;
use function is_user_admin;

const BASENAME   = 'emoji-toolbar/emoji-toolbar.php';
const PLUGINPATH = '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() {

	add_action( 'init', __NAMESPACE__ . '\\load_plugin', 9 );
}
/**
 * Conditionally load the plugin itself and its modifications.
 *
 * @return void
 */
function load_plugin() {

	if ( ! is_admin() || is_network_admin() || is_user_admin() ) {
		return;
	}

	$config = Figuren_Theater\get_config()['modules']['admin_ui'];
	if ( ! $config['emoji-toolbar'] ) {
		return;
	}

	require_once FT_VENDOR_DIR . PLUGINPATH; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
}
