<?php
/**
 * Plugin Name: Headless CMS
 * Description: A WordPress plugin that adds features to use WordPress as a headless CMS with any front-end environment using REST API
 * Plugin URI:  https://codeytek.com/headless-cms-wordpress-plugin
 * Author:      Imran Sayed
 * Author URI:  https://codeytek.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     2.0.4
 * Text Domain: headless-cms
 *
 * @package headless-cms
 */

define( 'HEADLESS_CMS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'HEADLESS_CMS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'HEADLESS_CMS_BUILD_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/build' );
define( 'HEADLESS_CMS_BUILD_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build' );
define( 'HEADLESS_CMS_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );

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

add_action( 'rest_api_init', function () {

    register_rest_route( 'headless/v1', '/secure-posts', array(
        'methods'  => 'GET',
        'callback' => 'headless_get_secure_posts',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
    ));

});

function headless_get_secure_posts() {
    return get_posts( array(
        'post_type'   => 'post',
        'numberposts' => 5,
    ));
}
