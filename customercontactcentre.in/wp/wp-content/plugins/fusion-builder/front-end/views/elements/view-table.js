/* eslint no-loop-func: 0 */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Table Element View.
		FusionPageBuilder.fusion_table = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs before view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforePatch: function() {
				var params = this.model.get( 'params' ),
					content,
					columnsNew,
					styleNew,
					styleOld,
					tableDOM,
					columnsOld;

				content    = 'undefined' === typeof this.$el.find( '[data-param="element_content"]' ).html() ? params.element_content : this.$el.find( '[data-param="element_content"]' ).html();

				tableDOM   = jQuery.parseHTML( content.trim() );
				columnsOld = jQuery( tableDOM ).find( 'th' ).length;
				columnsNew = parseInt( params.fusion_table_columns, 10 );
				styleOld   = jQuery( tableDOM ).attr( 'class' ).replace( /[^\d.]/g, '' );
				styleNew   = params.fusion_table_type;

				if ( columnsNew !== columnsOld || styleOld !== styleNew ) {
					tableDOM = this.generateTable( tableDOM, columnsNew, columnsOld );
					window.FusionPageBuilderApp.setContent( 'element_content', jQuery( tableDOM ).prop( 'outerHTML' ) );
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
				var attributes       = {},
					values           = atts.params,
					tableElementAtts = this.buildAttr( values ),
					tableDOM,
					columnsNew,
					columnsOld;

				if ( 'undefined' !== typeof values.fusion_table_type && '' !== values.fusion_table_type ) {
					values.element_content = values.element_content.replace( /<div class="table-\d">/g, '<div ' + _.fusionGetAttributes( tableElementAtts ) + '>' );
				}

				tableDOM = jQuery.parseHTML( values.element_content.trim() );
				columnsOld = jQuery( tableDOM ).find( 'th' ).length;

				if ( 'undefined' !== typeof values.fusion_table_columns && '' !== values.fusion_table_columns ) {
					columnsNew = parseInt( values.fusion_table_columns, 10 );
					tableDOM = this.generateTable( tableDOM, columnsNew, columnsOld );

					values.element_content = jQuery( tableDOM ).prop( 'outerHTML' );
				}

				// Any extras that need passed on.
				attributes.cid             = this.model.get( 'cid' );
				attributes.element_content = values.element_content;

				return attributes;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values object.
			 * @return {Object}
			 */
			buildAttr: function( values ) {
				var attr = {},
					tableStyle;

				if ( 'undefined' !== typeof values.fusion_table_type && '' !== values.fusion_table_type ) {
					tableStyle = values.element_content.charAt( 19 );

					if ( ( '1' === tableStyle || '2' === tableStyle ) && tableStyle !==  values.fusion_table_type ) {
						values.fusion_table_type = tableStyle;
					}

					attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'table-' + values.fusion_table_type
					} );

					attr = _.fusionAnimations( values, attr );

					if ( '' !== values[ 'class' ] ) {
						attr[ 'class' ] += ' ' + values[ 'class' ];
					}

					if ( '' !== values.id ) {
						attr.id = values.id;
					}
				}

				return attr;
			},

			/**
			 * Generates table HTML.
			 *
			 * @since 2.0.0
			 * @param {string} tableDOM   - The existing DOM.
			 * @param {string} columnsNew - New number of columns.
			 * @param {string} columnsOld - Old number of columns.
			 * @return {string}
			 */
			generateTable: function( tableDOM, columnsNew, columnsOld ) {
				var i;
				if ( columnsNew > columnsOld ) {
					for ( i = columnsOld + 1; i <= columnsNew; i++ ) {
						jQuery( tableDOM ).find( 'thead tr' ).append( '<th align="left">Column ' + i + '</th>' );
						jQuery( tableDOM ).find( 'tbody tr' ).each( function() {
							jQuery( this ).append( '<td align="left">Column ' + i + ' Value</td>' );
						} );
					}

				} else if ( columnsNew < columnsOld ) {
					for ( i = columnsNew + 1; i <= columnsOld; i++ ) {
						jQuery( tableDOM ).find( 'thead th' ).last().remove();
						jQuery( tableDOM ).find( 'tbody tr' ).each( function() {
							jQuery( this ).find( 'td' ).last().remove();
						} );
					}
				}

				return tableDOM;
			}
		} );
	} );
}( jQuery ) );
