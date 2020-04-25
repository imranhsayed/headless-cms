<?php
/**
 * Category Image form Template.
 *
 * @package headless-cms
 */

?>

<div class="form-field term-group">
	<label for="category-image-id"><?php esc_html_e( 'Category Image', 'headless-cms' ); ?></label>
	<input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
	<div id="category-image-wrapper"></div>
	<p>
		<input type="button" class="button button-secondary hcms_tax_media_button" id="hcms_tax_media_button" name="hcms_tax_media_button" value="<?php esc_html_e( 'Add Image', 'headless-cms' ); ?>" />
		<input type="button" class="button button-secondary hcms_tax_media_remove" id="hcms_tax_media_remove" name="hcms_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
	</p>
</div>
