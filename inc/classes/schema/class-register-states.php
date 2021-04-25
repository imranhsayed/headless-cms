<?php
/**
 * Register_States class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Schema;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Register_States
 */
class Register_States {

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

		// Register States Field.
		add_action( 'graphql_register_types', [ $this, 'register_states_fields' ] );

	}

	/**
	 * Register field.
	 */
	function register_states_fields() {

		register_graphql_object_type( 'WooStates', [
			'description' => __( 'States Type', 'headless-cms' ),
			'fields' => [
				'states'  => [ 'type' => 'String' ],
			]
		] );

		register_graphql_field(
			'RootQuery',
			'wooStates',
			[
				'description' => __( 'States', 'headless-cms' ),
				'type'        => 'WooStates',
				'args'        => [
					'countryCode' => [
						'type' => 'String',
					],
				],
				'resolve'     => function ( $source, $args, $context, $info ) {
					$states = [];

					if ( isset( $args['countryCode'] ) && ! empty( $args['countryCode'] ) ) {
						$states = class_exists( 'WooCommerce' ) ? WC()->countries->get_states( strtoupper($args['countryCode']) ) : [];
					}

					/**
					 * Here you need to return data that matches the shape of the "Dog" type. You could get
					 * the data from the WP Database, an external API, or static values.
					 * For example in this case we are getting it from WordPress database.
					 */
					return [
						'states' => wp_json_encode($states),
					];

				},
			]
		);
	}

}
