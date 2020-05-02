<?php
/**
 * Search Section Settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$search_placeholder_text = ! empty( $option_val_array['search_placeholder_text'] ) ? $option_val_array['search_placeholder_text'] : '';
$search_back_img         = ! empty( $option_val_array['search_back_img'] ) ? $option_val_array['search_back_img'] : '';
$search_section_class    = ! empty( $search_back_img ) ? 'uploaded' : '';
$search_section_btn_name = empty( $search_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );

?>

<!--Search Section Settings-->
<div id="hcms-srch-back-img-section" class="hcms-srch-back-img-section <?php echo esc_attr( $search_section_class ); ?>">

	<h2><?php esc_html_e( 'Search Section', 'headless-cms' ); ?></h2>

	<!--Search placeholder text-->
	<label for="hcms-search-placeholder-text"><?php esc_attr_e( 'Search placeholder text', 'headless-cms' ); ?></label>
	<input id="hcms-search-placeholder-text" class="hcms-search-placeholder-text" type="text" name="hcms_plugin_options[search_placeholder_text]" value="<?php echo esc_attr( $search_placeholder_text ); ?>" />

	<!--Search Background Image-->
	<h4><?php esc_html_e( 'Search background Image', 'headless-cms' ); ?></h4>
	<img class="hcms-hero-img" src="<?php echo esc_url( $search_back_img ); ?>" alt="right site logo" width="150">
	<input class="hcms-hero-input" type="hidden" name="hcms_plugin_options[search_back_img]" value="<?php echo esc_url( $search_back_img ); ?>" />
	<input class="hcms-hero-upload-btn hcms-right" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php echo esc_attr( $search_section_btn_name ); ?>" />
	<input class="hcms-hero-remove-btn" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
</div>

<br>
<hr>
