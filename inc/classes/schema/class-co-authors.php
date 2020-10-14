<?php
/**
 * Register coAuthors GraphQL type.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Schema;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Co_Authors
 */
class Co_Authors {

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
		 * Actions
		 */
		add_action( 'graphql_register_types', [ $this, 'register_graphql_fields' ] );

	}

	/**
	 * Register field.
	 */
	public function register_graphql_fields() {

		// Sticky Post Ids
		register_graphql_field(
			'Post',
			'coAuthors',
			[
				'type'        => 'String',
				'description' => __( 'Co Authors', 'wp-graphql' ),
				'resolve'     => function ($post) {
					return wp_json_encode( get_coauthors( $post->ID ) );
				},
			]
		);
	}

}

