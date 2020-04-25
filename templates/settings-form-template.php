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

		headless_cms_get_template_part(
			'templates/hero-section',
			[
				'option_val_array' => $option_val_array,
			] 
		);

		headless_cms_get_template_part(
			'templates/search-section',
			[
				'option_val_array' => $option_val_array,
			] 
		);

		headless_cms_get_template_part(
			'templates/featured-post-section',
			[
				'option_val_array' => $option_val_array,
			] 
		);

		headless_cms_get_template_part(
			'templates/latest-posts-section',
			[
				'option_val_array' => $option_val_array,
			] 
		);

		?>

		<!--Submit Button-->
		<div class="hcms-save-btn-container"><?php submit_button(); ?></div>
	</form>
</div>
