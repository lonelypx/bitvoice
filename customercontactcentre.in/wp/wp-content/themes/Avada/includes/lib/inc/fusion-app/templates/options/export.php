<?php
/**
 * Underscore.js template.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<#
var fieldId     = 'undefined' === typeof param.param_name ? param.id : param.param_name,
	export_text = 'undefined' === typeof param.text ? fusionBuilderText.upload_image : param.text,
	context     = 'undefined' === typeof param.context ? 'TO' : param.context,
	data        = 'TO' === context ? JSON.stringify( FusionApp.settings ) : JSON.stringify( FusionApp.data.postMeta );
#>
<div class="fusion-form-radio-button-set ui-buttonset fusion-export-mode">
	<input type="hidden" id="fusion-export-mode" name="fusion-export-mode" value="copy" class="fusion-dont-update button-set-value" />
	<a href="#" class="ui-button buttonset-item ui-state-active" data-value="copy" aria-label="<?php esc_attr_e( 'Code', 'Avada' ); ?>"><?php esc_attr_e( 'Code', 'Avada' ); ?></a>
	<a href="#" class="ui-button buttonset-item" data-value="download" aria-label="<?php esc_attr_e( 'File Download', 'Avada' ); ?>"><?php esc_attr_e( 'File Download', 'Avada' ); ?></a>
</div>

<div class="fusion-export-options">

	<div data-id="copy" class="fusion-copy-export active">
		<textarea id="export-code-value" rows="5" class="fusion-dont-update" data-context="{{ context }}">{{ data }}</textarea>
		<input type='button' id="fusion-export-copy" class='button fusion-builder-export-button' value='<?php esc_attr_e( 'Copy to Clipboard', 'Avada' ); ?>' />
	</div>

	<div data-id="download" class="fusion-export">
		<p><?php esc_attr_e( 'Click the export button to export your current set of options as a json file.', 'Avada' ); ?></p>
		<input type='button' id="fusion-export-file" class='button fusion-builder-export-button' value='<?php esc_attr_e( 'Export', 'Avada' ); ?>' data-context="{{ context }}" />
	</div>
</div>
