/* global fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Title View
		FusionPageBuilder.fusion_title = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes
			 * @return {Object}
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.values );

				// Create attribute objects
				attributes.attr          = this.buildAttr( atts.values );
				attributes.headingAttr   = this.buildHeadingAttr( atts.values );
				attributes.separatorAttr = this.builderSeparatorAttr( atts.values );
				attributes.style         = this.buildStyleBlock( atts.values, atts.extras );

				// Any extras that need passed on.
				attributes.cid           = this.model.get( 'cid' );
				attributes.output        = atts.values.element_content;
				attributes.style_type    = atts.values.style_type;
				attributes.size          = atts.values.size;
				attributes.content_align = atts.values.content_align;

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
				values.margin_top           = _.fusionValidateAttrValue( values.margin_top, 'px' );
				values.margin_bottom        = _.fusionValidateAttrValue( values.margin_bottom, 'px' );
				values.margin_top_mobile    = _.fusionValidateAttrValue( values.margin_top_mobile, 'px' );
				values.margin_bottom_mobile = _.fusionValidateAttrValue( values.margin_bottom_mobile, 'px' );

				if ( 'default' === values.style_type ) {
					values.style_type = fusionAllElements.fusion_title.defaults.style_type;
				}

				if ( 1 === values.style_type.split( ' ' ).length ) {
					values.style_type += ' solid';
				}

				// Make sure the title text is not wrapped with an unattributed p tag.
				if ( 'undefined' !== typeof values.element_content ) {
					values.element_content = values.element_content.trim();
					values.element_content = values.element_content.replace( /(<p[^>]+?>|<p>|<\/p>)/img, '' );
				}

				if ( 'undefined' !== typeof values.font_size && '' !== values.font_size ) {
					values.font_size = _.fusionGetValueWithUnit( values.font_size );
				}

				if ( 'undefined' !== typeof values.letter_spacing && '' !== values.letter_spacing ) {
					values.letter_spacing = _.fusionGetValueWithUnit( values.letter_spacing );
				}
			},

			buildStyleBlock: function( values, extras ) {
				var style = '';

				if ( ! ( '' === values.margin_top_mobile && '' === values.margin_bottom_mobile ) && ! ( '0px' === values.margin_top_mobile && '20px' === values.margin_bottom_mobile ) ) {
					style += '<style type="text/css">';
					style += '@media only screen and (max-width:' + extras.content_break_point + 'px) {';
					style += '.fusion-body .fusion-title.fusion-title-cid' + this.model.get( 'cid' ) + '{margin-top:' + values.margin_top_mobile + '!important;margin-bottom:' + values.margin_bottom_mobile + '!important;}';
					style += '}';
					style += '</style>';
				}
				return style;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values object.
			 * @return {Object}
			 */
			buildAttr: function( values ) {
				var styles,
					titleSize = 'two',
					attr      = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'fusion-title title fusion-title-cid' + this.model.get( 'cid' ),
						style: ''
					} );

				if ( -1 !== values.style_type.indexOf( 'underline' ) ) {
					styles = values.style_type.split( ' ' );

					_.each( styles, function( style ) {
						attr[ 'class' ] += ' sep-' + style;
					} );

					if ( values.sep_color ) {
						attr.style = 'border-bottom-color:' + values.sep_color + ';';
					}
				} else if ( -1 !== values.style_type.indexOf( 'none' ) ) {
					attr[ 'class' ] += ' fusion-sep-none';
				}

				if ( 'center' === values.content_align ) {
					attr[ 'class' ] += ' fusion-title-center';
				}

				if ( '1' == values.size ) {
					titleSize = 'one';
				} else if ( '2' == values.size ) {
					titleSize = 'two';
				} else if ( '3' == values.size ) {
					titleSize = 'three';
				} else if ( '4' == values.size ) {
					titleSize = 'four';
				} else if ( '5' == values.size ) {
					titleSize = 'five';
				} else if ( '6' == values.size ) {
					titleSize = 'six';
				}

				attr[ 'class' ] += ' fusion-title-size-' + titleSize;

				if ( 'undefined' !== typeof values.font_size && '' !== values.font_size ) {
					attr.style += 'font-size:' + values.font_size + ';';
				}

				if ( '' !== values.margin_top ) {
					attr.style += 'margin-top:' + values.margin_top + ';';
				}

				if ( '' !== values.margin_bottom ) {
					attr.style += 'margin-bottom:' + values.margin_bottom + ';';
				}

				if ( '' === values.margin_top && '' === values.margin_bottom ) {
					attr.style += ' margin-top:0px; margin-bottom:0px';
					attr[ 'class' ] += ' fusion-title-default-margin';
				}

				if ( '' !== values[ 'class' ] ) {
					attr[ 'class' ] += ' ' + values[ 'class' ];
				}

				if ( '' !== values.id ) {
					attr.id = values.id;
				}

				return attr;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values object.
			 * @return {Object}
			 */
			buildHeadingAttr: function( values ) {
				var self        = this,
					headingAttr = {
						class: 'title-heading-' + values.content_align,
						style: ''
					};

				if ( '' !== values.margin_top || '' !== values.margin_bottom ) {
					headingAttr.style += 'margin:0;';
				}

				if ( '' !== values.font_size ) {
					headingAttr.style += 'font-size:1em;';
				}

				if ( 'undefined' !== typeof values.line_height && '' !== values.line_height ) {
					headingAttr.style += 'line-height:' + values.line_height + ';';
				}

				if ( 'undefined' !== typeof values.letter_spacing && '' !== values.letter_spacing ) {
					headingAttr.style += 'letter-spacing:' + values.letter_spacing + ';';
				}

				if ( 'undefined' !== typeof values.text_color && '' !== values.text_color ) {
					headingAttr.style += 'color:' + values.text_color + ';';
				}

				if ( '' !== values.style_tag ) {
					headingAttr.style += values.style_tag;
				}

				headingAttr = _.fusionInlineEditor( {
					cid: self.model.get( 'cid' ),
					overrides: {
						color: 'text_color',
						'font-size': 'font_size',
						'line-height': 'line_height',
						'letter-spacing': 'letter_spacing',
						tag: 'size'
					}
				}, headingAttr );

				return headingAttr;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values object.
			 * @return {Object}
			 */
			builderSeparatorAttr: function( values ) {
				var separatorAttr = {
						class: 'title-sep'
					},
					styles        = values.style_type.split( ' ' );

				_.each( styles, function( style ) {
					separatorAttr[ 'class' ] += ' sep-' + style;
				} );

				if ( values.sep_color ) {
					separatorAttr.style = 'border-color:' + values.sep_color + ';';
				}

				return separatorAttr;
			},

			onCancel: function() {
				this.resetTypography();
			},

			afterPatch: function() {
				this.resetTypography();
			},

			resetTypography: function() {
				jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'fusion-typography-reset', this.model.get( 'cid' ) );

				if ( 800 > jQuery( '#fb-preview' ).width() ) {
					setTimeout( function() {
						jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'resize' );
					}, 50 );
				}
			}
		} );
	} );
}( jQuery ) );
