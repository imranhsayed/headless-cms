<?php
/**
 * Register_Countries class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Schema;

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
	function register_countries_fields() {

		register_graphql_object_type( 'WooCountries', [
			'description' => __( 'Countries Type', 'headless-cms' ),
			'fields' => [
				'countries'  => [ 'type' => 'String' ],
			]
		] );

		register_graphql_field(
			'RootQuery',
			'wooCountries',
			[
				'description' => __( 'Countries', 'headless-cms' ),
				'type'        => 'WooCountries',
				'resolve'     => function () {

					$countries = class_exists('WooCommerce') ? WC()->countries : [];

					/**
					 * Here you need to return data that matches the shape of the "WooCountries" type. You could get
					 * the data from the WP Database, an external API, or static values.
					 * For example in this case we are getting it from WordPress database.
					 */
					return [
						'countries' => wp_json_encode($countries),
					];

				},
			]
		);
	}

}
