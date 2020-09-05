<?php
/**
 * Underscore.js template.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<# if ( 'undefined' !== typeof FusionApp ) { #>
<div class="fusion-upload-file fusion-upload-area" data-mode="file">
	<input id="{{ param.param_name }}" name="{{ param.param_name }}" type="text" class="regular-text fusion-builder-upload-field fusion-url-only-input" value="{{ option_value }}" />
	<a href="JavaScript:void(0);" class="upload-image-remove"><span class="fusiona-close-fb"></span></a>
	<button class='button button-upload fusion-builder-upload-button' data-type="video" data-title="{{ fusionBuilderText.select_video }}"><i class="fusiona-plus"></i></button>
</div>
<# } else { #>
<div class="fusion-upload-file">
	<input id="{{ param.param_name }}" name="{{ param.param_name }}" type="text" class="regular-text fusion-builder-upload-field" value="{{ option_value }}" />
	<input type='button' class='button button-upload fusion-builder-upload-button' value='{{ fusionBuilderText.upload }}' data-type="video" data-title="{{ fusionBuilderText.select_video }}"/>
</div>
<# } #>
