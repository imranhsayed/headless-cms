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
	$located_template = '';

	if ( file_exists( HEADLESS_CMS_PATH . '/' . $template ) ) {
		$located_template = HEADLESS_CMS_PATH . '/' . $template;
	} else {
		$located_template = locate_template( $template, false, false );
	}

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
function hcms_custom_new_menu() {
	register_nav_menus(
		[
			'hcms-menu-header' => esc_html__( 'HCMS Header Menu', 'headless-cms' ),
			'hcms-menu-footer' => esc_html__( 'HCMS Footer Menu', 'headless-cms' ),
		]
	);
}
add_action( 'init', 'hcms_custom_new_menu' );

/**
 * Register Sidebar
 */

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hcms_sidebar_registration() {

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
				'name'        => __( 'HCMS Footer #1', 'headless-cms' ),
				'id'          => 'hcms-footer-sidebar-1',
				'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'headless-cms' ),
			]
		)
	);

	// Footer #2.
	register_sidebar(
		array_merge(
			$shared_args,
			[
				'name'        => __( 'HCMS Footer #2', 'headless-cms' ),
				'id'          => 'hcms-footer-sidebar-2',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'headless-cms' ),
			]
		)
	);

}

add_action( 'widgets_init', 'hcms_sidebar_registration' );

/**
 * Add theme supports
 */
function hcms_theme_support() {

	if ( function_exists( 'add_theme_support' ) ) {
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size.
		set_post_thumbnail_size( 1200, 9999 );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );
	}

}

add_action( 'after_setup_theme', 'hcms_theme_support' );
