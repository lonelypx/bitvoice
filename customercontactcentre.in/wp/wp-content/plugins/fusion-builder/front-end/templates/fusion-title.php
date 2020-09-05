<?php
/**
 * Underscore.js template
 *
 * @package fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-fusion_title-shortcode">
{{{ style }}}
<# if ( -1 !== style_type.indexOf( 'underline' ) || -1 !== style_type.indexOf( 'none' ) ) { #>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<h{{ size }} {{{ _.fusionGetAttributes( headingAttr ) }}}>
		{{{ FusionPageBuilderApp.renderContent( output, cid, false ) }}}
	</h{{ size }}>
</div>
<# } else { #>
	<# if ( 'right' == content_align ) { #>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="title-sep-container">
		<div {{{ _.fusionGetAttributes( separatorAttr ) }}}></div>
	</div>
	<h{{ size }} {{{ _.fusionGetAttributes( headingAttr ) }}}>
		{{{ FusionPageBuilderApp.renderContent( output, cid, false ) }}}
	</h{{ size }}>
</div>
	<# } else if ( 'center' == content_align ) { #>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="title-sep-container title-sep-container-left">
		<div {{{ _.fusionGetAttributes( separatorAttr ) }}}></div>
	</div>
	<h{{ size }} {{{ _.fusionGetAttributes( headingAttr ) }}}>
		{{{ FusionPageBuilderApp.renderContent( output, cid, false ) }}}
	</h{{ size }}>
	<div class="title-sep-container title-sep-container-right">
		<div {{{ _.fusionGetAttributes( separatorAttr ) }}}></div>
	</div>
</div>
	<# } else { #>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<h{{ size }} {{{ _.fusionGetAttributes( headingAttr ) }}}>
		{{{ FusionPageBuilderApp.renderContent( output, cid, false ) }}}
	</h{{ size }}>
	<div class="title-sep-container">
		<div {{{ _.fusionGetAttributes( separatorAttr ) }}}></div>
	</div>
</div>
	<# } #>
<# } #>
</script>
