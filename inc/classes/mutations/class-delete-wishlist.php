<?php
/**
 * Delete_Wishlist class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Mutations;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Delete_Wishlist
 */
class Delete_Wishlist {

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
		add_action( 'graphql_register_types', [ $this, 'delete_wishlist_mutation' ] );

	}

	/**
	 *
	 * inputFields: expects an array of Fields to be used for inputting values to the mutation
	 *
	 * outputFields: expects an array of fields that can be asked for in response to the mutation
	 * the resolve function is optional, but can be useful if the mutateAndPayload doesn't return an array
	 * with the same key(s) as the outputFields
	 *
	 * mutateAndGetPayload: expects a function, and the function gets passed the $input, $context, and $info
	 * the function should return enough info for the outputFields to resolve with
	 *
	 * @throws \Exception
	 */
	public function delete_wishlist_mutation() {
		register_graphql_mutation( 'removeFromWishlist', [
			'inputFields' => [
				'productId' => [
					'type'        => 'Integer',
					'description' => __( 'Product id', 'headless-cms' ),
				],
				'userId'    => [
					'type'        => 'Integer',
					'description' => __( 'User id', 'headless-cms' ),
				],
			],

			'outputFields' => [
				'removed'   => [
					'type'        => 'Boolean',
					'description' => __( 'True if the product is removed, false otherwise', 'headless-cms' ),
				],
				'productId' => [
					'type'        => 'Integer',
					'description' => __( 'The Product id that was deleted', 'headless-cms' ),
				],
				'error'     => [
					'type'        => 'String',
					'description' => __( 'Description of the error', 'headless-cms' ),
				],
			],

			'mutateAndGetPayload' => function ( $input, $context, $info ) {

				$response = [
					'removed'   => false,
					'productId' => ! empty( $input['productId'] ) ? intval($input['productId']) : 0,
					'error'     => '',
				];

				if ( empty( $input['productId'] ) || empty( $input['userId'] ) ) {
					$response['error'] = __( 'Please enter both product id and user id', 'headless-cms' );

					return $response;
				}

				return $this->remove_item( $input['productId'], $input['userId'], $response );
			},
		] );
	}

	/**
	 * Remove item from wishlist
	 *
	 * @since 1.0.0
	 *
	 */
	public function remove_item( $product_id, $user_id, $response ) {

		$current_user_id = get_current_user_id();

		if ( $user_id !== $current_user_id ) {
			$response['error'] = __( 'User id is not valid', 'headless-cms' );
			return $response;
		}

		$saved_products = (array) get_user_meta( $user_id, 'wc_next_saved_products', true );
		$key            = array_search( $product_id, $saved_products );

		if ( ! $key ) {
			$response['error'] = __( 'Something went wrong', 'headless-cms' );

			return $response;
		}

		unset( $saved_products[ $key ] );
		$remove_product_from_wishlist = update_user_meta( $user_id, 'wc_next_saved_products', $saved_products );

		if ( is_wp_error( $remove_product_from_wishlist ) ) {
			$response['error'] = 'Something went wrong';

			return $response;
		} else {
			$response['removed']   = true;
			$response['productId'] = intval( $product_id );

			return $response;
		}

	}
}
