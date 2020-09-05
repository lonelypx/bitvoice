<?php
/**
 * Underscore.js template.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<div class="fusion-iconpicker">
	<# if ( 'undefined' !== typeof FusionApp ) { #>
		<input type="hidden" class="fusion-iconpicker-input" value="{{ option_value }}" id="{{ param.param_name }}" name="{{ param.param_name }}"/>
		<div class="fusion-iconpicker-preview">
			<input type="text" class="fusion-icon-search fusion-hide-from-atts fusion-dont-update" placeholder="{{ fusionBuilderText.search_icons }}" />
			<span class="input-icon fa-search fas"></span>
		</div>
		<div class="fusion-iconselect-wrapper">
			<div class="icon_select_container"></div>
		</div>
	<# } else { #>
			<input type="text" class="fusion-icon-search" placeholder="{{ fusionBuilderText.search_icons }}" />
			<div class='icon_select_container'></div>
			<input type="hidden" class="fusion-iconpicker-input" value="{{ option_value }}" id="{{ param.param_name }}" name="{{ param.param_name }}"/>
	<# } #>
</div>
