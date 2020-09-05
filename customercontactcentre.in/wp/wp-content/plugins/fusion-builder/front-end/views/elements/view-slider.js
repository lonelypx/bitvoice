var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Slider parent View.
		FusionPageBuilder.fusion_slider = FusionPageBuilder.ParentElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {

				// TODO: save DOM and apply instead of generating
				this.generateChildElements();

				this._refreshJs();
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object}
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {},
					slides = window.FusionPageBuilderApp.findShortcodeMatches( atts.params.element_content, 'fusion_slide' ),
					slideElement;

				this.model.attributes.showPlaceholder = false;

				if ( 1 <= slides.length ) {
					slideElement = slides[ 0 ].match( window.FusionPageBuilderApp.regExpShortcode( 'fusion_slide' ) );
					this.model.attributes.showPlaceholder = ( 'undefined' === typeof slideElement[ 5 ] || '' === slideElement[ 5 ] || 'undefined' ===  slideElement[ 5 ] ) ? true : false;
				}

				// Validate values.
				this.validateValues( atts.values );

				// Create attribute objects.
				attributes.sliderShortcode = this.buildSliderAttr( atts.values );

				return attributes;
			},

			/**
			 * Modifies the values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values object.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.width  = _.fusionValidateAttrValue( values.width, 'px' );
				values.height = _.fusionValidateAttrValue( values.height, 'px' );
			},

			buildSliderAttr: function( values ) {
				var sliderShortcode = _.fusionVisibilityAtts( values.hide_on_mobile, {
					class: 'fusion-slider-sc flexslider'
				}
				);

				if ( true === this.model.attributes.showPlaceholder ) {
					sliderShortcode[ 'class' ] += ' fusion-show-placeholder';
				}

				if ( '' !== values.hover_type ) {
					sliderShortcode[ 'class' ] += ' flexslider-hover-type-' + values.hover_type;
				}

				sliderShortcode.style = 'max-width:' + values.width + ';height:' + values.height + ';';
				if ( '' !== values[ 'class' ] ) {
					sliderShortcode[ 'class' ] += ' ' + values[ 'class' ];
				}
				if ( '' !== values.id ) {
					sliderShortcode.id = values.id;
				}

				return sliderShortcode;
			}

		} );
	} );
}( jQuery ) );
