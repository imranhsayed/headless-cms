<?php
/**
 * Product class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Product
 */
class Product {

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
		// Register Product Fields.
		add_action( 'graphql_register_types', [ $this, 'register_product_fields' ] );
	}

	/**
	 * Register field.
	 */
	function register_product_fields() {
		if ( !class_exists('WooCommerce') ) {
			return;
		}
		register_graphql_field(
			'Product',
			'productCurrency',
			[
				'description' => __( 'Product Currency', 'headless-cms' ),
				'type'        => 'String',
				'resolve'     => function () {
					return get_woocommerce_currency();
				},
			]
		);
	}
}
