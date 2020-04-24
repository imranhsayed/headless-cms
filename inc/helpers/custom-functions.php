<?php
/**
 * Headless_CMS features custom functions.
 *
 * @package headless-cms
 */

/**
 * An extension to get_template_part function to allow variables to be passed to the template.
 *
 * @param  string $slug file slug like you use in get_template_part without php extension.
 * @param  array  $variables pass an array of variables you want to use in array keys.
 *
 * @return void
 */
function headless_cms_get_template_part( $slug, $variables = [] ) {

	$template         = sprintf( '%s.php', $slug );
	$located_template = locate_template( $template, false, false );

	if ( '' === $located_template ) {
		return;
	}

	if ( ! empty( $variables ) && is_array( $variables ) ) {
		extract( $variables, EXTR_SKIP ); // phpcs:ignore -- Used as an exception as there is no better alternative.
	}

	include $located_template; // phpcs:ignore

}

/**
 * Register Menus.
 */
function hw_custom_new_menu() {
	register_nav_menus( [
		'rwt-menu-header' => esc_html__( 'RWT Header Menu', 'rest-api-endpoints' ),
		'rwt-menu-footer' => esc_html__( 'RWT Footer Menu', 'rest-api-endpoints' ),
	] );
}
add_action( 'init', 'hw_custom_new_menu' );

/**
 * Register Sidebar
 */

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hw_sidebar_registration() {

	// Arguments used in all register_sidebar() calls.
	$shared_args = [
		'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
		'after_title'   => '</h2>',
		'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></div>',
	];

	// Footer #1.
	register_sidebar(
		array_merge(
			$shared_args,
			[
				'name'        => __( 'RWT Footer #1', 'rest-api-endpoints' ),
				'id'          => 'rwt-footer-sidebar-1',
				'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'rest-api-endpoints' ),
			]
		)
	);

	// Footer #2.
	register_sidebar(
		array_merge(
			$shared_args,
			[
				'name'        => __( 'RWT Footer #2', 'rest-api-endpoints' ),
				'id'          => 'rwt-footer-sidebar-2',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'rest-api-endpoints' ),
			]
		)
	);

}

add_action( 'widgets_init', 'hw_sidebar_registration' );

/**
 * Register sidebar.
 *
 * This will register and show widgets menu in the Appearance menu.
 */
if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar();
}

