/* global Fuse, fusionIconSearch */
var FusionPageBuilder = FusionPageBuilder || {},
	FusionDelay,
	FusionApp;

FusionPageBuilder.options = FusionPageBuilder.options || {};

FusionDelay = ( function() {
	var timer = 0;

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}() );

FusionPageBuilder.options.fusionIconPicker = {
	optionIconpicker: function( $element ) {
		var self = this,
			$iconPicker;

		$element    = $element || this.$el;
		$iconPicker = $element.find( '.fusion-iconpicker' );

		if ( $iconPicker.length ) {
			$iconPicker.each( function() {
				var $input     = jQuery( this ).find( '.fusion-iconpicker-input' ),
					value      = $input.val(),
					splitVal,
					$container = jQuery( this ).find( '.icon_select_container' ),
					$search    = jQuery( this ).find( '.fusion-icon-search' ),
					output     = jQuery( '.fusion-icons-rendered' ).html();

				$container.append( output );

				if ( '' !== value && -1 === value.indexOf( ' ' ) ) {
					value = FusionApp.checkLegacyIcons( value );

					// Update model.
					self.model.attributes[ jQuery( this ).closest( 'li.fusion-builder-option' ).data( 'option-id' ) ] = value;

					// Wait until options tab is rendered.
					setTimeout( function() {

						// Update form field with new values.
						$input.attr( 'value', value ).trigger( 'change' );
					}, 1000 );
				}

				if ( value && '' !== value ) {
					splitVal = value.split( ' ' );
					if ( 2 === splitVal.length ) {
						$container.find( '.' + splitVal[ 0 ] + '.' + splitVal[ 1 ] ).parent().addClass( 'selected-element' );
					}
				}

				$container.prepend( $container.find( '.selected-element' ) );

				// Icon click.
				$container.find( '.icon_preview' ).on( 'click', function( event ) {
					var $icon      = jQuery( this ).find( 'i' ),
						subset     = 'fas',
						$scopedContainer = jQuery( this ).closest( '.fusion-iconpicker' ),
						fontName   = 'fa-' + $icon.attr( 'data-name' );

					if ( $icon.hasClass( 'fab' ) ) {
						subset = 'fab';
					} else if ( $icon.hasClass( 'far' ) ) {
						subset = 'far';
					} else if ( $icon.hasClass( 'fal' ) ) {
						subset = 'fal';
					}

					if ( jQuery( this ).hasClass( 'selected-element' ) ) {
						jQuery( this ).removeClass( 'selected-element' );
						$scopedContainer.find( 'input.fusion-iconpicker-input' ).attr( 'value', '' ).trigger( 'change' );
						$scopedContainer.find( '.fusion-iconpicker-icon > span' ).attr( 'class', '' );
					} else {
						jQuery( event.currentTarget ).addClass( 'selected-element' ).siblings( '.icon_preview' ).removeClass( 'selected-element' );
						$scopedContainer.find( 'input.fusion-iconpicker-input' ).attr( 'value', fontName + ' ' + subset ).trigger( 'change' );
						$scopedContainer.find( '.fusion-iconpicker-icon > span' ).attr( 'class', fontName + ' ' + subset );
					}
				} );

				// Icon Search bar
				$search.on( 'change paste keyup', function() {
					var $searchInput = jQuery( this );

					FusionDelay( function() {
						var options,
							fuse,
							result;

						if ( $searchInput.val() && '' !== $searchInput.val() ) {
							value = $searchInput.val().toLowerCase();

							if ( 3 > value.length ) {
								return;
							}

							$container.find( '.icon_preview' ).css( 'display', 'none' );
							options = {
								threshold: 0.2,
								location: 0,
								distance: 100,
								maxPatternLength: 32,
								minMatchCharLength: 3,
								keys: [
									'name',
									'keywords',
									'categories'
								]
							};
							fuse   = new Fuse( fusionIconSearch, options );
							result = fuse.search( value );

							_.each( result, function( resultIcon ) {
								$container.find( '.icon-fa-' + resultIcon.name ).css( 'display', 'inline-flex' );
							} );
						} else {
							$container.find( '.icon_preview' ).css( 'display', 'inline-flex' );
						}
					}, 100 );
				} );
			} );
		}
	}
};
