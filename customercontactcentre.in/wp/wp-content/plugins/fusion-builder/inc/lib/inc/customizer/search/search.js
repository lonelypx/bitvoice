/* global Fuse, fusionFieldsSearch */
jQuery( document ).ready( function() {
	var fuse = new Fuse( fusionFieldsSearch.fields, {
		shouldSort: true,
		threshold: 0.2,
		location: 0,
		distance: 100,
		maxPatternLength: 32,
		minMatchCharLength: 3,
		keys: [
			'label',
			'description'
		]
	} );

	// Search
	jQuery( '#customize-control-fusion_search input' ).on( 'change keyup paste click focus', function() {
		var searchVal     = jQuery( this ).val(),
			results       = {},
			searchWrapper = jQuery( '#customize-control-fusion_search' ).find( '.fusion-search-results' );

		if ( 1 > searchWrapper.length ) {
			jQuery( '#customize-control-fusion_search' ).append( '<div class="fusion-search-results"></div>' );
		}

		// Clear previous results.
		jQuery( '.fusion-search-results' ).empty();

		if ( 2 < searchVal.length ) {
			results = fuse.search( searchVal );
		}

		// Add search results.
		_.each( results, function( result ) {
			jQuery( '#customize-control-fusion_search .fusion-search-results' ).append( '<span class="fusion-search-result" data-setting="' + result.settings + '">' + result.label + '</span>' );
		} );

		// Actions to run when clicking on a search result.
		jQuery( '.fusion-search-result' ).click( function() {

			// Focus on the clicked setting.
			wp.customize.control( jQuery( this ).data( 'setting' ) ).focus();
		} );
	} );

	wp.customize.section.each( function( section ) {

		// Get the pane element.
		var pane      = jQuery( '#sub-accordion-section-' + section.id ),
			sectionLi = jQuery( '#accordion-section-' + section.id );

		// Check if the section is expanded.
		if ( sectionLi.hasClass( 'control-section-fusion-search-section' ) ) {

			// Move element.
			pane.appendTo( sectionLi );

		}
	} );
} );
