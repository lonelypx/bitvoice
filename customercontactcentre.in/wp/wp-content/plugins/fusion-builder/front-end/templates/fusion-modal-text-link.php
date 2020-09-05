<?php
/**
 * Underscore.js template
 *
 * @package fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-fusion_modal_text_link-shortcode">
	<# if ( 'undefined' === typeof output || '' === output ) { #>
	<div class="fusion-builder-placeholder-preview">
		<i class="{{ icon }}"></i> {{ label }} ({{ name }})
	</div>
	<# } else { #>
	<a {{{ _.fusionGetAttributes( modalTextShortcode ) }}}>{{{ FusionPageBuilderApp.renderContent( output, cid, false ) }}}</a>
	<# } #>
</script>
