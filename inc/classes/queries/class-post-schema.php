<?php
/**
 * Register more GraphQL types for Post.
 *
 * e.g. coAuthors, bodyClasses
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

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
				'description' => __( 'Co Authors', 'headless-cms' ),
				'resolve'     => function ($post) {
					return function_exists( 'get_coauthors' ) ? wp_json_encode( get_coauthors( $post->ID ) ) : '';
				},
			]
		);

		// Register bodyClasses type for Posts.
		register_graphql_field(
			'Post',
			'bodyClasses',
			[
				'type'        => 'String',
				'description' => __( 'bodyClasses', 'headless-cms' ),
				'resolve'     => function ($post) {
					return $this->get_body_classes($post);
				},
			]
		);

		// Register bodyClasses type for Page.
		register_graphql_field(
			'Page',
			'bodyClasses',
			[
				'type'        => 'String',
				'description' => __( 'bodyClasses', 'headless-cms' ),
				'resolve'     => function ($post) {
					return $this->get_body_classes($post);
				},
			]
		);
	}

	/**
	 * Get body classes including elementor classes.
	 *
	 * @param Object $post Post.
	 *
	 * @return string Body classes.
	 */
	public function get_body_classes( $post ) {
		$body_classes = array_merge( [], get_body_class() );
		$body_classes = implode( ' ', $body_classes );
		$elementor_kit_post = get_page_by_title('Default Kit', OBJECT, 'elementor_library');
		$elementor_kit_post_id = ! empty( $elementor_kit_post ) ? $elementor_kit_post->ID : '';
		$body_classes = $body_classes . sprintf( ' elementor-default elementor-kit-%1$s elementor-page elementor-page-%2$s', $elementor_kit_post_id, $post->ID );
		return $body_classes;
	}

}

