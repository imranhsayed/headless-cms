<?php
/**
 * Plugin manifest class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Api\API_Settings;
use Headless_CMS\Features\Inc\Api\Get_Post;
use Headless_CMS\Features\Inc\Api\Get_Posts;
use Headless_CMS\Features\Inc\Api\Header_Footer_Api;
use Headless_CMS\Features\Inc\Api\Home_Page;
use Headless_CMS\Features\Inc\Api\Wc_Cart;
use Headless_CMS\Features\Inc\Api\Post_By_Tax;
use Headless_CMS\Features\Inc\Mutations\Add_Wishlist;
use Headless_CMS\Features\Inc\Mutations\Delete_Wishlist;
use Headless_CMS\Features\Inc\Queries\Get_Wishlist;
use Headless_CMS\Features\Inc\Queries\Header_Footer_Schema;
use Headless_CMS\Features\Inc\Queries\Post_Schema;
use Headless_CMS\Features\Inc\Queries\Product;
use Headless_CMS\Features\Inc\Queries\Register_Countries;
use Headless_CMS\Features\Inc\Queries\Register_Shipping;
use Headless_CMS\Features\Inc\Queries\Register_States;
use Headless_CMS\Features\Inc\Queries\Seo;
use Headless_CMS\Features\Inc\Queries\Sticky_Post;
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
		API_Settings::get_instance();
		Get_Post::get_instance();
		Get_Posts::get_instance();
		Header_Footer_Api::get_instance();
		Home_Page::get_instance();
		Post_By_Tax::get_instance();
		Wc_Cart::get_instance();

		// Queries.
		Header_Footer_Schema::get_instance();
		Sticky_Post::get_instance();
		Post_Schema::get_instance();
		Seo::get_instance();
		Register_Countries::get_instance();
		Register_States::get_instance();
		Register_Shipping::get_instance();
		Get_Wishlist::get_instance();
		Product::get_instance();

		// Mutations
		Add_Wishlist::get_instance();
		Delete_Wishlist::get_instance();

		// Preview.
		Preview::get_instance();

	}

}
