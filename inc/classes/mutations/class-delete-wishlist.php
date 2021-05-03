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

		// Register Delete wishlist Mutation Types.
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
				'wishlistProductIds' => [
					'type'        => [ 'list_of' => 'Integer' ],
					'description' => __( 'The Product ids in the wishlist', 'headless-cms' ),
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

				if ( empty( $input['productId'] ) ) {
					$response['error'] = __( 'Please enter a valid product id', 'headless-cms' );

					return $response;
				}

				$user_id = get_current_user_id();

				if ( ! $user_id ) {
					$response['error'] = __( 'Request is not authenticated', 'headless-cms' );

					return $response;
				}

				return $this->remove_item( $input['productId'], $user_id, $response );
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

		$saved_products = (array) get_user_meta( $user_id, 'wishlist_saved_products', true );
		$key            = array_search( $product_id, $saved_products );

		if ( ! $key ) {
			$response['error'] = __( 'Product does not exist in the wishlist', 'headless-cms' );

			return $response;
		}

		unset( $saved_products[ $key ] );
		$removed_product_from_wishlist = update_user_meta( $user_id, 'wishlist_saved_products', $saved_products );

		if ( !$removed_product_from_wishlist ) {
			$response['error'] = __( 'Something went wrong in removing the product', 'headless-cms' );

			return $response;
		} else {
			$response['removed']   = true;
			$response['productId'] = intval( $product_id );
			$response['wishlistProductIds'] = array_filter( $saved_products );

			return $response;
		}

	}
}
