<?php

namespace Headless_CMS\Features\Inc\Mutations;

use Headless_CMS\Features\Inc\Traits\Singleton;

class Add_Wishlist {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		add_action( 'graphql_register_types', [ $this, 'add_wishlist_mutation' ] );
	}

	public function add_wishlist_mutation() {
		if ( !class_exists( 'WooCommerce' ) ) {
			return;
		}
		register_graphql_mutation(
			'addToWishlist',
			[
				'inputFields' => [
					'productId' => [
						'type' => 'Integer',
						'description' => __( 'Product id', 'headless-cms' )
					]
				],
				'outputFields' => [
					'added' => [
						'type' => 'Boolean',
						'description' => __( 'True if the product is added, false otherwise', 'headless-cms' )
					],
					'productId' => [
						'type' => 'Integer',
						'description' => __( 'Product id that in question', 'headless-cms' )
					],
					'wishlistProductIds' => [
						'type' => [ 'list_of' => 'String' ],
						'description' => __( 'The product ids in the wishlist', 'headless-cms' )
					],
					'error' => [
						'type' => 'String',
						'description' => __( 'Description of the error', 'headless-cms' )
					]
				],
				'mutateAndGetPayload' => function( $input, $context, $info ) {
					$response = [
						'added' => false,
						'productId' => ! empty( $input['productId'] ) ? $input['productId'] : 0,
						'wishlistProductIds' => [],
						'error' => ''
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

				}
			]
		);
	}

	public function save_products_to_wishlist( int $product_id, int $user_id, array $response ) {
		// Check if the product id is valid
		$product = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			$response['error'] = __( 'Product does not exit', 'headless-cms' );
			return $response;
		}

		// Get saved products of the current user.
		$saved_products = (array) get_user_meta( $user_id, 'wishlist_saved_products', true );
		$response['wishlistProductIds'] = array_filter($saved_products);

		// Check if the product already exists.
		if ( in_array( $product_id, $saved_products, true ) ) {
			if ( array_search( $product_id, $saved_products, true ) ) {
				$response['error'] = __( 'Product already exists', 'headless-cms' );
				return $response;
			}
		} else {
			$saved_products[] = $product_id;
		}

		// Save product to current user.
		$save_product_to_user = update_user_meta( $user_id, 'wishlist_saved_products', $saved_products );

		if ( ! $save_product_to_user ) {
			$response['error'] = __( 'Something went wrong in adding the product to the wishlist', 'headless-cms' );
			return $response;
		}

		$response['added'] = true;
		$response['productId'] = $product_id;
		$response['wishlistProductIds'] = array_filter($saved_products);

		return $response;
	}
}
