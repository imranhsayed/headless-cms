<?php
/**
 * Frontend Site Details Section Settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$frontend_site_url = ! empty( $option_val_array['frontend_site_url'] ) ? $option_val_array['frontend_site_url'] : '';
?>

<hr>
<!--Frontend Site Details Section-->
<div id="hcms-site-details-section" class="hcms-site-details-section">
	
	<h2><?php esc_html_e( 'Frontend Site Details Section', 'headless-cms' ); ?></h2>
    <p><strong><?php esc_html_e( 'Example for local:', 'headless-cms' ); ?></strong> http://localhost:3000</p>
    <p><strong><?php esc_html_e( 'Example for live:', 'headless-cms' ); ?></strong> https://example.com</p>
    
	<!--Frontend Site URL-->
	<label for="hcms-frontend-site-url-input"><?php esc_attr_e( 'Frontend Site URL', 'headless-cms' ); ?></label>
	<input id="hcms-frontend-site-url-input" class="hcms-frontend-site-url-input" type="text" name="hcms_plugin_options[frontend_site_url]" value="<?php echo esc_attr( $frontend_site_url ); ?>" />
</div>

<br>
<hr>
