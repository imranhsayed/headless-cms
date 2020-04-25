<?php
/**
 * Customizer class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Customizer
 */
class Customizer {

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
		add_action( 'customize_register', [ $this, 'customize_register' ] );

	}

	/**
	 * Customize register.
	 *
	 * @param \WP_Customize_Manager $wp_customize Theme Customizer object.
	 *
	 * @action customize_register
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {

		$this->social_icon_section( $wp_customize );
		$this->footer_section( $wp_customize );

	}

	/**
	 * Add Footer section settings.
	 *
	 * @param object $wp_customize WP_Customize.
	 */
	public function footer_section( $wp_customize ) {

		$wp_customize->add_section(
			'rae_footer',
			[
				'title'       => esc_html__( 'Footer', 'rest-api-endpoints' ),
				'description' => esc_html__( 'Footer', 'rest-api-endpoints' ),
			]
		);

		$setting_id = 'rae_footer_text';

		$wp_customize->add_setting(
			$setting_id,
			[
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_control(
			$setting_id,
			[
				'label'    => esc_html__( 'Copyright text', 'rest-api-endpoints' ),
				'section'  => 'rae_footer',
				'settings' => $setting_id,
				'type'     => 'text',
			]
		);
	}

	/**
	 * Add social icon section.
	 *
	 * @param object $wp_customize WP_Customize.
	 */
	public function social_icon_section( $wp_customize ) {

		// Social Icons.
		$social_icons = [ 'facebook', 'twitter', 'instagram', 'youtube' ];

		$wp_customize->add_section(
			'rae_social_links',
			[
				'title'       => esc_html__( 'Social Links', 'rest-api-endpoints' ),
				'description' => esc_html__( 'Social links', 'rest-api-endpoints' ),
			]
		);

		foreach ( $social_icons as $social_icon ) {

			$setting_id = sprintf( 'rae_%s_link', $social_icon );

			$wp_customize->add_setting(
				$setting_id,
				[
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_url',
				]
			);

			$wp_customize->add_control(
				$setting_id,
				[
					'label'    => esc_html( $social_icon ),
					'section'  => 'rae_social_links',
					'settings' => $setting_id,
					'type'     => 'text',
				]
			);
		}
	}


}
