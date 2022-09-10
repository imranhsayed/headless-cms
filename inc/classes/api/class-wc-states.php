<?php
/**
 * Wc_States class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;


use Headless_CMS\Features\Inc\Traits\Singleton;
use \WP_REST_Server;

/**
 * Class Wc_States
 */
class Wc_States {

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
	protected $rest_base = 'wc/states';

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

		// e.g. http://example.com/wp-json/rae/v1/wc/states?countryCode=IN
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_states' ],
					'permission_callback' => '__return_true',
				],
			]
		);
	}

	/**
	 * Get States by
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function get_states( \WP_REST_Request $request ): \WP_REST_Response {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return rest_ensure_response( [] );
		}

		$parameters  = $request->get_params();
		$countryCode = ! empty( $parameters['countryCode'] ) ? sanitize_text_field( $parameters['countryCode'] ) : '';

		$states = ! empty( $countryCode ) ? WC()->countries->get_states( strtoupper( $countryCode ) ) : [];
		$states = $this->get_formatted_states( $states );

		/**
		 * Here you need to return data that matches the shape of the "WooStates" type. You could get
		 * the data from the WP Database, an external API, or static values.
		 * For example in this case we are getting it from WordPress database.
		 */
		$data = [
			'states' => $states,
		];

		return rest_ensure_response( $data );
	}

	/**
	 * Get Formatted States.
	 *
	 * @param array $states
	 *
	 * @return array
	 */
	public function get_formatted_states( array $states = [] ): array {

		$formatted_states = [];

		if ( empty( $states ) && ! is_array( $states ) ) {
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
