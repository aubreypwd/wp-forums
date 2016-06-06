( function( $ ) {

	/**
	 * Show/upload Media based on file type.
	 *
	 * See wp-forums.php aubreypwd_wp_forums_metabox_add_media_test_display()
	 * for the buttons that pass the type here.
	 *
	 * @author  Aubrey Portwood
	 *
	 * @return {Object}
	 */
	$.fn.aubreypwdMediaType = function() {
		var aubreypwdMediaType = this; // This makes this easier to understand.

		// The buttons in wp-forums.php aubreypwd_wp_forums_metabox_add_media_test_display().
		aubreypwdMediaType.$buttons = $( '.js-add-media-button' );

		// When we click on of the buttons.
		aubreypwdMediaType.$buttons.on( 'click', function( event ) {
			event.preventDefault(); // Don't "submit" when clicked.

			var button = this; // The button that was clicked.
			var type   = $( button ).data( 'type' ); // The type on the data-type="" attribute.

			// Open the wp.media frame.
			var frame = wp.media( {
				multiple: false, // Change to true for multiple file selections.

				/*
				 * Here is where the main magic happens.
				 *
				 * We take the type, e.g. video, image, audio,
				 * and we send it to library.type which only
				 * shows the files of that type.
				 */
				library: { type : type },
			} );

			// When a file is selected.
			frame.on( 'select', function() {

				// Get that file.
				var attachment = frame.state().get('selection').first().toJSON();

				// We don't really need to do anything so just show it to the user.
				alert( attachment );
			} );

			// Open the frame, we've got it setup.
			frame.open();
		} );

	};

	// Load the aubreypwdMediaType function.
	$( document ).ready( $.fn.aubreypwdMediaType );
} ( jQuery ) );
