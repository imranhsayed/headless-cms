<?php
/**
 * Add_Wishlist class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Mutations;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Add_Wishlist
 */
class Add_Wishlist {

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

		// Register Wishlist Mutation Types.
		add_action( 'graphql_register_types', [ $this, 'add_wishlist_mutation' ] );

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
	/**
	 * Register field.
	 */
	public function add_wishlist_mutation() {
		register_graphql_mutation(
			'addToWishlist',
			[
				'inputFields'         => [
					'productId' => [
						'type'        => 'Integer',
						'description' => __( 'Product id', 'headless-cms' ),
					],
				],

				'outputFields'        => [
					'added'              => [
						'type'        => 'Boolean',
						'description' => __( 'True if the product is removed, false otherwise', 'headless-cms' ),
					],
					'productId'          => [
						'type'        => 'Integer',
						'description' => __( 'The Product id that was added', 'headless-cms' ),
					],
					'wishlistProductIds' => [
						'type'        => [ 'list_of' => 'Integer' ],
						'description' => __( 'The Product ids in the wishlist', 'headless-cms' ),
					],
					'error'              => [
						'type'        => 'String',
						'description' => __( 'Description of the error', 'headless-cms' ),
					],
				],

				'mutateAndGetPayload' => function ( $input, $context, $info ) {

					$response = [
						'added'              => false,
						'productId'          => ! empty( $input['productId'] ) ? $input['productId'] : 0,
						'wishlistProductIds' => [],
						'error'              => '',
					];

					$user_id = get_current_user_id();

					if ( ! $user_id ) {
						$response['error'] = __( 'Authentication failed', 'headless-cms' );

						return $response;
					}

					if ( empty( $input['productId'] ) ) {
						$response['error'] = __( 'Please enter a valid product id', 'headless-cms' );

						return $response;
					}

					return $this->save_products_to_wishlist( $input['productId'], $user_id, $response );
				},
			]
		);
	}

	/**
	 * Save products to wishlist
	 *
	 * @param int   $product_id Product id.
	 * @param int   $user_id User id.
	 * @param array $response Response.
	 *
	 * @return array $response Response.
	 */
	public function save_products_to_wishlist( int $product_id, int $user_id, array $response ) {

		// Check if the product id is valid else return error.
		$product = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			$response['error'] = __( 'Product does not exist', 'headless-cms' );
			return $response;
		}

		// Get saved products of current user.
		$saved_products = (array) get_user_meta( $user_id, 'wishlist_saved_products', true );
		$response['wishlistProductIds'] = array_filter( $saved_products );

		// Check if the product already exists.
		if ( in_array( $product_id, $saved_products, true ) ) {
			if ( array_search( $product_id, $saved_products, true ) ) {
				$response['error'] = __( 'Product already exist', 'headless-cms' );
				return $response;
			}
		} else {
			$saved_products[] = $product_id;
		}

		// Save product to current user.
		$save_product_to_user = update_user_meta( $user_id, 'wishlist_saved_products', $saved_products );

		if ( !$save_product_to_user ) {
			$response['error'] = __( 'Something went wrong in adding the product to wishlist', 'headless-cms' );

			return $response;
		}

		$response['added']              = true;
		$response['productId']          = $product_id;
		$response['wishlistProductIds'] = array_filter( $saved_products );
		return $response;
	}
}
