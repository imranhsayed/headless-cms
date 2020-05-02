<?php
/**
 * Featured post section settings
 *
 * @package headless-cms
 */

if ( empty( $option_val_array ) ) {
	$option_val_array = [];
}

$featured_post_heading   = ! empty( $option_val_array['featured_post_heading'] ) ? $option_val_array['featured_post_heading'] : '';
$first_featured_post_id  = ! empty( $option_val_array['first_featured_post_id'] ) ? $option_val_array['first_featured_post_id'] : '';
$second_featured_post_id = ! empty( $option_val_array['second_featured_post_id'] ) ? $option_val_array['second_featured_post_id'] : '';
$third_featured_post_id  = ! empty( $option_val_array['third_featured_post_id'] ) ? $option_val_array['third_featured_post_id'] : '';

$args = [
	'post_type'              => 'post',
	'post_status'            => 'publish',
	'orderby'                => 'date',
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,

];

$latest_posts_data = new WP_Query( $args );
$latest_posts      = ! empty( $latest_posts_data->posts ) ? $latest_posts_data->posts : [];
?>

<!--Select featured post section-->
<div class="hcms-featured-post-selection">

	<h2><?php esc_html_e( 'Featured posts section', 'headless-cms' ); ?></h2>

	<!--Featured Post Heading-->
	<label for="hcms-featured-post-heading-input"><?php esc_attr_e( 'Hero title', 'headless-cms' ); ?></label>
	<input id="hcms-featured-post-heading-input" class="hcms-featured-post-heading-input" type="text" name="hcms_plugin_options[featured_post_heading]" value="<?php echo esc_attr( $featured_post_heading ); ?>" />

	<h4><?php esc_html_e( 'Select three featured posts', 'headless-cms' ); ?></h4>

	<!--Featured Post One Selection-->
	<label for="featured-post-one"><?php esc_attr_e( 'Featured Post One', 'headless-cms' ); ?></label>
	<select id="featured-post-one" name="hcms_plugin_options[first_featured_post_id]" size="1">

		<?php
		if ( ! empty( $latest_posts && is_array( $latest_posts ) ) ) {
			foreach ( $latest_posts as $latest_post ) {

				$is_selected = ( intval( $first_featured_post_id ) === $latest_post->ID ) ? 'selected' : '';
				?>
				<option value="<?php echo esc_attr( $latest_post->ID ); ?>" <?php echo esc_attr( $is_selected ); ?>>
					<?php echo esc_html( $latest_post->post_name ); ?>
				</option>
				<?php
			}
		}
		?>
	</select>
	<br>

	<!--Featured Post Two Selection-->
	<label for="featured-post-two"><?php esc_attr_e( 'Featured Post Two', 'headless-cms' ); ?></label>
	<select id="featured-post-two" name="hcms_plugin_options[second_featured_post_id]" size="1">

		<?php
		if ( ! empty( $latest_posts && is_array( $latest_posts ) ) ) {
			foreach ( $latest_posts as $latest_post ) {

				$is_selected = ( intval( $second_featured_post_id ) === $latest_post->ID ) ? 'selected' : '';
				?>
				<option value="<?php echo esc_attr( $latest_post->ID ); ?>" <?php echo esc_attr( $is_selected ); ?>>
					<?php echo esc_html( $latest_post->post_name ); ?>
				</option>
				<?php
			}
		}
		?>
	</select>
	<br>

	<!--Featured Post Three Selection-->
	<label for="featured-post-three"><?php esc_attr_e( 'Featured Post Three', 'headless-cms' ); ?></label>
	<select id="featured-post-three" name="hcms_plugin_options[third_featured_post_id]" size="1">

		<?php
		if ( ! empty( $latest_posts && is_array( $latest_posts ) ) ) {
			foreach ( $latest_posts as $latest_post ) {

				$is_selected = ( intval( $third_featured_post_id ) === $latest_post->ID ) ? 'selected' : '';
				?>
				<option value="<?php echo esc_attr( $latest_post->ID ); ?>" <?php echo esc_attr( $is_selected ); ?>>
					<?php echo esc_html( $latest_post->post_name ); ?>
				</option>
				<?php
			}
		}
		?>
	</select>
	<br>
	<br>

</div>
