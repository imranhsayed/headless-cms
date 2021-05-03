<?php
/**
 * Register_Shipping class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Register_Shipping
 */
class Register_Shipping {

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

		// Register Shipping Zones fields.
		add_action( 'graphql_register_types', [ $this, 'register_shipping_zones_fields' ] );

	}

	/**
	 * Register field.
	 */
	function register_shipping_zones_fields() {

		register_graphql_object_type( 'ShippingInfo', [
			'description' => __( 'Shipping Zones Type', 'headless-cms' ),
			'fields' => [
				'shippingZones'  => [ 'type' => 'String' ],
				'storePostCode' => [ 'type' => 'Integer' ],
			]
		] );

		register_graphql_field(
			'RootQuery',
			'shippingInfo',
			[
				'description' => __( 'Shipping Zones', 'headless-cms' ),
				'type'        => 'ShippingInfo',
				'resolve'     => function () {

					$zone_locations = $this->get_zone_locations();
					$store_post_code = class_exists('WooCommerce') ? WC()->countries->get_base_postcode() : 0;

					/**
					 * Here you need to return data that matches the shape of the "ShippingInfo" type. You could get
					 * the data from the WP Database, an external API, or static values.
					 * For example in this case we are getting it from WordPress database.
					 */
					return [
						'shippingZones' => wp_json_encode($zone_locations),
						'storePostCode' => intval( $store_post_code ),
					];

				},
			]
		);
	}

	public function get_store_address() {
		$store_address = '';
		if( class_exists( 'WC_Countries' ) ) {
			$store_address = get_option( 'woocommerce_store_address' );
		}

		return $store_address;
	}

	/**
	 * Get Zone locations
	 *
	 * @return array $zone_locations Zone locations.
	 */
	public function get_zone_locations() {
		$zone_locations = [];

		if( class_exists( 'WC_Shipping_Zones' ) ) {
			$all_zones = \WC_Shipping_Zones::get_zones();
			if ( ! empty( $all_zones ) && is_array( $all_zones ) ) {
				foreach ((array) $all_zones as $key => $the_zone ) {

					$zone_info = [
						'zone_name'           => $the_zone['zone_name'],
						'country_names'       => $the_zone['formatted_zone_location'],
						'zone_location_codes' => $the_zone['zone_locations'],
						'shipping_methods'    => $this->get_shipping_methods( $the_zone['shipping_methods'] ),
					];
					array_push($zone_locations, $zone_info);

				}
			}
		}

		return $zone_locations;
	}

	/**
	 * Get Shipping methods.
	 *
	 * @param array $shipping_methods_data Shipping method data.
	 *
	 * @return array Shipping methods.
	 */
	public function get_shipping_methods($shipping_methods_data) {

		$shipping_methods = [];

		if ( empty( $shipping_methods_data ) || !is_array( $shipping_methods_data ) ) {
			return $shipping_methods;
		}

		foreach ((array) $shipping_methods_data as $key => $shipping_method ) {
			array_push($shipping_methods, [
				'method_title' => ! empty($shipping_method->instance_settings['title']) ? $shipping_method->instance_settings['title'] : '',
			]);
		}

		return $shipping_methods;
	}

}
