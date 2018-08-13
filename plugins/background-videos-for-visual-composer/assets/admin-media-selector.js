/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @param    object    $    A reference to the jQuery object
 */
function renderMediaUploader( $, $button, mime_type ) {
	'use strict';

	var file_frame, image_data, json;

	/**
	 * If an instance of file_frame already exists, then we can open it
	 * rather than creating a new instance.
	 */
	if ( undefined !== file_frame ) {

		file_frame.open();
		return;

	}

	/**
	 * If we're this far, then an instance does not exist, so we need to
	 * create our own.
	 *
	 * Here, use the wp.media library to define the settings of the Media
	 * Uploader. We're opting to use the 'post' frame which is a template
	 * defined in WordPress core and are initializing the file frame
	 * with the 'insert' state.
	 *
	 * We're also not allowing the user to select more than one image.
	 */
	var frame_options = {
		frame:    'post',
		state:    'insert',
		multiple: false
	};
	/**
	 * If we've got a mime type, add it
	 */
	if( mime_type.length ) {
		frame_options.library = {
			type: mime_type
		};
	}
	file_frame = wp.media.frames.file_frame = wp.media(frame_options);
	
	

	/**
	 * Setup an event handler for what to do when an image has been
	 * selected.
	 *
	 * Since we're using the 'view' state when initializing
	 * the file_frame, we need to make sure that the handler is attached
	 * to the insert event.
	 */
	file_frame.on( 'insert', function() {

		// Read the JSON data returned from the Media Uploader
		json = file_frame.state().get( 'selection' ).first().toJSON();

		// First, make sure that we have the URL of an image to display
		if ( 0 > $.trim( json.url.length ) ) {
			return;
		}

		$button.next('.wpb-media-input-field').val(json.url);

	});

	// Now display the actual file_frame
	file_frame.open();

}


(function( $ ) {
	'use strict';

	$(function() {

		$( '.wpb-media-input-button' ).on( 'click', function( e ) {

			// Stop the anchor's default behavior
			e.preventDefault();

			// We're going to send this button to the function
			var $this = $(this);

			// Get the mime type from the data attribute as an array
			var mime_type = $(this).data('mime-type').split(',');

			// Display the media uploader
			renderMediaUploader( $, $this, mime_type );

		});

	});

})( jQuery );