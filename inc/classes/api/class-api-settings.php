<?php
/**
 * API Settings.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class API_Settings
 */
class API_Settings {

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

		// If the site url is same as home url, then don't make any updates.
		if ( get_site_url() === get_home_url() ) {
			return null;
		}

		/**
		 * Update site and home URLs for rest api call.
		 */
		add_filter( 'rest_url', [ $this, 'force_update_rest_url' ] );

		/**
		 * Update site and home URLs for rest api call for WooCommerce.
		 *
		 * @see https://github.com/woocommerce/woocommerce/blob/0c44ab857a9b041727ab8c16fd182ee9b700818e/includes/class-wc-rest-authentication.php#L41
		 */
		add_action( 'determine_current_user', [ $this, 'update_site_and_home_url' ], 1, 1 );

		/**
		 * Reset the site and home URLs after authentication rest api call for WooCommerce.
		 */
		add_action( 'determine_current_user', [ $this, 'reset_site_and_home_url' ], 20, 1 );

	}

	/**
	 * Update the home and site url for the REST api authentication call.
	 *
	 * This is required because check_oauth_signature() of WooCommerce uses get_home_url to verify the signature.
	 * At that point, the correct site url is not available. So we modify the URL to the backend url and then
	 * later reset it back after the function completes it's work.
	 *
	 * @see https://github.com/woocommerce/woocommerce/blob/0c44ab857a9b041727ab8c16fd182ee9b700818e/includes/class-wc-rest-authentication.php#L390
	 *
	 * @param int $user_id User ID.
	 *
	 * @return mixed
	 */
	function update_site_and_home_url( $user_id ) {

		add_filter( 'home_url', [ $this, 'update_urls_callback' ], 1, 2 );
		add_filter( 'site_url', [ $this, 'update_urls_callback' ], 1, 2 );

		return $user_id;
	}

	/**
	 * Update URL Callback.
	 *
	 * @param $url
	 * @param $path
	 *
	 * @return string
	 */
	public function update_urls_callback( $url, $path ) {

		$url = get_option('siteurl');

		return $url . $path;
	}

	/**
	 * Reset the site and home URLs after authentication rest api call.
	 *
	 * @param int $user_id User id
	 */
	public function reset_site_and_home_url( $user_id ) {

		remove_filter( 'home_url', [ $this, 'update_urls_callback' ], 1 );
		remove_filter( 'site_url', [ $this, 'update_urls_callback' ], 1 );

		return $user_id;
	}

	/**
	 * Customize rest base url.
	 * When we set site address url to frontend url, by default wp rest endpoint gets that frontend url as base point.
	 * So we must update that to actual backend url.
	 *
	 * @param string $url url of the backend.
	 *
	 * @return string backend url.
	 */
	public function force_update_rest_url( $url ) {
		return str_replace( get_home_url(), get_site_url(), $url );
	}

}
