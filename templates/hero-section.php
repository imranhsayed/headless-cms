<?php
/**
 * Hero Section Settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$hero_title         = ! empty( $option_val_array['hero_title'] ) ? $option_val_array['hero_title'] : '';
$hero_description   = ! empty( $option_val_array['hero_description'] ) ? $option_val_array['hero_description'] : '';
$hero_btn_text      = ! empty( $option_val_array['hero_btn_text'] ) ? $option_val_array['hero_btn_text'] : '';
$hero_back_img      = ! empty( $option_val_array['hero_back_img'] ) ? $option_val_array['hero_back_img'] : '';
$hero_btn_name      = empty( $hero_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );
$hero_section_class = ! empty( $hero_back_img ) ? 'uploaded' : '';
?>

<hr>
<!--Hero Section-->
<div id="hcms-hero-img-section" class="hcms-hero-img-section <?php echo esc_attr( $hero_section_class ); ?>">

	<h2><?php esc_html_e( 'Hero Section', 'headless-cms' ); ?></h2>

	<!--Hero Title-->
	<label for="hcms-hero-title-input"><?php esc_attr_e( 'Hero title', 'headless-cms' ); ?></label>
	<input id="hcms-hero-title-input" class="hcms-hero-title-input" type="text" name="hcms_plugin_options[hero_title]" value="<?php echo esc_attr( $hero_title ); ?>" />

	<!--Hero Subtitle-->
	<label for="hcms-hero-description-input"><?php esc_attr_e( 'Hero description', 'headless-cms' ); ?></label>
	<input id="hcms-hero-description-input" class="hcms-hero_description-input" type="text" name="hcms_plugin_options[hero_description]" value="<?php echo esc_attr( $hero_description ); ?>" />

	<!--Hero Button Text-->
	<label for="hcms-hero-btn-text-input"><?php esc_attr_e( 'Hero btn text', 'headless-cms' ); ?></label>
	<input id="hcms-hero-btn-text-input" class="hcms-hero-btn-text-input" type="text" name="hcms_plugin_options[hero_btn_text]" value="<?php echo esc_attr( $hero_btn_text ); ?>" />

	<!--Hero Image-->
	<h4><?php esc_html_e( 'Hero Background image', 'headless-cms' ); ?></h4>
	<img class="hcms-hero-img" src="<?php echo esc_url( $hero_back_img ); ?>" alt="left site logo" width="150">
	<input class="hcms-hero-input" type="hidden" name="hcms_plugin_options[hero_back_img]" value="<?php echo esc_url( $hero_back_img ); ?>" />
	<input class="hcms-hero-upload-btn hcms-left" data-section-id="#hcms-hero-img-section" type="button" value="<?php echo esc_attr( $hero_btn_name ); ?>" />
	<input class="hcms-hero-remove-btn" data-section-id="#hcms-hero-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
</div>

<br>
<hr>
