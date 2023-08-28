<?php
/**
 * Plugin Name:     figuren.theater | Admin UI
 * Plugin URI:      https://github.com/figuren-theater/ft-admin-ui
 * Description:     Clean and helpful UI to make your digital content editing with websites.fuer.figuren.theater a friendly experience.
 * Author:          figuren.theater
 * Author URI:      https://figuren.theater
 * Text Domain:     figurentheater
 * Domain Path:     /languages
 * Version:         1.1.1
 *
 * @package         figuren-theater\ft-admin-ui
 */

namespace Figuren_Theater\Admin_UI;

const DIRECTORY = __DIR__;

add_action( 'altis.modules.init', __NAMESPACE__ . '\\register' );
