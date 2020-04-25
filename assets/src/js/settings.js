/**
 * Settings scripts, loaded plugin's settings page.
 *
 * @package headless-cms
 */

/**
 * Internal dependencies
 */
import '../scss/settings.scss';

( ( $ ) => {

	/**
	 * Settings Class.
	 */
	class Settings {

		/**
		 * Constructor.
		 *
		 * @return {void}
		 */
		constructor() {
			this.init();
		}

		/**
		 * Init
		 *
		 * @return {void}
		 */
		init() {

			this.handleMediaUpload( '#hcms-hero-img-section' );
			this.handleMediaUpload( '#hcms-srch-back-img-section' );

		}

		/**
		 * Handle Media Upload
		 *
		 * @param {string} sectionId Section Id.
		 *
		 * @return {void}
		 */
		handleMediaUpload( sectionId ) {

			/**
			 * Upload media.
			 */
			let mediaUploader;

			// When the Upload Button is clicked, open the WordPress Media Uploader to select/change the image.
			$( sectionId + ' .hcms-hero-upload-btn' ).click( ( event ) => {

				event.preventDefault();

				if ( mediaUploader ) {
					mediaUploader.open();
					return;
				}

				/* eslint-disable */
				mediaUploader = wp.media.frames.file_frame = wp.media( {
					title: 'Choose Image',
					button: {
						text: 'Choose Image'
					}, multiple: false
				} );
				/* eslint-enable */

				mediaUploader.on( 'select', function () {

					let attachment    = mediaUploader.state().get( 'selection' ).first().toJSON();
					const inputEl     = $( sectionId + ' .hcms-hero-input' );
					const imgEl       = $( sectionId + ' .hcms-hero-img' );
					const uploadBtnEl = $( sectionId + ' .hcms-hero-upload-btn' );

					imgEl.attr( 'src', attachment.url );
					inputEl.val( attachment.url );
					uploadBtnEl.val( 'Change Logo' );
					$( sectionId ).addClass( 'uploaded' );

				} );

				mediaUploader.open();

			} );

			this.handleRemoveMedia( sectionId );
		}

		/**
		 * Handles Remove Media.
		 *
		 * @param {string} sectionId Section Id.
		 *
		 * @return {void}
		 */
		handleRemoveMedia( sectionId ) {

			// When the remove media button is clicked, remove the image url and the image.
			$( sectionId + ' .hcms-hero-remove-btn' ).on( 'click', () => {

				const inputEl     = $( sectionId + ' .hcms-hero-input' );
				const imgEl       = $( sectionId + ' .hcms-hero-img' );
				const uploadBtnEl = $( sectionId + ' .hcms-hero-upload-btn' );

				imgEl.attr( 'src', '' );
				inputEl.val( '' );
				uploadBtnEl.val( 'Select Logo' );
				$( sectionId ).removeClass( 'uploaded' );

			} );
		}
	}

	new Settings();

} )( jQuery );

