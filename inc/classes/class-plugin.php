<?php
/**
 * Plugin manifest class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

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

		// Load plugin classes.
		Assets::get_instance();
		Customizer::get_instance();
		Get_Post_Api::get_instance();
		Get_Posts_Api::get_instance();
		Header_Footer_Api::get_instance();
		Home_Page_Api::get_instance();
		Post_By_Tax_Api::get_instance();
		Category::get_instance();
		Settings::get_instance();
		Header_Footer_Schema::get_instance();
		Sticky_Post_Type::get_instance();

	}

}
