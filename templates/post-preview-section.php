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
$jwt_secret = ! empty( $option_val_array['jwt_secret'] ) ? $option_val_array['jwt_secret'] : 'wQ2gp%lGB(T0~!eV?FJg3M+tAy-R0YLF2rH_ Lou>k|7iFfGuH+0#oPTLXiG@8r-';
?>

<hr>
<!--Frontend Site Details Section-->
<div id="hcms-activate-preview-section" class="hcms-activate-preview-section">
	
	<h2><?php esc_html_e( 'Frontend Site Details Section', 'headless-cms' ); ?></h2>
	
	<!--Frontend Site URL-->
	<label for="hcms-activate-preview-input"><?php esc_attr_e( 'Activate Post Preview', 'headless-cms' ); ?></label>
	<input id="hcms-activate-preview-input" class="hcms-activate-preview-input" type="checkbox" name="hcms_plugin_options[activate_preview]" value="1" <?php checked(1, esc_attr( $activate_preview ), true ) ?> />
</div>

<!--JWT Secret-->
<div id="hcms-jwt-secret-section" class="hcms-jwt-secret-section">

    <h2><?php esc_html_e( 'JWT Secret Section', 'headless-cms' ); ?></h2>
    <p>
        <?php esc_html_e( 'You can generate the secret by going to:', 'headless-cms' ); ?>
        <a href="<?php echo esc_url('https://api.wordpress.org/secret-key/1.1/salt/'); ?>" target="_blank">https://api.wordpress.org/secret-key/1.1/salt/</a>
    </p>
    <p><strong><?php esc_html_e('Example:', 'headless-cms'); ?></strong> wQ2gp%lGB(T0~!eV?FJg3M+tdd-RddLFa2rH_ Lou>k|7iFfGuH+0#oPTLXiG@8r-</p>

    <!--Frontend Site URL-->
    <label for="hcms-jwt-secret-input"><?php esc_attr_e( 'JWT Secret: ', 'headless-cms' ); ?></label>
    <input id="hcms-jwt-secret-input" size="76" class="hcms-jwt-secret-input" type="text" name="hcms_plugin_options[jwt_secret]" value="<?php echo esc_attr( $jwt_secret ); ?>"/>
</div>

<br>
<hr>
