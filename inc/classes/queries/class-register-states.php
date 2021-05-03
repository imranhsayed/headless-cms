<?php
/**
 * Register_States class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

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

		register_graphql_object_type( 'WooState', [
			'fields' => [
				'stateCode' => [ 'type' => 'String' ],
				'stateName' => [ 'type' => 'String' ],
			],
		] );

		register_graphql_object_type( 'WooStates', [
			'description' => __( 'States Type', 'headless-cms' ),
			'fields' => [
				'states'   => [
					'type' => [
						'list_of' => 'WooState'
					]
				],
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

					if ( ! class_exists( 'WooCommerce' ) ) {
						return $states;
					}

					$states = isset( $args['countryCode'] ) && ! empty( $args['countryCode'] ) ? WC()->countries->get_states( strtoupper($args['countryCode']) ) : [];
					$states = $this->get_formatted_states( $states );

					/**
					 * Here you need to return data that matches the shape of the "WooStates" type. You could get
					 * the data from the WP Database, an external API, or static values.
					 * For example in this case we are getting it from WordPress database.
					 */
					return [
						'states' => $states,
					];

				},
			]
		);
	}

	public function get_formatted_states( $states ) {

		$formatted_states = [];

		if ( empty( $states ) && !is_array( $states ) ) {
			return $formatted_states;
		}

		foreach ( $states as $stateCode => $stateName ) {
			array_push( $formatted_states, [
				'stateCode' => $stateCode,
				'stateName' => $stateName,
			] );
		}

		return $formatted_states;
	}

}
