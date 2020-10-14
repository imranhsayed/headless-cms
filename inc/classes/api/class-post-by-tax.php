<?php
/**
 * Post_By_Tax class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;

use Headless_CMS\Features\Inc\Traits\Singleton;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Post_By_Tax
 */
class Post_By_Tax {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		$this->plugin_options = get_option( 'hcms_plugin_options' );
		$this->setup_hooks();

	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		$this->route = '/posts-by-tax';

		/**
		 * Action
		 */
		add_action( 'rest_api_init', [ $this, 'rest_posts_endpoints' ] );

	}

	/**
	 * Register posts endpoints.
	 */
	public function rest_posts_endpoints() {

		/**
		 * Handle Posts Request: GET Request
		 *
		 * This api gets the custom home page data for the site.
		 * The data will include:
		 * Latest posts ( Latest posts, with given post type passed in query params of URL, defaults to 'post', and given taxonomy )
		 *
		 * The 'post_type' here is a string e.g. 'post', The 'taxonomy' here is a string e.g. 'category'
		 *
		 * Example: http://example.com/wp-json/rae/v1/posts-by-tax?post_type=post&taxonomy=category&slug=xyz
		 */
		register_rest_route(
			'rae/v1',
			$this->route,
			[
				'method'   => 'GET',
				'callback' => [ $this, 'rest_endpoint_handler' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Get posts call back.
	 *
	 * Returns the menu items array of object on success
	 *
	 * @param WP_REST_Request $request request object.
	 *
	 * @return WP_Error|WP_REST_Response response object.
	 */
	public function rest_endpoint_handler( WP_REST_Request $request ) {

		$response   = [];
		$parameters = $request->get_params();
		$post_type  = ! empty( $parameters['post_type'] ) ? sanitize_text_field( $parameters['post_type'] ) : 'post';
		$taxonomy   = ! empty( $parameters['taxonomy'] ) ? sanitize_text_field( $parameters['taxonomy'] ) : 'category';
		$slug       = ! empty( $parameters['slug'] ) ? sanitize_text_field( $parameters['slug'] ) : '';

		// Error Handling.
		$error = new WP_Error();

		$latest_posts = $this->get_latest_posts( $post_type, $taxonomy, $slug );

		// If any menus found.
		if ( ! empty( $hero_section_data ) || ! empty( $search_section_data ) || ! empty( $featured_posts ) || ! empty( $latest_posts ) ) {

			$response['status'] = 200;
			$response['data']   = [
				'posts' => $latest_posts,
			];

		} else {

			// If the posts not found.
			$error->add( 406, __( 'Data not found', 'rest-api-endpoints' ) );

			return $error;

		}

		return new WP_REST_Response( $response );

	}

	/**
	 * Get latest posts
	 *
	 * @param string $post_type Post Type.
	 * @param string $taxonomy Taxnomy.
	 * @param string $slug Slug.
	 *
	 * @return array latest posts
	 */
	public function get_latest_posts( $post_type, $taxonomy, $slug ) {

		// Ignoring phps for taxonomy query as its required here.
		$args = [
			'post_type'              => $post_type,
			'post_status'            => 'publish',
			'posts_per_page'         => 10, // Get three posts.
			'fields'                 => 'ids',
			'orderby'                => 'date',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => [ // phpcs:ignore WordPress.DB.SlowDBQuery
				[
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $slug,
				],
			],

		];

		$result = new WP_Query( $args );

		$latest_post_ids = $result->get_posts();

		$latest_posts = [];

		if ( ! empty( $latest_post_ids ) && is_array( $latest_post_ids ) ) {
			foreach ( $latest_post_ids as $post_ID ) {

				$author_id     = get_post_field( 'post_author', $post_ID );
				$attachment_id = get_post_thumbnail_id( $post_ID );

				$post_data                     = [];
				$post_data['id']               = $post_ID;
				$post_data['title']            = get_the_title( $post_ID );
				$post_data['excerpt']          = get_the_excerpt( $post_ID );
				$post_data['slug']             = get_post_field( 'post_name', $post_ID );
				$post_data['content']          = get_the_content( null, false, $post_ID );
				$post_data['date']             = get_the_date( '', $post_ID );
				$post_data['attachment_image'] = [
					'img_sizes'  => wp_get_attachment_image_sizes( $attachment_id ),
					'img_src'    => wp_get_attachment_image_src( $attachment_id, 'full' ),
					'img_srcset' => wp_get_attachment_image_srcset( $attachment_id ),
				];
				$post_data['meta']             = [
					'author_id'   => $author_id,
					'author_name' => get_the_author_meta( 'display_name', $author_id ),
				];

				array_push( $latest_posts, $post_data );

			}
		}

		return $latest_posts;

	}

}
