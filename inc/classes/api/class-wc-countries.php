<?php
/**
 * Wc_Countries class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;


use Headless_CMS\Features\Inc\Traits\Singleton;
use \WP_REST_Server;

/**
 * Class Wc_Countries
 */
class Wc_Countries {

	use Singleton;

	/**
	 * Endpoint namespace
	 *
	 * @var string
	 */
	protected $namespace = 'rae/v1';

	/**
	 * Route name
	 *
	 * @var string
	 */
	protected $rest_base = 'wc/countries';

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
		add_action( 'rest_api_init', [ $this, 'register_rest_api_endpoints' ] );

	}

	/**
	 * Register endpoints.
	 */
	public function register_rest_api_endpoints() {

		// e.g. http://example.com/wp-json/rae/v1/wc/countries
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_countries' ],
					'permission_callback' => '__return_true',
				],
			]
		);
	}

	/**
	 * Get countries
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 *
	 * @since 1.0.0
	 *
	 */
	public function get_countries( \WP_REST_Request $request ): \WP_REST_Response {

		// All countries for billing.
		$all_countries     = class_exists( 'WooCommerce' ) ? WC()->countries : [];
		$billing_countries = ! empty( $all_countries->countries ) ? $all_countries->countries : [];
		$billing_countries = $this->get_formatted_countries( $billing_countries );

		// All countries with states for shipping.
		$shipping_countries = class_exists( 'WooCommerce' ) ? WC()->countries->get_shipping_countries() : [];;
		$shipping_countries = ! empty( $shipping_countries ) ? $shipping_countries : [];
		$shipping_countries = $this->get_formatted_countries( $shipping_countries );

		/**
		 * Here you need to return data that matches the shape of the "WooCountries" type. You could get
		 * the data from the WP Database, an external API, or static values.
		 * For example in this case we are getting it from WordPress database.
		 */
		$data = [
			'billingCountries'  => $billing_countries,
			'shippingCountries' => $shipping_countries,
		];

		return rest_ensure_response( $data );
	}

	/**
	 * Get Formatted Countries.
	 *
	 * @param $countries
	 *
	 * @return array
	 */
	public function get_formatted_countries( $countries ) {

		$formatted_countries = [];

		if ( empty( $countries ) && ! is_array( $countries ) ) {
			return $formatted_countries;
		}

		foreach ( $countries as $countryCode => $countryName ) {
			array_push( $formatted_countries, [
				'countryCode' => $countryCode,
				'countryName' => $countryName,
			] );
		}

		return $formatted_countries;
	}
}
