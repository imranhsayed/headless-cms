<?php
/**
 * Header_Footer_Api class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Api;

use Headless_CMS\Features\Inc\Traits\Singleton;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Header_Footer_Api
 */
class Header_Footer_Api {

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

		$this->route = '/header-footer';

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
		 * This api gets the header and footer of the site.
		 * The data will include:
		 * 1. header data ( siteTitle, siteDescription, siteLogoUrl, favicon, headerMenuItems( Header menu with the given menu location id ) )
		 * 2. footer data ( copyrightText, sidebarOne( widget ), sidebarTwo ( Widget ), socialLinks, footerMenuItems( Footer menu with the given menu location id ) )
		 *
		 * The 'header_location_id' here is a string e.g. 'primary' or whatever 'header_location_id' name you have used at the time of registration of the menu.
		 *
		 * Example: http://example.com/wp-json/rae/v1/header-footer?header_location_id=primary&footer_location_id=secondary
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
		$response                = [];
		$parameters              = $request->get_params();
		$header_menu_location_id = ! empty( $parameters['header_location_id'] ) ? sanitize_text_field( $parameters['header_location_id'] ) : '';
		$footer_menu_location_id = ! empty( $parameters['footer_location_id'] ) ? sanitize_text_field( $parameters['footer_location_id'] ) : '';

		// Error Handling.
		$error = new WP_Error();

		$header_menu_items = $this->get_nav_menu_items( $header_menu_location_id );
		$footer_menu_items = $this->get_nav_menu_items( $footer_menu_location_id );

		// If any menus found.
		if ( ! empty( $header_menu_items ) || ! empty( $footer_menu_items ) ) {

			$response['status'] = 200;
			$response['data']   = [
				'header' => [
					'siteLogoUrl'     => $this->get_custom_logo_url( 'custom_logo' ),
					'siteTitle'       => get_bloginfo( 'title' ),
					'siteDescription' => get_bloginfo( 'description' ),
					'favicon'         => get_site_icon_url(),
					'headerMenuItems' => $header_menu_items,
				],
				'footer' => [
					'footerMenuItems' => $footer_menu_items,
					'socialLinks'     => $this->get_social_icons(),
					'copyrightText'   => $this->get_copyright_text(),
					'sidebarOne'      => $this->get_sidebar( 'hcms-footer-sidebar-1' ),
					'sidebarTwo'      => $this->get_sidebar( 'hcms-footer-sidebar-2' ),
				],
			];

		} else {

			// If the posts not found.
			$error->add( 406, __( 'Data not found', 'rest-api-endpoints' ) );

			return $error;

		}

		return new WP_REST_Response( $response );

	}

	/**
	 * Get Custom logo URL.
	 *
	 * @param string $key Key.
	 *
	 * @return string Image.
	 */
	public function get_custom_logo_url( $key ) {

		$custom_logo_id = get_theme_mod( $key );
		$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		return $image[0];
	}

	/**
	 * Get social icons
	 *
	 * @return array $social_icons
	 */
	public function get_social_icons() {

		$social_icons      = [];
		$social_icons_name = [ 'facebook', 'twitter', 'instagram', 'youtube' ];

		foreach ( $social_icons_name as $social_icon_name ) {

			$social_link = get_theme_mod( sprintf( 'rae_%s_link', $social_icon_name ) );

			if ( $social_link ) {
				array_push(
					$social_icons,
					[
						'iconName' => esc_attr( $social_icon_name ),
						'iconUrl'  => esc_url( $social_link ),
					]
				);
			}
		}

		return $social_icons;

	}

	/**
	 * Get copyright text
	 *
	 * @return mixed
	 */
	public function get_copyright_text() {
		return get_theme_mod( 'rae_footer_text' );
	}

	/**
	 * Get nav menu items by location.
	 *
	 * @param string $location The menu location id.
	 * @param array  $args Arguments.
	 *
	 * @return array $menu_data Menu items array of Objects.
	 */
	public function get_nav_menu_items( $location, $args = [] ) {

		if ( empty( $location ) ) {
			return '';
		}

		// Get all locations.
		$locations = get_nav_menu_locations();

		// Get object id by location.
		$object = wp_get_nav_menu_object( $locations[ $location ] );

		// Get menu items by menu name.
		$menu_data  = wp_get_nav_menu_items( $object->name, $args );
		$menu_items = [];

		if ( ! empty( $menu_data ) ) {

			// Menus ( Loop through the menu, and push all the parent menu items first ).
			foreach ( $menu_data as $item ) {
				if ( empty( $item->menu_item_parent ) ) {
					$menu_item             = [];
					$menu_item['ID']       = $item->ID;
					$menu_item['title']    = $item->title;
					$menu_item['url']      = $item->url;
					$menu_item['children'] = [];

					// We are also getting the page slug and the page id that this menu is linked to.
					$menu_item['pageSlug'] = get_post_field( 'post_name', $item->object_id );
					$menu_item['pageID']   = intval( $item->object_id );

					array_push( $menu_items, $menu_item );
				}
			}

			// Submenus: ( Loop through the menu again, and push all the child menu items ).
			foreach ( $menu_data as $item ) {

				// If the menu has a parent, it means its a child menu.
				if ( $item->menu_item_parent ) {

					// Create a child menu array.
					$submenu_item          = [];
					$submenu_item['ID']    = $item->ID;
					$submenu_item['title'] = $item->title;
					$submenu_item['url']   = $item->url;

					// We are also getting the page slug and the page id that this menu is linked to.
					$submenu_item['pageSlug'] = get_post_field( 'post_name', $item->object_id );
					$submenu_item['pageID']   = intval( $item->object_id );

					// Loop through the menu items and find the parent whose child this is.
					foreach ( $menu_items as $key => $parent_item ) {

						// if the parent id of this child menu, is same as the parent menu id.
						if ( intval( $item->menu_item_parent ) === $parent_item['ID'] ) {

							// push the child menu into its parent menu children property.
							array_push( $menu_items[ $key ]['children'], $submenu_item );

						}
					}
				}
			}
		}

		$menu_items = ! empty( $menu_items ) ? $menu_items : '';

		// Return menu post objects.
		return $menu_items;

	}

	/**
	 * Returns the content of all the sidebars with given sidebar id.
	 *
	 * @param string $sidebar_id Sidebar id.
	 *
	 * @return false|string
	 */
	public function get_sidebar( $sidebar_id ) {
		ob_start();

		dynamic_sidebar( $sidebar_id );
		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	}

}
