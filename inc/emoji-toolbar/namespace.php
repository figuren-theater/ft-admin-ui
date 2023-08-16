<?php
/**
 * Figuren_Theater Admin_UI Emoji_Toolbar.
 *
 * @package figuren-theater/ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI\Emoji_Toolbar;

use FT_VENDOR_DIR;

use Figuren_Theater;
use function Figuren_Theater\get_config;

use function add_action;
use function is_admin;
use function is_network_admin;
use function is_user_admin;

const BASENAME   = 'emoji-toolbar/emoji-toolbar.php';
const PLUGINPATH = FT_VENDOR_DIR . '/wpackagist-plugin/' . BASENAME;

/**
 * Bootstrap module, when enabled.
 */
function bootstrap() {

	add_action( 'init', __NAMESPACE__ . '\\load_plugin', 9 );
}

function load_plugin() {

	if ( ! is_admin() || is_network_admin() || is_user_admin() )
		return;

	$config = Figuren_Theater\get_config()['modules']['admin_ui'];
	if ( ! $config['emoji-toolbar'] )
		return; // early

	require_once PLUGINPATH;
}
