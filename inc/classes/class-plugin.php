<?php
/**
 * Plugin manifest class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Api\Get_Post;
use Headless_CMS\Features\Inc\Api\Get_Posts;
use Headless_CMS\Features\Inc\Api\Header_Footer_Api;
use Headless_CMS\Features\Inc\Api\Home_Page;
use Headless_CMS\Features\Inc\Api\Post_By_Tax;
use Headless_CMS\Features\Inc\Schema\Header_Footer_Schema;
use Headless_CMS\Features\Inc\Schema\Post_Schema;
use Headless_CMS\Features\Inc\Schema\Sticky_Post;
use \Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// Load plugin general classes.
		Assets::get_instance();
		Customizer::get_instance();
		Category::get_instance();
		Settings::get_instance();

		// Apis.
		Get_Post::get_instance();
		Get_Posts::get_instance();
		Header_Footer_Api::get_instance();
		Home_Page::get_instance();
		Post_By_Tax::get_instance();

		// Schemas.
		Header_Footer_Schema::get_instance();
		Sticky_Post::get_instance();
		Post_Schema::get_instance();

	}

}
