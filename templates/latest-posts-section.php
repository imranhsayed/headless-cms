<?php
/**
 * Latest Posts Section
 *
 * @package headles-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$latest_post_heading = ! empty( $option_val_array['latest_post_heading'] ) ? $option_val_array['latest_post_heading'] : '';
?>

<hr>
<!--Latest post section-->
<div class="hcms-latest-post">

	<h2><?php esc_html_e( 'Latest Posts Section', 'headless-cms' ); ?></h2>

	<!--Latest Post Heading-->
	<label for="hcms-latest-post-heading-input"><?php esc_attr_e( 'Latest posts heading', 'headless-cms' ); ?></label>
	<input id="hcms-latest-post-heading-input" class="hcms-latest-post-heading-input" type="text" name="hcms_plugin_options[latest_post_heading]" value="<?php echo esc_attr( $latest_post_heading ); ?>" />
</div>
