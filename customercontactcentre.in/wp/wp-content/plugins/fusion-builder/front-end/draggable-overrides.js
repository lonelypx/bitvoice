jQuery( document ).ready( function() {
	jQuery.ui.plugin.add( 'draggable', 'iframeScroll', {
		drag: function( event, ui, i ) {
			var o              = i.options,
				scrolled       = false,
				iframe         = jQuery( '#fb-preview' ),
				iframeDocument = iframe.contents(),
				offset         = iframe.offset();

			offset.width  = iframe.width();
			offset.height = iframe.height();

			if ( 'undefined' === typeof i.scrollTop ) {
				i.scrollTop = iframeDocument.scrollTop();
			}

			//Check scroll top
			if ( o.scrollSensitivity > event.clientY ) {
				scrolled = iframeDocument.scrollTop( i.scrollTop - o.scrollSpeed );
				i.scrollTop = i.scrollTop - o.scrollSpeed;
			} else if ( o.scrollSensitivity > offset.height - event.clientY - 15 ) {
				scrolled    = iframeDocument.scrollTop( i.scrollTop + o.scrollSpeed );
				i.scrollTop = i.scrollTop + o.scrollSpeed;
			}

			//Check scroll left
			if ( offset.left < event.pageX && event.pageX < offset.left + o.scrollSensitivity ) {
				if ( offset.top < event.pageY && event.pageY < offset.top + offset.height ) {
					scrolled = iframeDocument.scrollLeft( iframeDocument.scrollLeft() - o.scrollSpeed );
				}
			}

			//Check scroll right
			if ( ( offset.left + offset.width - o.scrollSensitivity ) < event.pageX && event.pageX < offset.left + offset.width ) {
				if ( offset.top < event.pageY && event.pageY < offset.top + offset.height ) {
					scrolled = iframeDocument.scrollLeft( iframeDocument.scrollLeft() + o.scrollSpeed );
				}
			}

			if ( false !== scrolled && jQuery.ui.ddmanager && ! o.dropBehaviour ) {
				jQuery.ui.ddmanager.prepareOffsets( i, event );
			}

			clearTimeout( i.scrollTimer );
			if ( i._mouseStarted ) {
				i.scrollTimer = setTimeout( function() {
					i._trigger( 'drag', event );
					if ( jQuery.ui.ddmanager ) {
						jQuery.ui.ddmanager.drag( i, event );
					}
				}, 10 );
			}
		},
		stop: function( event, ui, i ) {
			clearInterval( i.scrollTimer );
		}
	} );
} );
