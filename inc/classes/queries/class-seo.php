<?php
/**
 * Register more GraphQL types for PostTypeSEO.
 *
 * e.g. schemaDetails
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc\Queries;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Seo
 */
class Seo {
	
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
		 * Actions
		 */
		add_action( 'graphql_register_types', [ $this, 'register_graphql_fields' ] );
		
	}
	
	/**
	 * Register field.
	 */
	public function register_graphql_fields() {
		
		// Register type 'schemaDetails'.
		register_graphql_field(
			'PostTypeSEO',
			'schemaDetails',
			[
				'type'        => 'String',
				'description' => esc_html__( 'Yoast SEO Schema', 'headless-cms' ),
				'resolve'     => function( $root, $args, $context, $info ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
					
					$post_id    = get_the_ID(); // Current post or page id.
					$post_type  = get_post_type( $post_id );  // Current post type.
					$yoast_meta = \YoastSEO()->meta;
					
					if ( is_home() || is_front_page() ) {
						// Get schema for home page.
						$output = $yoast_meta->for_home_page()->schema;
					} elseif ( 'post' === $post_type ) {
						// Get schema for post. Only post type 'post'.
						$output = $yoast_meta->for_post( $post_id )->schema;
					} else {
						// Get schema for all other page or post.
						$output = $yoast_meta->for_current_page()->schema;
					}
					
					if ( ! empty( $output ) ) {
						$output = wp_json_encode( $output, JSON_UNESCAPED_SLASHES );
						$output = $this->replace_backend_url( $output );
					}
					
					return $output;
				},
			]
		);
	}
	
	/**
	 * Function to replace backend URL with frontend.
	 *
	 * @param string $output String to replace backend URL.
	 *
	 * @return string
	 */
	public function replace_backend_url( $output ) {
		
		$plugin_options = get_option('hcms_plugin_options');
		$frontend_url = is_array( $plugin_options ) && ! empty( $plugin_options['frontend_site_url'] ) ? esc_url( $plugin_options['frontend_site_url'] ) : '';
		
		if ( ! empty( $frontend_url ) ) {
			$frontend_url = untrailingslashit( $frontend_url );
			$output       = str_replace( home_url(), $frontend_url, $output );
		}
		
		return $output;
		
	}
	
}

