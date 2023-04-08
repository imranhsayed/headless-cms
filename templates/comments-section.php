<?php
/**
 * Comments Section Settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$allow_anonymous_comments = ! empty( $option_val_array['allow_anonymous_comments'] ) ? $option_val_array['allow_anonymous_comments'] : '';
?>

<hr>
<div id="hcms-allow-anonymous-comments-section" class="hcms-allow-anonymous-comments-section">

	<h2><?php esc_html_e( 'Comments Settings Section', 'headless-cms' ); ?></h2>

	<!--Allow Anonymous Comments-->
	<label for="hcms-allow-anonymous-comments-input"><?php esc_attr_e( 'Allow Anonymous Comments.', 'headless-cms' ); ?></label>
	<input id="hcms-allow-anonymous-comments-input" class="hcms-allow-anonymous-comments-input" type="checkbox" name="hcms_plugin_options[allow_anonymous_comments]" value="1" <?php checked(1, esc_attr( $allow_anonymous_comments ), true ) ?> />
	<p><?php esc_attr_e( 'Checking this box, will allow users to submit comments without logging-in, via REST API ', 'headless-cms' ); ?></p>
</div>

<br>
<hr>
