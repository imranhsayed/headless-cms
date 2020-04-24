
<?php
/**
 * Plugin Settings form template
 *
 * @package headless-cms
 */

?>

<div class="hcms-settings-wrapper">
	<!--Header-->
	<div class="hcms-settings-header">
		<h2><?php esc_html_e( 'HCMS Plugins Settings', 'headless-cms' ); ?></h2>
		<p><?php esc_html_e( 'Add the settings for the plugin', 'headless-cms' ); ?></p>
	</div>
	<!--Form-->
	<form method="post" id="hcms-settings-form" class="hcms-settings-form" action="options.php">
		<?php
		settings_fields( 'hcms-plugin-settings-group' );
		do_settings_sections( 'hcms-plugin-settings-group' );
		$option_val_array = get_option( 'hcms_app_options' );

		$hero_back_img  = ! empty( $option_val_array['hero_back_img'] ) ? $option_val_array['hero_back_img'] : '';
		$search_back_img = ! empty( $option_val_array['search_back_img'] ) ? $option_val_array['search_back_img'] : '';

		$left_section_class  = ! empty( $hero_back_img ) ? 'uploaded' : '';
		$right_section_class = ! empty( $search_back_img ) ? 'uploaded' : '';
		$left_btn_name       = empty( $hero_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );
		$right_btn_name      = empty( $search_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );

		?>
		<!--Left Image Section-->
		<div id="hcms-hero-img-section" class="hcms-hero-img-section <?php echo esc_attr( $left_section_class ); ?>">
			<h3><?php esc_html_e( 'Hero Background image', 'headless-cms' ); ?></h3>
			<img class="hcms-hero-img" src="<?php echo esc_url( $hero_back_img ); ?>" alt="left site logo" width="150">
			<input class="hcms-hero-input" type="hidden" name="hcms_app_options[hero_back_img]" value="<?php echo esc_url( $hero_back_img ); ?>" />
			<input class="hcms-hero-upload-btn hcms-left" data-section-id="#hcms-hero-img-section" type="button" value="<?php echo esc_attr( $left_btn_name ); ?>" />
			<input class="hcms-hero-remove-btn" data-section-id="#hcms-hero-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
		</div>

		<!--Right Image Section-->
		<div id="hcms-srch-back-img-section" class="hcms-srch-back-img-section <?php echo esc_attr( $right_section_class ); ?>">
			<h3><?php esc_html_e( 'Search background Image', 'headless-cms' ); ?></h3>
			<img class="hcms-hero-img" src="<?php echo esc_url( $search_back_img ); ?>" alt="right site logo" width="150">
			<input class="hcms-hero-input" type="hidden" name="hcms_app_options[search_back_img]" value="<?php echo esc_url( $search_back_img ); ?>" />
			<input class="hcms-hero-upload-btn hcms-right" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php echo esc_attr( $right_btn_name ); ?>" />
			<input class="hcms-hero-remove-btn" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
		</div>

		<!--Submit Button-->
		<div class="hcms-save-btn-container"><?php submit_button(); ?></div>
	</form>
</div>
