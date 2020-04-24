<?php
/**
 * Plugin Name: Headless CMS
 * Description: A WordPress plugin that adds features to use WordPress as a headless CMS with any front-end environment using REST API
 * Plugin URI:  https://codeytek.com
 * Author:      Imran Sayed
 * Author URI:  https://codeytek.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: headless-cms
 *
 * @package headless-cms
 */

define( 'HEADLESS_CMS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'HEADLESS_CMS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once HEADLESS_CMS_PATH . '/inc/helpers/autoloader.php';
require_once HEADLESS_CMS_PATH . '/inc/helpers/custom-functions.php';
// phpcs:enable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function headless_cms_features_plugin_loader() {
	\Headless_CMS\Features\Inc\Plugin::get_instance();
}

headless_cms_features_plugin_loader();
