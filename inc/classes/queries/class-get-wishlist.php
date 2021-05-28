<?php
/**
 * Get_Wishlist class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Get_Wishlist
 */
class Get_Wishlist {

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

		// Register Wishlist Field.
		add_action( 'graphql_register_types', [ $this, 'register_get_wishlist_fields' ] );

	}

	/**
	 * Register field.
	 */
	function register_get_wishlist_fields() {

		if ( !class_exists('WooCommerce') ) {
			return;
		}

		register_graphql_object_type( 'WishlistProductImage', [
			'fields' => [
				'attachmentId' => [ 'type' => 'Integer' ],
				'src'          => [ 'type' => 'String' ],
				'alt'          => [ 'type' => 'String' ],
			],
		] );

		register_graphql_object_type( 'WishlistProduct', [
			'fields' => [
				'databaseId'    => [ 'type' => 'Integer' ],
				'name'          => [ 'type' => 'String' ],
				'slug'          => [ 'type' => 'String' ],
				'typename'    => [ 'type' => 'String' ],
				'priceHtml'     => [ 'type' => 'String' ],
				'image'         => [ 'type' => 'WishlistProductImage' ],
				'buttonText'    => [ 'type' => 'String' ],
				'productUrl'    => [ 'type' => 'String' ],
				'stockStatus'   => [ 'type' => 'String' ],
				'stockQuantity' => [ 'type' => 'Integer' ],
			],
		] );

		register_graphql_object_type( 'WishlistProducts', [
			'description' => __( 'States Type', 'headless-cms' ),
			'fields'      => [
				'productIds' => [
					'type' => [
						'list_of' => 'Integer',
					],
				],
				'products'   => [ 'type' => [ 'list_of' => 'WishlistProduct' ] ],
				'error'      => [ 'type' => 'String' ],
			],
		] );

		register_graphql_field(
			'RootQuery',
			'getWishList',
			[
				'description' => __( 'States', 'headless-cms' ),
				'type'        => 'WishlistProducts',
				'resolve'     => function ( $source, $args, $context, $info ) {
					$wishlist_products = [
						'productIds' => [],
						'products'   => [],
					];

					$user_id = get_current_user_id();

					if ( ! $user_id ) {
						$wishlist_products['error'] = __( 'Request is not authenticated', 'headless-cms' );

						return $wishlist_products;
					}

					$saved_product_ids  = (array) get_user_meta( $user_id, 'wishlist_saved_products', true );
					$wish_list_products = $this->prepare_wishlist_items_for_response( $saved_product_ids );

					/**
					 * Here you need to return data that matches the shape of the "WishlistProduct" type. You could get
					 * the data from the WP Database, an external API, or static values.
					 * For example in this case we are getting it from WordPress database.
					 */
					$wishlist_products['productIds'] = array_filter( $saved_product_ids );
					$wishlist_products['products']   = $wish_list_products;

					return $wishlist_products;

				},
			]
		);
	}

	/**
	 * Get the wishlist products with required data.
	 *
	 * @param array $product_ids Product Ids
	 *
	 * @return array $wishlist_products Wishlist products.
	 */
	public function prepare_wishlist_items_for_response( array $product_ids ) {

		$type_list = [
			'simple'   => 'SimpleProduct',
			'variable' => 'VariableProduct',
			'external' => 'ExternalProduct',
			'group'    => 'GroupProduct',
		];

		$wishlist_products = [];
		$args              = [
			'include' => $product_ids,
		];
		$products          = wc_get_products( $args );

		if ( empty( $products ) || ! is_array( $products ) ) {
			return $wishlist_products;
		}

		foreach ( $products as $product ) {
			$product_data = [];
			$data         = $product->get_data();
			$stock_status = ! empty( $data['stock_status'] ) ? $data['stock_status'] : '';
			$stock_status = 'instock' === $stock_status ? 'IN_STOCK' : $stock_status;
			$typename = $product->get_type();
			$typename = ! empty( $typename ) ? $type_list[$typename] : '';

			$product_data['databaseId']    = ! empty( $data['id'] ) ? $data['id'] : 0;
			$product_data['name']          = ! empty( $data['name'] ) ? $data['name'] : '';
			$product_data['slug']          = ! empty( $data['slug'] ) ? $data['slug'] : '';
			$product_data['typename']      = $typename;
			$product_data['priceHtml']     = $product->get_price_html();
			$product_data['image']         = $this->get_image( $product, $data['name'] );
			$product_data['buttonText']    = ! empty( $data['button_text'] ) ? $data['button_text'] : '';
			$product_data['productUrl']    = ! empty( $data['product_url'] ) ? $data['product_url'] : '';
			$product_data['stockStatus']   = $stock_status;
			$product_data['stockQuantity'] = intval( $data['stock_quantity'] );

			// Push each product into the wishlist products array.
			$wishlist_products[] = $product_data;
		}

		return $wishlist_products;
	}


	/**
	 * Get the featured image for a product
	 *
	 * @param object $product      Product
	 * @param string $product_name Product name
	 *
	 * @return array Featured image.
	 */
	protected function get_image( object $product, string $product_name ) {
		$attachment_id = $product->get_image_id() ? $product->get_image_id() : 0;

		// Set a placeholder image if the product has no images set.
		if ( empty( $attachment_id ) ) {
			return [
				'attachmentId' => 0,
				'src'          => wc_placeholder_img_src(),
				'alt'          => $product_name,
			];
		}

		$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
		$altText    = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

		return [
			'attachmentId' => (int) $attachment_id,
			'src'          => current( $attachment ),
			'alt'          => ! empty( $altText ) ? $altText : $product_name,
		];
	}

}
