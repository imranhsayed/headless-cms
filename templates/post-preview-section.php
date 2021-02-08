<?php
/**
 * Post Preview Section Settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$activate_preview = ! empty( $option_val_array['activate_preview'] ) ? $option_val_array['activate_preview'] : '';
?>

<hr>
<!--Frontend Site Details Section-->
<div id="hcms-activate-preview-section" class="hcms-activate-preview-section">
	
	<h2><?php esc_html_e( 'Frontend Site Details Section', 'headless-cms' ); ?></h2>
	
	<!--Frontend Site URL-->
	<label for="hcms-activate-preview-input"><?php esc_attr_e( 'Activate Post Preview', 'headless-cms' ); ?></label>
	<input id="hcms-activate-preview-input" class="hcms-activate-preview-input" type="checkbox" name="hcms_plugin_options[activate_preview]" value="1" <?php checked(1, esc_attr( $activate_preview ), true ) ?> />
</div>

<br>
<hr>
