<?php
/**
 * Category class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Category
 */
class Category {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Action
		 */
		add_action( 'category_add_form_fields', [ $this, 'add_category_image' ], 10, 2 );
		add_action( 'created_category', [ $this, 'save_category_image' ], 10, 2 );
		add_action( 'category_edit_form_fields', [ $this, 'update_category_image' ], 10, 2 );
		add_action( 'edited_category', [ $this, 'updated_category_image' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_media' ] );
		add_action( 'admin_footer', [ $this, 'add_script' ] );

	}

	/**
	 * Load Media.
	 */
	public function load_media() {
		wp_enqueue_media();
	}

	/**
	 * Add form fields
	 *
	 * @param string $taxonomy
	 */
	public function add_category_image ( $taxonomy ) {
		include_once HEADLESS_CMS_TEMPLATE_PATH . 'category-img-form.php';
	}

	/**
	 * Save the form fields
	 *
	 * @param integer $term_id term ID.
	 */
	public function save_category_image ( $term_id ) {
		if( ! empty( $_POST['category-image-id'] ) ){
			$image = sanitize_text_field( $_POST['category-image-id'] );
			add_term_meta( $term_id, 'category-image-id', $image, true );
		}
	}

	/**
	 * Edit the form fields
	 *
	 * @param object $term Term.
	 */
	public function update_category_image ( $term ) { ?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="category-image-id"><?php esc_html_e( 'Category Image', 'headless-cms' ); ?></label>
			</th>
			<td>
				<?php $image_id = get_term_meta ( $term->term_id, 'category-image-id', true ); ?>
				<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
				<div id="category-image-wrapper">
					<?php if ( $image_id ) { ?>
						<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
					<?php } ?>
				</div>
				<p>
					<input type="button" class="button button-secondary hcms_tax_media_button" id="hcms_tax_media_button" name="hcms_tax_media_button" value="<?php esc_html_e( 'Add Image', 'headless-cms' ); ?>" />
					<input type="button" class="button button-secondary hcms_tax_media_remove" id="hcms_tax_media_remove" name="hcms_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
				</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update form fields
	 *
	 * @param integer $term_id.
	 */
	public function updated_category_image ( $term_id ) {
		if( ! empty( $_POST['category-image-id'] ) ){
			$image = sanitize_text_field( $_POST['category-image-id'] );
			update_term_meta ( $term_id, 'category-image-id', $image );
		} else {
			update_term_meta ( $term_id, 'category-image-id', '' );
		}
	}

	/*
	 * Add script
	 * @since 1.0.0
	 */
	public function add_script() { ?>
		<script>
			jQuery(document).ready( function($) {
				function ct_media_upload(button_class) {
					let _custom_media = true,
					    _orig_send_attachment = wp.media.editor.send.attachment;
					$('body').on('click', button_class, function(e) {
						let button_id = '#'+$(this).attr('id');
						let send_attachment_bkp = wp.media.editor.send.attachment;
						let button = $(button_id);
						_custom_media = true;
						wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$('#category-image-id').val(attachment.id);
								$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								$('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
							} else {
								return _orig_send_attachment.apply( button_id, [props, attachment] );
							}
						}
						wp.media.editor.open(button);
						return false;
					});
				}
				ct_media_upload('.hcms_tax_media_button.button');
				$('body').on('click','.hcms_tax_media_remove',function(){
					$('#category-image-id').val('');
					$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
				});
				
				$(document).ajaxComplete(function(event, xhr, settings) {
					let queryStringArr = settings.data.split('&');
					if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
						let xml = xhr.responseXML;
						$response = $(xml).find('term_id').text();
						if($response!=""){
							// Clear the thumb image
							$('#category-image-wrapper').html('');
						}
					}
				});
			});
		</script>
	<?php }

}
