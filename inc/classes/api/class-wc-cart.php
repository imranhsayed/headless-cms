<?php
/**
 * Wc_Cart class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;

use Headless_CMS\Features\Inc\Traits\Singleton;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Header_Footer_Api
 */
class Wc_Cart {

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
	protected $rest_base = 'cart/items';

	/**
	 * Construct method.
	 */
	protected function __construct() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

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
		add_action( 'rest_api_init', [ $this, 'rest_posts_endpoints' ] );
		add_filter( 'rest_pre_dispatch', [ $this, 'check_rest_response' ], 10, 3 );

	}

	/**
	 * Dispath rest response
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function check_rest_response( $response, $object, $request ) {
		if ( $this->wc_next_validate_boolean( $request->get_header( 'X-Headless-CMS' ) ) ) {

			// WC session cookie setter and getter.
			$cookie = apply_filters( 'woocommerce_cookie', 'wp_woocommerce_session_' . COOKIEHASH );

			if ( $request->get_header( 'X-WC-Session' ) ) {
				$_COOKIE[ $cookie ] = urldecode( $request->get_header( 'X-WC-Session' ) );
			} else {
				do_action( 'woocommerce_set_cart_cookies', true );
			}

			$this->wc_load_cart();
		}

		return $response;
	}

	/**
	 * Validate a boolean variable
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $var
	 *
	 * @return bool
	 */
	public function wc_next_validate_boolean( $var ) {
		return filter_var( $var, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Register posts endpoints.
	 */
	public function rest_posts_endpoints() {
		$item_schema = $this->get_default_item_schema();

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => '__return_true',
				),

				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => '__return_true',
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),

				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'clear_cart' ),
					'permission_callback' => '__return_true',
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/batch',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'batch_items' ),
					'permission_callback' => '__return_true',
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_batch_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<key>[\w-]+)',
			array(
				'args' => array(
					'key' => array(
						'description'       => __( 'The cart item key is what identifies the item in the cart.', 'wc-next-app' ),
						'type'              => 'string',
						'validate_callback' => array( $this, 'is_valid_cart_item' ),
					),
				),

				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => '__return_true',
					'args'                => array(
						'quantity' => $item_schema['properties']['quantity'],
					),
				),

				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => '__return_true',
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<key>[\w-]+)/restore',
			array(
				'args' => array(
					'key' => array(
						'description'       => __( 'The cart item key is what identifies the item in the cart.', 'wc-next-app' ),
						'type'              => 'string',
						'validate_callback' => array( $this, 'is_valid_removed_cart_item' ),
					),
				),

				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'restore_item' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	/**
	 * Get the Cart schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_default_item_schema() {
		// @todo: Add more properties matches the /cart GET endpoint
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'cart',
			'type'       => 'object',
			'properties' => array(
				'key' => array(
					'description' => __( 'Cart item key.', 'wc-next-app' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'product_id' => array(
					'description' => __( 'ID of the product to add to the cart.', 'wc-next-app' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'default'     => 0,
					'arg_options' => array(
						'validate_callback' => array( $this, 'is_valid_product' ),
					),
				),
				'quantity' => array(
					'description' => __( 'Quantity of the item to add.', 'wc-next-app' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'default'     => 1,
					'arg_options' => array(
						'validate_callback' => array( $this, 'is_valid_quantity' ),
					),
				),
				'variation_id' => array(
					'description' => __( 'ID of the variation being added to the cart.', 'wc-next-app' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'default'     => 0,
					'arg_options' => array(
						'validate_callback' => array( $this, 'is_valid_product' ),
					),
				),
				'variation' => array(
					'description' => __( 'Variation attribute values.', 'wc-next-app' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'default'     => array(),
				),
				'cart_item_data' => array(
					'description' => __( 'Extra cart item data we want to pass into the item.', 'wc-next-app' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'default'     => array(),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Retrieves the item's schema for display / public consumption purposes.
	 *
	 * @since 4.7.0
	 *
	 * @return array Public item schema data.
	 */
	public function get_public_item_schema() {

		$schema = $this->get_item_schema();

		if ( ! empty( $schema['properties'] ) ) {
			foreach ( $schema['properties'] as &$property ) {
				unset( $property['arg_options'] );
			}
		}

		return $schema;
	}

	/**
	 * Get cart items
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function get_items( $request ) {
		$data = array();
		include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
		include_once WC_ABSPATH . 'includes/class-wc-cart.php';

		if ( is_null( WC()->cart ) ) {
			wc_load_cart();
		}
		$cart = WC()->cart->get_cart();

		foreach ( $cart as $item_key => &$cart_item ) {
			$data[] = $this->prepare_cart_item_for_response( $cart_item, $request );
		}

		$response = rest_ensure_response( $data );
		$response = $this->add_headers( $response );

		return $response;
	}

	/**
	 * Retrieves an array of endpoint arguments from the item schema for the controller.
	 *
	 * @since 4.7.0
	 *
	 * @param string $method Optional. HTTP method of the request. The arguments for `CREATABLE` requests are
	 *                       checked for required values and may fall-back to a given default, this is not done
	 *                       on `EDITABLE` requests. Default WP_REST_Server::CREATABLE.
	 * @return array Endpoint arguments.
	 */
	public function get_endpoint_args_for_item_schema( $method = WP_REST_Server::CREATABLE ) {
		return rest_get_endpoint_args_for_schema( $this->get_item_schema(), $method );
	}

	/**
	 * Retrieves the item's schema, conforming to JSON Schema.
	 *
	 * @since 4.7.0
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		return $this->add_additional_fields_schema( array() );
	}

	/**
	 * Adds the schema from additional fields to a schema array.
	 *
	 * The type of object is inferred from the passed schema.
	 *
	 * @since 4.7.0
	 *
	 * @param array $schema Schema array.
	 * @return array Modified Schema array.
	 */
	protected function add_additional_fields_schema( $schema ) {
		if ( empty( $schema['title'] ) ) {
			return $schema;
		}

		// Can't use $this->get_object_type otherwise we cause an inf loop.
		$object_type = $schema['title'];

		$additional_fields = $this->get_additional_fields( $object_type );

		foreach ( $additional_fields as $field_name => $field_options ) {
			if ( ! $field_options['schema'] ) {
				continue;
			}

			$schema['properties'][ $field_name ] = $field_options['schema'];
		}

		return $schema;
	}

	/**
	 * Retrieves all of the registered additional fields for a given object-type.
	 *
	 * @since 4.7.0
	 *
	 * @param string $object_type Optional. The object type.
	 * @return array Registered additional fields (if any), empty array if none or if the object type could
	 *               not be inferred.
	 */
	protected function get_additional_fields( $object_type = null ) {

		if ( ! $object_type ) {
			$object_type = $this->get_object_type();
		}

		if ( ! $object_type ) {
			return array();
		}

		global $wp_rest_additional_fields;

		if ( ! $wp_rest_additional_fields || ! isset( $wp_rest_additional_fields[ $object_type ] ) ) {
			return array();
		}

		return $wp_rest_additional_fields[ $object_type ];
	}

	/**
	 * Load WC Cart functionalities in REST environment
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wc_load_cart() {
		include_once WC_ABSPATH . 'includes/wc-notice-functions.php';

		if ( ! wc()->cart ) {
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			wc_load_cart();
			wc()->cart->get_cart();
		}
	}

	/**
	 * Add item to cart
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function create_item( $request ) {
		$product_id     = $request['product_id'];
		$quantity       = ! empty( $request['quantity'] ) ? $request['quantity'] : 1;
		$variation_id   = ! empty( $request['variation_id'] ) ? $request['variation_id'] : 0;

		$product = wc_get_product( $variation_id ? $variation_id : $product_id );

		if ( empty( $product ) ) {
			return new WP_Error( 'rest_invalid_product', __( 'Product not exists', 'wc-next-app' ) );
		}

		if ( $product instanceof WC_Product_Variation ) {
			$product_id = $product->get_parent_id();
			$variation  = $product->get_variation_attributes();
		}

		// Force quantity to 1 if sold individually and check for existing item in cart.
		if ( $product->is_sold_individually() ) {
			$quantity = 1;

			$cart_contents = wc()->cart->cart_contents;

			$found_in_cart = apply_filters( 'woocommerce_add_to_cart_sold_individually_found_in_cart', $cart_item_key && $cart_contents[ $cart_item_key ]['quantity'] > 0, $product_id, $variation_id, $cart_item_data, $cart_id );

			if ( $found_in_cart ) {
				/* translators: %s: product name */
				return new WP_Error( 'wc_next_rest_product_sold_individually', sprintf( __( 'You cannot add another "%s" to your cart.', 'wc-next-app' ), $product->get_name() ), array( 'status' => 500 ) );
			}
		}

		// Product is purchasable check.
		if ( ! $product->is_purchasable() ) {
			return new WP_Error( 'wc_next_rest_cannot_be_purchased', __( 'Sorry, this product cannot be purchased.', 'wc-next-app' ), array( 'status' => 500 ) );
		}

		// Stock check - only check if we're managing stock and backorders are not allowed.
		if ( ! $product->is_in_stock() ) {
			return new WP_Error( 'wc_next_rest_product_out_of_stock', sprintf( __( 'You cannot add &quot;%s&quot; to the cart because the product is out of stock.', 'wc-next-app' ), $product->get_name() ), array( 'status' => 500 ) );
		}
		if ( ! $product->has_enough_stock( $quantity ) ) {
			return new WP_Error( 'wc_next_rest_not_enough_in_stock', sprintf( __( 'You cannot add that amount of &quot;%1$s&quot; to the cart because there is not enough stock (%2$s remaining).', 'wc-next-app' ), $product->get_name(), wc_format_stock_quantity_for_display( $product->get_stock_quantity(), $product ) ), array( 'status' => 500 ) );
		}

		// Stock check - this time accounting for whats already in-cart.
		if ( $product->managing_stock() ) {
			$products_qty_in_cart = wc()->cart->get_cart_item_quantities();

			if ( isset( $products_qty_in_cart[ $product->get_stock_managed_by_id() ] ) && ! $product->has_enough_stock( $products_qty_in_cart[ $product->get_stock_managed_by_id() ] + $quantity ) ) {
				return new WP_Error(
					'wc_next_rest_not_enough_stock_remaining',
					sprintf(
						__( 'You cannot add that amount to the cart &mdash; we have %1$s in stock and you already have %2$s in your cart.', 'wc-next-app' ),
						wc_format_stock_quantity_for_display( $product->get_stock_quantity(), $product ),
						wc_format_stock_quantity_for_display( $products_qty_in_cart[ $product->get_stock_managed_by_id() ], $product )
					),
					array( 'status' => 500 )
				);
			}
		}

		// Add item to cart.
		$item_key = wc()->cart->add_to_cart( $product_id, $quantity, $variation_id );

		// Return response to added item to cart or return error.
		if ( $item_key ) {
			$cart_item = wc()->cart->get_cart_item( $item_key );

			do_action( 'wc_next_rest_add_to_cart', $item_key, $cart_item );

			if ( is_array( $cart_item ) ) {
				$data     = $this->prepare_cart_item_for_response( $cart_item, $request );
				$response = rest_ensure_response( $data );
				$response = $this->add_headers( $response );

				return $response;
			}
		}

		return new WP_Error( 'wc_next_rest_cannot_add_to_cart', sprintf( __( 'You cannot add "%s" to your cart.', 'wc-next-app' ), $product->get_name() ), array( 'status' => 500 ) );
	}

	/**
	 * Prepare cart item
	 *
	 * @since 1.0.0
	 *
	 * @param array            $cart_item
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	protected function prepare_cart_item_for_response( $cart_item, $request ) {
		$product = $cart_item['data'];

		$product_id = $product->get_id();

		if ( $product instanceof WC_Product_Variation ) {
			$product_id = $product->get_parent_id();
		}

		$cart_item['data']           = $product->get_data();
		$cart_item['data']['images'] = $this->get_images( $product );

		return $cart_item;
	}

	/**
	 * Get the images for a product or product variation.
	 *
	 * @since 1.0.0
	 *
	 * @param WC_Product|WC_Product_Variation $product Product instance.
	 *
	 * @return array
	 */
	protected function get_images( $product ) {
		$images         = array();
		$attachment_ids = array();

		// Add featured image.
		if ( $product->get_image_id() ) {
			$attachment_ids[] = $product->get_image_id();
		}

		// Add gallery images.
		$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		// Build image data.
		foreach ( $attachment_ids as $position => $attachment_id ) {
			$attachment_post = get_post( $attachment_id );
			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'id'   => (int) $attachment_id,
				'src'  => current( $attachment ),
				'name' => get_the_title( $attachment_id ),
				'alt'  => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);
		}

		// Set a placeholder image if the product has no images set.
		if ( empty( $images ) ) {
			$images[] = array(
				'id'   => 0,
				'src'  => wc_placeholder_img_src(),
				'name' => __( 'Placeholder', 'wc-next-app' ),
				'alt'  => __( 'Placeholder', 'wc-next-app' ),
			);
		}

		return $images;
	}

	/**
	 * Add response header
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Response $response
	 */
	protected function add_headers( $response ) {
		wc()->cart->calculate_totals();

		$response->header( 'X-WC-Cart-TotalItems', wc()->cart->get_cart_contents_count() );
		$response->header( 'X-WC-Cart-Totals', json_encode( wc()->cart->get_totals() ) );

		$wc_session_id = null;
		$headers       = headers_list();

		foreach( $headers as $header ) {
			if ( 0 === strpos( $header, 'Set-Cookie: wp_woocommerce_session_' ) ) {
				preg_match_all( '/Set-Cookie: wp_woocommerce_session_(.*?)=(.*?);/', $header, $matches );

				if ( ! empty( $matches[2][0] ) ) {
					$wc_session_id = $matches[2][0];
				}
			}
		}

		$response->header( 'X-WC-Session', $wc_session_id );

		return $response;
	}

	/**
	 * Validate product
	 *
	 * @since 1.0.0
	 *
	 * @param mixed            $value
	 * @param \WP_REST_Request $request
	 * @param string           $param
	 *
	 * @return bool
	 */
	public function is_valid_product( $value, $request, $param ) {
		if ( ! is_integer( $value ) ) {
			return new WP_Error( 'rest_invalid_product_id', sprintf( __( 'Invalid %s.', 'wc-next-app' ), $param ) );
		}

		if ( empty( absint( $request['product_id'] ) ) && empty( absint( $request['variation_id'] ) ) ) {
			return new WP_Error( 'rest_invalid_data', __( 'product_id or variation_id is required.', 'wc-next-app' ) );
		}

		$product_id = absint( $value );

		if ( $product_id <= 0 ) {
			return true;
		}

		$product = wc_get_product( $product_id );

		if ( $product instanceof WC_Product && 'trash' !== $product->get_status() ) {
			return true;
		}

		return new WP_Error( 'rest_invalid_product', sprintf( __( '%s does not exist.', 'wc-next-app' ), $param ) );
	}

	/**
	 * Validate product quantity
	 *
	 * @since 1.0.0
	 *
	 * @param mixed            $value
	 * @param \WP_REST_Request $request
	 * @param string           $param
	 *
	 * @return bool|\WP_Error
	 */
	public function is_valid_quantity( $value, $request, $param ) {
		if ( ! is_integer( $value ) ) {
			return new WP_Error( 'rest_invalid_quantity', __( 'quantity is not numeric.', 'wc-next-app' ) );
		}

		$value = absint( $value );

		if ( $value < 0 ) {
			return new WP_Error( 'rest_invalid_quantity', __( 'quntity must be equal or greater than 0.', 'wc-next-app' ) );
		}

		return true;
	}

	/**
	 * Validate a cart item
	 *
	 * @since 1.0.0
	 *
	 * @param string           $key
	 * @param \WP_REST_Request $request
	 * @param string           $param
	 *
	 * @return bool
	 */
	public function is_valid_cart_item( $key, $request, $param ) {
		if ( wc()->cart->is_empty() ) {
			return new WP_Error( 'wc_next_rest_empty_cart', __( "You don't have any item in your cart.", 'wc-next-app' ) );
		}

		if ( wc()->cart->get_cart_item( $key ) ) {
			return true;
		}

		return new WP_Error( 'wc_next_rest_invalid_cart_item_key', __( 'Invalid cart item key.', 'wc-next-app' ) );
	}

	/**
	 * Validate a trashed cart item
	 *
	 * @since 1.0.0
	 *
	 * @param string           $key
	 * @param \WP_REST_Request $request
	 * @param string           $param
	 *
	 * @return bool
	 */
	public function is_valid_removed_cart_item( $key, $request, $param ) {
		$removed_items = wc()->cart->get_removed_cart_contents();

		if ( isset( $removed_items[ $key ] ) ) {
			return true;
		}

		return new WP_Error( 'wc_next_rest_invalid_trashed_item_key', __( 'Cart item not found in removed items.', 'wc-next-app' ) );
	}

	/**
	 * Clear cart
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function clear_cart() {
		wc()->cart->empty_cart();
		wc()->session->set( 'cart', array() );

		if ( ! wc()->cart->is_empty() ) {
			return new WP_Error( 'wc_next_rest_clear_cart_failed', __( 'Clearing the cart failed!', 'wc-next-app' ), array( 'status' => 500 ) );
		} else {
			$response = rest_ensure_response( array() );
			$response = $this->add_headers( $response );

			return $response;
		}
	}

	/**
	 * Update a cart item
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_item( $request ) {
		$cart_item_key = $request['key'];
		$quantity      = $request['quantity'];

		$current_data = wc()->cart->get_cart_item( $cart_item_key );

		// Checks if the item has enough stock before updating
		$has_enough_stock = $this->has_enough_stock( $current_data, $quantity );

		if ( is_wp_error( $has_enough_stock ) ) {
			return $has_enough_stock;
		}

		if ( wc()->cart->set_quantity( $cart_item_key, $quantity ) ) {
			$new_data = wc()->cart->get_cart_item( $cart_item_key );

			$product_id   = ! isset( $new_data['product_id'] ) ? 0 : absint( $new_data['product_id'] );
			$variation_id = ! isset( $new_data['variation_id'] ) ? 0 : absint( $new_data['variation_id'] );

			$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );

			if ( isset( $new_data['quantity'] ) && $quantity != $new_data['quantity'] ) {
				do_action( 'wc_next_rest_item_quantity_changed', $cart_item_key, $new_data );
			}

			$data     = ! empty( $new_data ) ? $this->prepare_cart_item_for_response( $new_data, $request ) : array();
			$response = rest_ensure_response( $data );
			$response = $this->add_headers( $response );

			// Return response based on product quantity increment.
			if ( $quantity > $current_data['quantity'] ) {
				$status = 'increased';
			} else if ( $quantity < $current_data['quantity'] ) {
				$status = 'decreased';
			} else {
				$status = 'unchanged';
			}

			$quantity_status = json_encode( array(
				'status'            => $status,
				'previous_quantity' => $current_data['quantity'],
				'new_quantity'      => $quantity,
			) );

			$response->header( 'X-WC-Cart-ItemQuantity', $quantity_status );

			return $response;

		} else {
			return new WP_Error( 'wc_next_rest_can_not_update_item', __( 'Unable to update item quantity in cart.', 'wc-next-app' ), array( 'status' => 500 ) );
		}
	}

	/**
	 * Checks if the product in the cart has enough stock
	 * before updating the quantity.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $current_data
	 * @param  string $quantity
	 *
	 * @return bool|WP_Error
	 */
	protected function has_enough_stock( $current_data = array(), $quantity = 1 ) {
		$product_id      = ! isset( $current_data['product_id'] ) ? 0 : absint( $current_data['product_id'] );
		$variation_id    = ! isset( $current_data['variation_id'] ) ? 0 : absint( $current_data['variation_id'] );
		$current_product = wc_get_product( $variation_id ? $variation_id : $product_id );

		$quantity = absint( $quantity );

		if ( ! $current_product->has_enough_stock( $quantity ) ) {
			return new WP_Error(
				'wc_next_rest_not_enough_in_stock',
				sprintf(
					__( 'You cannot add that amount of &quot;%1$s&quot; to the cart because there is not enough stock (%2$s remaining).', 'wc-next-app' ),
					$current_product->get_name(),
					wc_format_stock_quantity_for_display( $current_product->get_stock_quantity(), $current_product )
				),
				array( 'status' => 500 )
			);
		}

		return true;
	}

	/**
	 * Delete/Remove a cart item
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function delete_item( $request ) {
		$cart_item = wc()->cart->get_cart_item( $request['key'] );

		if ( ! wc()->cart->remove_cart_item( $request['key'] ) ) {
			return new WP_Error( 'wc_cart_rest_can_not_remove_item', __( 'Unable to remove item from cart.', 'wc-next-app' ), array( 'status' => 500 ) );
		}

		$data     = $this->prepare_cart_item_for_response( $cart_item, $request );
		$response = rest_ensure_response( $data );
		$response = $this->add_headers( $response );

		return $response;
	}

	/**
	 * Restore a cart item
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function restore_item( $request ) {
		if ( ! wc()->cart->restore_cart_item( $request['key'] ) ) {
			return new WP_Error( 'wc_cart_rest_can_not_restore_item', __( 'Unable to restore cart item.', 'wc-next-app' ), array( 'status' => 500 ) );
		}

		$cart_item = wc()->cart->get_cart_item( $request['key'] );
		$data      = $this->prepare_cart_item_for_response( $cart_item, $request );
		$response  = rest_ensure_response( $data );
		$response  = $this->add_headers( $response );

		return $response;
	}

	/**
	 * Get normalized rest base.
	 *
	 * @return string
	 */
	protected function get_normalized_rest_base() {
		return preg_replace( '/\(.*\)\//i', '', $this->rest_base );
	}

	/**
	 * Check batch limit.
	 *
	 * ATTENTION: Intentionally keep original code from WooCommerce
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Request items.
	 *
	 * @return bool|WP_Error
	 */
	protected function check_batch_limit( $items ) {
		$limit = apply_filters( 'woocommerce_rest_batch_items_limit', 100, $this->get_normalized_rest_base() );
		$total = 0;

		if ( ! empty( $items['create'] ) ) {
			$total += count( $items['create'] );
		}

		if ( ! empty( $items['update'] ) ) {
			$total += count( $items['update'] );
		}

		if ( ! empty( $items['delete'] ) ) {
			$total += count( $items['delete'] );
		}

		if ( $total > $limit ) {
			/* translators: %s: items limit */
			return new WP_Error( 'woocommerce_rest_request_entity_too_large', sprintf( __( 'Unable to accept more than %s items for this request.', 'wc-next-app' ), $limit ), array( 'status' => 413 ) );
		}

		return true;
	}

	/**
	 * Bulk create, update and delete items.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array Of WP_Error or WP_REST_Response.
	 */
	public function batch_items( $request ) {
		/**
		 * REST Server
		 *
		 * @var WP_REST_Server $wp_rest_server
		 */
		global $wp_rest_server;

		// Get the request params.
		$items    = array_filter( $request->get_params() );
		$response = array();

		// Check batch limit.
		$limit = $this->check_batch_limit( $items );
		if ( is_wp_error( $limit ) ) {
			return $limit;
		}

		if ( ! empty( $items['create'] ) ) {
			foreach ( $items['create'] as $item ) {
				$_item = new WP_REST_Request( 'POST' );

				// Default parameters.
				$defaults = array();
				$schema   = $this->get_public_item_schema();
				foreach ( $schema['properties'] as $arg => $options ) {
					if ( isset( $options['default'] ) ) {
						$defaults[ $arg ] = $options['default'];
					}
				}
				$_item->set_default_params( $defaults );

				// Set request parameters.
				$_item->set_body_params( $item );
				$_response = $this->create_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['create'][] = array(
						'id'    => 0,
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['create'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		if ( ! empty( $items['update'] ) ) {
			foreach ( $items['update'] as $item ) {
				$_item = new WP_REST_Request( 'PUT' );
				$_item->set_body_params( $item );
				$_response = $this->update_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['update'][] = array(
						'id'    => $item['id'],
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['update'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		if ( ! empty( $items['delete'] ) ) {
			foreach ( $items['delete'] as $id ) {
				$id = (int) $id;

				if ( 0 === $id ) {
					continue;
				}

				$_item = new WP_REST_Request( 'DELETE' );
				$_item->set_query_params(
					array(
						'id'    => $id,
						'force' => true,
					)
				);
				$_response = $this->delete_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['delete'][] = array(
						'id'    => $id,
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$response['delete'][] = $wp_rest_server->response_to_data( $_response, '' );
				}
			}
		}

		return $response;
	}

}
