<?php
/**
 * Settings class.
 *
 * @package headless-cms
 */

namespace Headless_CMS\Features\Inc;

use Headless_CMS\Features\Inc\Traits\Singleton;

/**
 * Class Settings
 *
 * Settings option name: 'hcms_plugin_options'
 */
class Settings {

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
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );

	}

	/**
	 * Adds settings page for the plugin in the dashboard.
	 */
	public function add_settings_page() {

		$menu_plugin_title = __( 'HCMS Settings', 'headless-cms' );

		// Create new top-level menu.
		add_menu_page(
			__(
				'HCMS Plugin Settings',
				'headless-cms'
			),
			$menu_plugin_title,
			'administrator',
			'hcms-settings-menu-page',
			[ $this, 'plugin_settings_page_content' ],
			'dashicons-admin-generic'
		);

		// Call register settings function.
		add_action( 'admin_init', [ $this, 'register_plugin_settings' ] );
	}

	/**
	 * Register our settings.
	 */
	public function register_plugin_settings() {
		register_setting( 'hcms-plugin-settings-group', 'hcms_plugin_options' );
	}

	/**
	 * Settings Page Content for Orion Plugin.
	 */
	public function plugin_settings_page_content() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/**
		 * Add error/update messages.
		 * Check if the user have submitted the settings.
		 * WordPress will add the "settings-updated" $_GET parameter to the url.
		 */
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

			// Add settings saved message with the class of "updated".
			add_settings_error( 'hcms_app_messages', 'hcms_app_message', __( 'Settings Saved', 'headless-cms' ), 'updated' );

		}

		// Show error/update messages.
		settings_errors( 'hcms_app_messages' );

		include_once HEADLESS_CMS_TEMPLATE_PATH . 'settings-form-template.php';

	}
}
