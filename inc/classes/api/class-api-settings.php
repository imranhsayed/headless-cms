<?php
/**
 * API Settings.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Header_Footer_Api
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

		add_filter( 'rest_url', [ $this, 'force_update_rest_url' ] );
		add_action( 'determine_current_user', [ $this, 'update_site_and_home_url' ], 1, 1 );
		add_action( 'determine_current_user', [ $this, 'reset_site_and_home_url' ], 20, 1 );
	}

	function update_site_and_home_url( $user_id ) {

		add_filter( 'home_url', [ $this, "update_urls" ], 1, 2 );
		add_filter( 'site_url', [ $this, "update_urls" ], 1, 2 );

		return $user_id;
	}

	function update_urls( $url, $path ) {
		$url = 'http://localhost:8888';

		return $url . $path;
	}

	function reset_site_and_home_url( $user_id ) {

		remove_filter( 'home_url', [ $this, "update_urls" ], 1 );
		remove_filter( 'site_url', [ $this, "update_urls" ], 1 );

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
