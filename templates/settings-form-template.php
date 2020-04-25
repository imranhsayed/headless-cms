
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
		$option_val_array = get_option( 'hcms_plugin_options' );

		$hero_title  = ! empty( $option_val_array['hero_title'] ) ? $option_val_array['hero_title'] : '';
		$hero_subtitle  = ! empty( $option_val_array['hero_subtitle'] ) ? $option_val_array['hero_subtitle'] : '';
		$hero_btn_text  = ! empty( $option_val_array['hero_btn_text'] ) ? $option_val_array['hero_btn_text'] : '';
		$hero_back_img  = ! empty( $option_val_array['hero_back_img'] ) ? $option_val_array['hero_back_img'] : '';

		$search_placeholder_text  = ! empty( $option_val_array['search_placeholder_text'] ) ? $option_val_array['search_placeholder_text'] : '';
		$search_back_img = ! empty( $option_val_array['search_back_img'] ) ? $option_val_array['search_back_img'] : '';

		$left_section_class  = ! empty( $hero_back_img ) ? 'uploaded' : '';
		$right_section_class = ! empty( $search_back_img ) ? 'uploaded' : '';
		$left_btn_name       = empty( $hero_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );
		$right_btn_name      = empty( $search_back_img ) ? __( 'Select Image', 'headless-cms' ) : __( 'Change Image', 'headless-cms' );

		?>
		<hr>
		<!--Hero Section-->
		<div id="hcms-hero-img-section" class="hcms-hero-img-section <?php echo esc_attr( $left_section_class ); ?>">

			<h2><?php esc_html_e( 'Hero Section', 'headless-cms' ); ?></h2>

			<!--Hero Title-->
			<label for="hcms-hero-title-input"><?php esc_attr_e( 'Hero title', 'headless-cms' ); ?></label>
			<input id="hcms-hero-title-input" class="hcms-hero-title-input" type="text" name="hcms_plugin_options[hero_title]" value="<?php echo esc_attr( $hero_title ); ?>" />

			<!--Hero Subtitle-->
			<label for="hcms-hero-subtitle-input"><?php esc_attr_e( 'Hero subtitle', 'headless-cms' ); ?></label>
			<input id="hcms-hero-subtitle-input" class="hcms-hero_subtitle-input" type="text" name="hcms_plugin_options[hero_subtitle]" value="<?php echo esc_attr( $hero_subtitle ); ?>" />

			<!--Hero Button Text-->
			<label for="hcms-hero-btn-text-input"><?php esc_attr_e( 'Hero btn text', 'headless-cms' ); ?></label>
			<input id="hcms-hero-btn-text-input" class="hcms-hero-btn-text-input" type="text" name="hcms_plugin_options[hero_btn_text]" value="<?php echo esc_attr( $hero_btn_text ); ?>" />

			<!--Hero Image-->
			<h4><?php esc_html_e( 'Hero Background image', 'headless-cms' ); ?></h4>
			<img class="hcms-hero-img" src="<?php echo esc_url( $hero_back_img ); ?>" alt="left site logo" width="150">
			<input class="hcms-hero-input" type="hidden" name="hcms_plugin_options[hero_back_img]" value="<?php echo esc_url( $hero_back_img ); ?>" />
			<input class="hcms-hero-upload-btn hcms-left" data-section-id="#hcms-hero-img-section" type="button" value="<?php echo esc_attr( $left_btn_name ); ?>" />
			<input class="hcms-hero-remove-btn" data-section-id="#hcms-hero-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
		</div>

		<br>
		<hr>
		<!--Search Background Image Section-->
		<div id="hcms-srch-back-img-section" class="hcms-srch-back-img-section <?php echo esc_attr( $right_section_class ); ?>">

			<h2><?php esc_html_e( 'Search Section', 'headless-cms' ); ?></h2>

			<!--Search placeholder text-->
			<label for="hcms-search-placeholder-text"><?php esc_attr_e( 'Search placeholder text', 'headless-cms' ); ?></label>
			<input id="hcms-search-placeholder-text" class="hcms-search-placeholder-text" type="text" name="hcms_plugin_options[search_placeholder_text]" value="<?php echo esc_attr( $search_placeholder_text ); ?>" />

			<!--Search Background Image-->
			<h3><?php esc_html_e( 'Search background Image', 'headless-cms' ); ?></h3>
			<img class="hcms-hero-img" src="<?php echo esc_url( $search_back_img ); ?>" alt="right site logo" width="150">
			<input class="hcms-hero-input" type="hidden" name="hcms_plugin_options[search_back_img]" value="<?php echo esc_url( $search_back_img ); ?>" />
			<input class="hcms-hero-upload-btn hcms-right" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php echo esc_attr( $right_btn_name ); ?>" />
			<input class="hcms-hero-remove-btn" data-section-id="#hcms-srch-back-img-section" type="button" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
		</div>

		<!--Submit Button-->
		<div class="hcms-save-btn-container"><?php submit_button(); ?></div>
	</form>
</div>
