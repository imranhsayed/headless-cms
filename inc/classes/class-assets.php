<?php
/**
 * Assets class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Assets
 */
class Assets {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Action
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

	}

	/**
	 * To enqueue scripts and styles. in admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		if ( 'toplevel_page_hcms-settings-menu-page' === $hook_suffix ) {
			wp_register_script( 'hcms-plugins-settings-js', HEADLESS_CMS_BUILD_URI . '/js/settings.js', [ 'jquery' ], filemtime( HEADLESS_CMS_BUILD_DIR . '/js/settings.js' ), true );
			wp_register_style( 'hcms-plugins-settings-css', HEADLESS_CMS_BUILD_URI . '/css/settings.css', [], filemtime( HEADLESS_CMS_BUILD_DIR . '/css/settings.css' ), false );

			wp_enqueue_style( 'hcms-plugins-settings-css' );
			wp_enqueue_media();
			wp_enqueue_script( 'hcms-plugins-settings-js' );
			wp_enqueue_script( 'media-uploader' );
		}

		if ( 'term.php' === $hook_suffix ) {
			wp_register_script( 'hcms-plugins-category-js', HEADLESS_CMS_BUILD_URI . '/js/category.js', [ 'jquery' ], filemtime( HEADLESS_CMS_BUILD_DIR . '/js/category.js' ), true );
			wp_register_style( 'hcms-plugins-category-css', HEADLESS_CMS_BUILD_URI . '/css/category.css', [], filemtime( HEADLESS_CMS_BUILD_DIR . '/css/category.css' ), false );

			wp_enqueue_style( 'hcms-plugins-category-css' );
			wp_enqueue_media();
			wp_enqueue_script( 'hcms-plugins-category-js' );
			wp_enqueue_script( 'media-uploader' );
		}

	}

}
