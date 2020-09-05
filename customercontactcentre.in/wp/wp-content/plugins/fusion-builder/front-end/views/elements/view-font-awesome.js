/* global FusionPageBuilderApp */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Font Awesome Element View.
		FusionPageBuilder.fusion_fontawesome = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				this.afterPatch();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var params = this.model.get( 'params' );
				this.$el.removeClass( 'fusion-element-alignment-right fusion-element-alignment-left' );
				if ( 'undefined' !== typeof params.alignment && ( 'right' === params.alignment || 'left' === params.alignment ) ) {
					this.$el.addClass( 'fusion-element-alignment-' + params.alignment );
				}
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object}
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.values );

				// Create attribute objects
				attributes.attr      = this.buildAttr( atts.values );

				// Any extras that need passed on.
				attributes.cid       = this.model.get( 'cid' );
				attributes.alignment = atts.values.alignment;
				attributes.output    = atts.values.element_content;

				return attributes;
			},

			/**
			 * Modify values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.font_size = _.fusionValidateAttrValue( this.convertDeprecatedSizes( values.size ), '' );
			},

			/**
			 * Converts deprecated font sizes.
			 *
			 * @since 2.0
			 * @param {string} size - The size (small|medium|large).
			 * @return {string}
			 */
			convertDeprecatedSizes: function( size ) {

				switch ( size ) {
				case 'small':
					return '10px';
				case 'medium':
					return '18px';
				case 'large':
					return '40px';
				default:
					return size;
				}
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {Object}
			 */
			buildAttr: function( values ) {
				var legacyIcon              =  false;
				var attr                    = {};
				values.circle_yes_font_size = values.font_size * 0.88;
				values.line_height          = values.font_size * 1.76;
				values.icon_margin          = values.font_size * 0.5;

				// Check if an old icon shortcode is used, where no margin option is present, or if all margins were left empty.
				if ( 'undefined' === typeof values.margin_left || ( '' === values.margin_top && '' === values.margin_right && '' === values.margin_bottom && '' === values.margin_left ) ) {
					legacyIcon = true;
				}
				attr = {
					class: 'fontawesome-icon ' + _.fusionFontAwesome( values.icon ) + ' circle-' + values.circle
				};
				attr = _.fusionVisibilityAtts( values.hide_on_mobile, attr );
				attr.style = '';

				if ( 'yes' === values.circle ) {

					if ( values.circlebordercolor ) {
						attr.style += 'border-color:' + values.circlebordercolor + ';';
					}

					if ( values.circlecolor ) {
						attr.style += 'background-color:' + values.circlecolor + ';';
					}

					attr.style += 'font-size:' + values.circle_yes_font_size + 'px;';
					attr.style += 'line-height:' + values.line_height + 'px;height:' + values.line_height + 'px;width:' + values.line_height + 'px;';

				} else {
					attr.style += 'font-size:' + values.font_size + 'px;';
				}

				if ( legacyIcon ) {
					if ( 'left' === values.alignment ) {
						values.icon_margin_position = 'right';
					} else if ( 'right' === values.alignment ) {
						values.icon_margin_position = 'left';
					} else {
						values.icon_margin_position = FusionPageBuilderApp.$el.hasClass( 'rtl' ) ? 'left' : 'right';
					}

					if ( 'center' === values.alignment ) {
						attr.style += 'margin-left:0;margin-right:0;';
					} else {
						attr.style += 'margin-' + values.icon_margin_position + ':' + values.icon_margin + 'px;';
					}
				} else {
					if ( values.margin_top ) {
						attr.style += 'margin-top:' + values.margin_top + ';';
					}

					if ( values.margin_right ) {
						attr.style += 'margin-right:' + values.margin_right + ';';
					}

					if ( values.margin_bottom ) {
						attr.style += 'margin-bottom:' + values.margin_bottom + ';';
					}

					if ( values.margin_left ) {
						attr.style += 'margin-left:' + values.margin_left + ';';
					}
				}

				if ( values.iconcolor ) {
					attr.style += 'color:' + values.iconcolor + ';';
				}

				if ( values.rotate ) {
					attr[ 'class' ] += ' fa-rotate-' + values.rotate;
				}

				if ( 'yes' === values.spin ) {
					attr[ 'class' ] += ' fa-spin';
				}

				if ( values.flip ) {
					attr[ 'class' ] += ' fa-flip-' + values.flip;
				}

				if ( values[ 'class' ] ) {
					attr[ 'class' ] += ' ' + values[ 'class' ];
				}

				if ( values.id ) {
					attr.id = values.id;
				}

				attr = _.fusionAnimations( values, attr );

				return attr;
			}
		} );
	} );
}( jQuery ) );
