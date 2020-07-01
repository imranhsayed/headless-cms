/**
 * Category scripts, loaded plugin's settings page.
 *
 * @package headless-cms
 */

/**
 * Internal dependencies
 */
import '../scss/category.scss';

( ( $ ) => {

	/**
	 * Category Class.
	 */
	class Category {

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
			this.mediaUpload( '.hcms_tax_media_button.button' );
			this.addEvents();
			this.ajaxRequest();
		}

		addEvents() {
			$( 'body' ).on( 'click', '.hcms_tax_media_remove', function () {
				$( '#category-image-id' ).val( '' );
				$( '#category-image-wrapper' ).html( '<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />' );
				$( '#hcms_tax_media_button' ).toggleClass( 'hcms_hide' );
				$( '#hcms_tax_media_remove' ).toggleClass( 'hcms_hide' );
			} );
		}

		mediaUpload( btnClass ) {
			let customMedia = true;
			let origSendAttachment = wp.media.editor.send.attachment;

			$( 'body' ).on( 'click', btnClass, function ( e ) {
				let btnID   = '#' + $( this ).attr( 'id' );
				let button  = $( btnID );
				customMedia = true;
				wp.media.editor.send.attachment = function ( props, attachment ) {
					if ( customMedia ) {
						$( '#category-image-id' ).val( attachment.id );
						$( '#category-image-wrapper' ).html( '<img class="custom_media_image" src=""/>' );
						$( '#category-image-wrapper .custom_media_image' ).attr( 'src', attachment.url ).css( 'display', 'block' );
						$( '#hcms_tax_media_button' ).toggleClass( 'hcms_hide' );
						$( '#hcms_tax_media_remove' ).toggleClass( 'hcms_hide' );
					} else {
						return origSendAttachment.apply( btnID, [ props, attachment ] );
					}
				};
				wp.media.editor.open( button );
				return false;
			} );
		}

		ajaxRequest() {
			$( document ).ajaxComplete( function ( event, xhr, settings ) {
				let queryStringArr = settings.data.split( '&' );
				if ( -1 !== $.inArray( 'action=add-tag', queryStringArr ) ) {
					let xml   = xhr.responseXML;
					const response = $( xml ).find( 'term_id' ).text();
					if ( '' != response ) {

						// Clear the thumb image
						$( '#category-image-wrapper' ).html( '' );
					}
				}
			} );
		}
	}

	new Category();

} )( jQuery );
