<?php
/**
 * Register more GraphQL types for Post.
 *
 * e.g. coAuthors, bodyClasses
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Schema;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Post_Schema
 */
class Post_Schema {

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

		// coAuthors
		register_graphql_field(
			'Post',
			'coAuthors',
			[
				'type'        => 'String',
				'description' => __( 'Co Authors', 'wp-graphql' ),
				'resolve'     => function ($post) {
					return function_exists( 'get_coauthors' ) ? wp_json_encode( get_coauthors( $post->ID ) ) : '';
				},
			]
		);

		register_graphql_field(
			'Post',
			'bodyClasses',
			[
				'type'        => 'String',
				'description' => __( 'bodyClasses', 'headless-cms' ),
				'resolve'     => function ($post) {
					return wp_json_encode( get_body_class() );
				},
			]
		);
	}

}

