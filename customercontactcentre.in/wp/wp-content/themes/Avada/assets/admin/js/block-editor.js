jQuery( window ).load( function() {
	setSidebarClasses();

	jQuery( '.block-editor-page' ).on( 'change', '#pyre_sidebar_1', function() {
		setSidebarClasses();
	} );

	jQuery( '.block-editor-page' ).on( 'change', 'select[name="sidebar_2_generator_replacement[0]"]', function() {
		setSidebarClasses();
	} );

	function setSidebarClasses() {
		var sidebarOneValue = jQuery( '#pyre_sidebar_1' ).children( 'option:selected' ).val(),
			sidebarOneText  = jQuery( '#pyre_sidebar_1' ).children( 'option:selected' ).text(),
			sidebarTwoValue = jQuery( 'select[name="sidebar_2_generator_replacement[0]"]' ).children( 'option:selected' ).val(),
			sidebarTwoText  = jQuery( 'select[name="sidebar_2_generator_replacement[0]"]' ).children( 'option:selected' ).text();

		// No siedebar.
		if ( ! sidebarOneValue || ( 'default_sidebar' === sidebarOneValue && -1 !== sidebarOneText.indexOf( 'None' ) ) ) {
			jQuery( '.block-editor-page' ).removeClass( 'has-sidebar' ).removeClass( 'double-sidebars' );
		} else {

			// Single sidebar.
			jQuery( '.block-editor-page' ).addClass( 'has-sidebar' );

			if ( ! sidebarTwoValue || ( 'default_sidebar' === sidebarTwoValue && -1 !== sidebarTwoText.indexOf( 'None' ) ) ) {
				jQuery( '.block-editor-page' ).removeClass( 'double-sidebars' );
			} else {

				// Double sidebars.
				jQuery( '.block-editor-page' ).addClass( 'double-sidebars' );
			}
		}
	}
} );
