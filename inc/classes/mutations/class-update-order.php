<?php
/**
 * Update_Order class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Mutations;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Update_Order
 */
class Update_Order {

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

		// Register update order Mutation Types.
		add_action( 'graphql_register_types', [ $this, 'update_order_mutation' ] );

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
	public function update_order_mutation() {
		register_graphql_mutation( 'updateTheOrder', [
			'inputFields' => [
				'orderId'       => [
					'type'        => 'String',
					'description' => __( 'Order id', 'headless-cms' ),
				],
				'status'        => [
					'type'        => 'OrderStatusEnum',
					'description' => __( 'New order Status ', 'headless-cms' ),
				],
				'transactionId' => [
					'type'        => 'String',
					'description' => __( 'New transaction id', 'headless-cms' ),
				],
			],

			'outputFields' => [
				'orderStatusUpdated' => [
					'type'        => 'Integer',
					'description' => __( 'Has order status updated', 'headless-cms' ),
				],
				'orderId'            => [
					'type'        => 'String',
					'description' => __( 'Order Id in question', 'headless-cms' ),
				],
				'orderStatus'        => [
					'type'        => 'String',
					'description' => __( 'Updated Order Status', 'headless-cms' ),
				],
				'customerId'         => [
					'type'        => 'Integer',
					'description' => __( 'Customer Id', 'headless-cms' ),
				],
				'transactionId'      => [
					'type'        => 'String',
					'description' => __( 'Transaction id', 'headless-cms' ),
				],
				'error'              => [
					'type'        => 'String',
					'description' => __( 'Description of the error', 'headless-cms' ),
				],
			],

			'mutateAndGetPayload' => function ( $input, $context, $info ) {

				$response = [
					'orderStatusUpdated' => false,
					'orderId'            => ! empty( $input['orderId'] ) ? intval( $input['orderId'] ) : 0,
					'orderStatus'        => '',
					'customerId'         => 0,
					'transactionId'      => '',
					'error'              => '',
				];

				if ( empty( $input['orderId'] ) ) {
					$response['error'] = __( 'Please enter a valid order id', 'headless-cms' );

					return $response;
				}

				$user_id = get_current_user_id();

				if ( ! $user_id ) {
					$response['error'] = __( 'Request is not authenticated', 'headless-cms' );

					return $response;
				}

				return $this->update_order_details( $input['orderId'], $input['status'], $input['transactionId'], $response );
			},
		] );
	}

	/**
	 * Update order details
	 *
	 * @param string $input_order_id       Input Order id.
	 * @param string $input_status         Input status. e.g. COMPLETE, PENDING etc (dropdown selection)..
	 * @param string $input_transaction_id Input transaction id.
	 * @param array  $response             Response.
	 *
	 * @return array Response.
	 *
	 * @throws \WC_Data_Exception
	 */
	public function update_order_details( string $input_order_id, string $input_status, string $input_transaction_id, array $response ) {

		$order                          = \WC_Order_Factory::get_order( $input_order_id );
		$response['orderStatusUpdated'] = ! empty( $input_status ) && $order->update_status( $input_status );
		$response['orderStatus']        = $order->get_status();
		$response['customerId']         = $order->get_customer_id();

		if ( ! empty( $input_transaction_id ) ) {
			$order->set_transaction_id( $input_transaction_id );
		}

		$response['transactionId'] = $order->get_transaction_id();

		return $response;
	}
}
