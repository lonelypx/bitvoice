<?php
/**
 * Underscore.js template
 *
 * @package fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-fusion_counters_box-shortcode">
	<div {{{ _.fusionGetAttributes( countersBoxShortcode ) }}}></div>
	<div class="clearfix"></div>
</script>
<script type="text/html" id="tmpl-fusion_counter_box-shortcode">
	<div {{{ _.fusionGetAttributes( counterBoxContainer ) }}}>
		{{{ counterWrapper }}}
		<div {{{ _.fusionGetAttributes( counterBoxShortcodeContent ) }}}>
			{{{ FusionPageBuilderApp.renderContent( output, cid, parent ) }}}
		</div>
	</div>
</script>
