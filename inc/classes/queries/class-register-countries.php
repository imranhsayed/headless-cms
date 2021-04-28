<?php
/**
 * Register_Countries class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Register_Countries
 */
class Register_Countries {

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

		// Register Countries Field.
		add_action( 'graphql_register_types', [ $this, 'register_countries_fields' ] );

	}

	/**
	 * Register field.
	 */
	public function register_countries_fields() {

		register_graphql_object_type( 'WooCountry', [
			'fields' => [
				'countryCode'  => [ 'type' => 'String' ],
				'countryName' => [ 'type' => 'String' ],
			]
		] );

		register_graphql_object_type( 'WooCountries', [
			'description' => __( 'Countries Type', 'headless-cms' ),
			'fields'      => [
				'billingCountries'   => [
					'type' => [
						'list_of' => 'WooCountry'
					]
				],
				'shippingCountries'   => [
					'type' => [
						'list_of' => 'WooCountry'
					]
				],
			],
		] );

		register_graphql_field(
			'RootQuery',
			'wooCountries',
			[
				'description' => __( 'Countries', 'headless-cms' ),
				'type'        => 'WooCountries',
				'resolve'     => function () {

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
					return [
						'billingCountries'  => $billing_countries,
						'shippingCountries' => $shipping_countries,
					];

				},
			]
		);
	}

	public function get_formatted_countries( $countries ) {

		$formatted_countries = [];

		if ( empty( $countries ) && !is_array( $countries ) ) {
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
