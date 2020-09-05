/* global FusionPageBuilderElements, fusionAppConfig, FusionPageBuilderApp, FusionPageBuilderViewManager */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	_.extend( FusionPageBuilder.Callback.prototype, {
		fusion_preview: function( name, value, args, view ) {
			var property = args.property,
				element  = window.fusionAllElements[ view.model.get( 'element_type' ) ],
				$theEl;

			if ( ! value && '' !== value ) {
				return;
			}

			if ( ! args.skip ) {
				view.changeParam( name, value );
			}

			if ( '' === value && 'undefined' !== typeof element && 'undefined' !== typeof element.defaults && 'undefined' !== typeof element.defaults[ name ] ) {
				value = element.defaults[ name ];
			}
			if ( 'undefined' !== typeof args.dimension ) {
				property = ( 'undefined' !== typeof args.property[ name ] ) ? args.property[ name ] : name.replace( /_/g, '-' );
			}
			if ( 'undefined' !== typeof args.unit ) {
				value = _.fusionGetValueWithUnit( value, args.unit );
			}
			$theEl = ( 'undefined' === typeof args.selector ) ? view.$el : view.$el.find( args.selector );
			if ( 'string' === typeof property ) {
				$theEl.css( property, value );
			}
			if ( 'object' === typeof property ) {
				_.each( args.property, function( singleProperty ) {
					$theEl.css( singleProperty, value );
				} );
			}

			return {
				render: false
			};
		},

		fusion_add_id: function( name, value, args, view ) {
			var $theEl;

			if ( ! args.skip ) {
				view.changeParam( name, value );
			}

			$theEl = ( 'undefined' === typeof args.selector ) ? view.$el : view.$el.find( args.selector );

			$theEl.attr( 'id', value );

			return {
				render: false
			};
		},

		fusion_add_class: function( name, value, args, view ) {
			var $theEl,
				existingValue = view.model.attributes.params[ name ];

			if ( ! args.skip ) {
				view.changeParam( name, value );
			}

			$theEl = ( 'undefined' === typeof args.selector ) ? view.$el : view.$el.find( args.selector );

			$theEl.removeClass( existingValue );
			$theEl.addClass( value );

			return {
				render: false
			};
		},

		fusion_toggle_class: function( name, value, args, view ) {
			var $theEl;

			if ( ! args.skip ) {
				view.changeParam( name, value );
			}

			$theEl = ( 'undefined' === typeof args.selector ) ? view.$el : view.$el.find( args.selector );

			if ( 'object' === typeof args.classes ) {
				_.each( args.classes, function( optionClass, optionValue ) {
					$theEl.removeClass( optionClass );
					if ( value === optionValue ) {
						$theEl.addClass( optionClass );
					}
				} );
			}

			return {
				render: false
			};
		},

		fusion_ajax: function( name, value, modelData, args, cid, action, model, elementView ) {

			var params   = jQuery.extend( true, {}, modelData.params ),
				ajaxData = {};

			if ( 'undefined' !== typeof name && ! args.skip ) {
				params[ name ] = value;
			}
			ajaxData.params = jQuery.extend( true, {}, window.fusionAllElements[ modelData.element_type ].defaults, _.fusionCleanParameters( params ) );

			jQuery.ajax( {
				url: fusionAppConfig.ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: action,
					model: ajaxData,
					fusion_load_nonce: fusionAppConfig.fusion_load_nonce
				},
				success: function( response ) {
					if ( 'undefined' === typeof model ) {
						model = FusionPageBuilderElements.find( function( scopedModel ) {
							return scopedModel.get( 'cid' ) == cid; // jshint ignore: line
						} );
					}

					// This changes actual model.
					if ( 'undefined' !== typeof name && ! args.skip ) {
						elementView.changeParam( name, value );
					}

					if ( 'image_id' === name && 'undefined' !== typeof response.image_data && 'undefined' !== typeof response.image_data.url && ! args.skip ) {
						elementView.changeParam( 'image', response.image_data.url );
					}

					model.set( 'query_data', response );

					if ( 'generated_element' !== model.get( 'type' ) ) {
						if ( 'undefined' == typeof elementView ) {
							elementView = FusionPageBuilderViewManager.getView( cid );
						}

						if ( 'undefined' !== typeof elementView ) {
							elementView.reRender();
						}
					}
				}
			} );
		},

		fusion_do_shortcode: function( cid, content, parent, ajaxShortcodes ) {

			jQuery.ajax( {
				url: fusionAppConfig.ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'get_shortcode_render',
					content: content,
					shortcodes: 'undefined' !== typeof ajaxShortcodes ? ajaxShortcodes : '',
					fusion_load_nonce: fusionAppConfig.fusion_load_nonce,
					cid: cid
				},
				success: function( response ) {
					var markup = {},
						modelcid = cid,
						model,
						view;

					if ( 'undefined' !== typeof parent && parent ) {
						modelcid = parent;
					}

					model = FusionPageBuilderElements.find( function( scopedModel ) {
						return scopedModel.get( 'cid' ) == modelcid; // jshint ignore: line
					} );

					view = FusionPageBuilderViewManager.getView( modelcid );

					markup.output = FusionPageBuilderApp.addPlaceholder( content, response.content );

					if ( view && 'function' === typeof view.filterOutput ) {
						markup.output = view.filterOutput( markup.output );
					}

					markup.shortcode = content;

					if ( model ) {
						model.set( 'markup', markup );
					}

					// If multi shortcodes, add each.
					if ( 'object' === typeof response.shortcodes ) {
						_.each( response.shortcodes, function( output, shortcode ) {
							FusionPageBuilderApp.extraShortcodes.addShortcode( shortcode, FusionPageBuilderApp.addPlaceholder( shortcode, output ) );
						} );
					}

					if ( 'undefined' !== typeof view ) {
						view.reRender( 'ajax' );
					}

					if ( FusionPageBuilderApp.viewsToRerender ) {
						_.each( FusionPageBuilderApp.viewsToRerender, function( scopedCID ) {
							FusionPageBuilderViewManager.getView( scopedCID ).reRender( 'ajax' );
						} );

						FusionPageBuilderApp.viewsToRerender = [];
					}
				}
			} );
		},

		fusion_code_mirror: function( name, value, args, view ) {

			// Save encoded value.
			if ( ! args.skip ) {
				view.changeParam( name, value );
			}

			if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( value ) ) === value ) {
				value = FusionPageBuilderApp.base64Decode( value );
			}

			// Update with decoded value.
			view.syntaxHighlighter.getDoc().setValue( value );

			return {
				render: false
			};
		}
	} );
}( jQuery ) );
