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

	}

}
