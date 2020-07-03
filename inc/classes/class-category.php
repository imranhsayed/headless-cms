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
 *
 * Adds Category image upload option on the category page in the WordPress dashboard.
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

	}

	/**
	 * Add form fields
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_category_image( $taxonomy ) {
		include_once HEADLESS_CMS_TEMPLATE_PATH . 'category-img-form.php';
	}

	/**
	 * Save the form fields
	 *
	 * @param integer $term_id term ID.
	 */
	public function save_category_image( $term_id ) {
		if ( ! empty( $_POST['category-image-id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			$image    = sanitize_text_field( $_POST['category-image-id'] );
			$meta_key = 'category-image-id';

			add_term_meta( $term_id, $meta_key, $image, true );
		}
	}

	/**
	 * Edit the form fields
	 *
	 * @param object $term Term.
	 */
	public function update_category_image( $term ) { ?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="category-image-id"><?php esc_html_e( 'Category Image', 'headless-cms' ); ?></label>
			</th>
			<td>
				<?php
					$image_id           = get_term_meta( $term->term_id, 'category-image-id', true );
					$add_image_class    = ! empty( $image_id ) ? 'hcms_hide' : '';
					$remove_image_class = empty( $image_id ) ? 'hcms_hide' : '';
				?>
				<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo esc_url( $image_id ); ?>">
				<div id="category-image-wrapper">
					<?php if ( $image_id ) { ?>
						<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
					<?php } ?>
				</div>
				<div>
					<input type="button" class="button button-secondary hcms_tax_media_button <?php echo esc_attr( $add_image_class ); ?>" id="hcms_tax_media_button" name="hcms_tax_media_button" value="<?php esc_html_e( 'Add Image', 'headless-cms' ); ?>" />
					<input type="button" class="button button-secondary hcms_tax_media_remove <?php echo esc_attr( $remove_image_class ); ?>" id="hcms_tax_media_remove" name="hcms_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'headless-cms' ); ?>" />
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update form fields
	 *
	 * @param integer $term_id Term ID.
	 */
	public function updated_category_image( $term_id ) {
		if ( ! empty( $_POST['category-image-id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			$image = sanitize_text_field( $_POST['category-image-id'] );
			update_term_meta( $term_id, 'category-image-id', $image );
		} else {
			update_term_meta( $term_id, 'category-image-id', '' );
		}
	}

}
