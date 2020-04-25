<?php
/**
 * Home_Page_Api class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Traits\Singleton;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Home_Page_Api
 */
class Home_Page_Api {

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

		$this->route = '/home';

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
		 * 1. Site Logo
		 * 2. Header menu with the given menu location id
		 * 3. Footer menu with the given menu location id
		 *
		 * The 'post_type' here is a string e.g. 'post', The 'taxonomy' here is a string e.g. 'category'
		 *
		 * Example: http://example.com/wp-json/rae/v1/home?post_type=post
		 */
		register_rest_route(
			'rae/v1',
			$this->route,
			[
				'method'   => 'GET',
				'callback' => [ $this, 'rest_endpoint_handler' ],
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
		$post_type   = ! empty( $parameters['post_type'] ) ? sanitize_text_field( $parameters['post_type'] ) : 'post';
		$taxonomy   = ! empty( $parameters['taxonomy'] ) ? sanitize_text_field( $parameters['taxonomy'] ) : 'category';

		// Error Handling.
		$error = new WP_Error();

		$hero_section_data = $this->get_hero_section();
		$search_section_data = $this->get_search_section( $taxonomy );
		$featured_posts = $this->get_featured_posts(  );

		// If any menus found.
		if ( ! empty( $header_menu_items ) || ! empty( $footer_menu_items ) ) {

			$response['status']    = 200;
			$response['data'] = [
				'header' => [
					'siteLogoUrl' => $this->get_custom_logo_url( 'custom_logo' ),
					'siteTitle' => get_bloginfo( 'title' ),
					'siteDescription' => get_bloginfo( 'description' ),
					'favicon' => get_site_icon_url(),
					'headerMenuItems' => $header_menu_items,
				],
				'footer' => [
					'footerMenuItems' => $footer_menu_items,
					'socialLinks' => $this->get_social_icons(),
					'copyrightText' => $this->get_copyright_text(),
					'sidebarOne' => $this->get_sidebar( 'hcms-footer-sidebar-1' ),
					'sidebarTwo' => $this->get_sidebar( 'hcms-footer-sidebar-2' ),
				]
			];

		} else {

			// If the posts not found.
			$error->add( 406, __( 'Data not found', 'rest-api-endpoints' ) );

			return $error;

		}

		return new WP_REST_Response( $response );

	}

	/**
	 * Get Hero Section data.
	 *
	 * @return array $hero_section_data Hero Section data
	 */
	public function get_hero_section() {

		if ( empty( $this->plugin_options ) ) {
			return [];
		}

		$hero_section_data = [
			'heroTitle' => $this->plugin_options['hero_title'],
			'heroSubTitle' => $this->plugin_options['hero_subtitle'],
			'heroBtnTxt' => $this->plugin_options['hero_btn_text'],
			'heroBackURL' => $this->plugin_options['hero_back_img'],
		];

		return $hero_section_data;
	}

	/**
	 * Get Search Section data.
	 *
	 * @param string $taxonomy Taxonomy
	 *
	 * @return array $search_section_data Hero Section data
	 */
	public function get_search_section( $taxonomy ) {

		if ( empty( $this->plugin_options ) ) {
			return [];
		}

		// Get latest three categories.
		$terms = get_terms( [
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'number' => 3,
		] );

		$search_section_data = [
			'searchPlaceholderTxt' => $this->plugin_options['search_placeholder_text'],
			'searchBackURL' => $this->plugin_options['search_back_img'],
			'terms' => $terms,
		];

		return $search_section_data;
	}

	public function get_featured_posts() {

	}

}
