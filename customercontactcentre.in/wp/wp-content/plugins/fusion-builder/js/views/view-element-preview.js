/* global fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Builder Element Preview View
		FusionPageBuilder.ElementPreviewView = window.wp.Backbone.View.extend( {

			className: 'fusion_module_block_preview ',

			initialize: function() {
				if ( jQuery( '#' + fusionAllElements[ this.model.attributes.element_type ].preview_id ).length ) {
					this.template = FusionPageBuilder.template( jQuery( '#' + fusionAllElements[ this.model.attributes.element_type ].preview_id ).html() );
				} else {
					this.template = FusionPageBuilder.template( jQuery( '#fusion-builder-block-module-default-preview-template' ).html() );
				}
			},

			render: function() {
				this.$el.html( this.template( this.model.attributes ) );

				return this;
			}
		} );
	} );
}( jQuery ) );
