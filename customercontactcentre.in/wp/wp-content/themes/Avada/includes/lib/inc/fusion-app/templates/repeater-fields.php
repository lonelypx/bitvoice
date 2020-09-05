<?php
/**
 * The toolbar template file.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<script type="text/template" id="fusion-app-repeater-fields">
	<div class="repeater-field">
	<#
	param           = field;
	option_value    = 'undefined' !== value ? value : param.value;
	hidden          = 'undefined' !== typeof param.hidden ? ' hidden' : '';
	childDependency = 'undefined' !== typeof param.child_dependency ? ' has-child-dependency' : '';
	optionId        = 'undefined' !== typeof param.param_name ? param.param_name : param.id;
	optionTitle     = 'undefined' !== typeof param.heading ? param.heading : param.label;
	#>
	<li data-option-id="{{ optionId }}" class="fusion-builder-option {{ param.type }}{{ hidden }}{{ childDependency }}" style="display:inline-block" >
		<div class="option-details">
			<# if ( 'undefined' !== typeof optionTitle ) { #>
				<h3>{{ optionTitle }}</h3>
			<# }; #>
			<# if ( 'undefined' !== typeof param.description ) { #>
				<p class="description">{{{ param.description }}}</p>
			<# }; #>
		</div>

		<div class="option-field fusion-builder-option-container">
			<?php
			$fields = [
				'textfield',
				'colorpickeralpha',
				'select',
				'upload_object',
				'textarea',
			];

			// Redux on left, template on right.
			$field_replacement = [
				'text'   => 'textfield',
				'media'  => 'upload_object',
				'upload' => 'upload_object',
			];

			foreach ( $field_replacement as $redux => $option ) {
				$fields[] = [
					$redux,
					FUSION_LIBRARY_PATH . '/inc/fusion-app/templates/options/' . str_replace( '_', '-', $option ) . '.php',
				];
			}
			?>
			<?php
				$fields = apply_filters( 'fusion_builder_repeater_fields', $fields );
			?>
			<?php foreach ( $fields as $field_type ) : ?>
				<?php if ( is_array( $field_type ) && ! empty( $field_type ) ) : ?>
					<# if ( '<?php echo esc_attr( $field_type[0] ); ?>' === param.type ) { #>
					<?php include wp_normalize_path( $field_type[1] ); ?>
				<# }; #>
				<?php else : ?>
					<# if ( '<?php echo esc_attr( $field_type ); ?>' === param.type ) { #>
					<?php include FUSION_LIBRARY_PATH . '/inc/fusion-app/templates/options/' . str_replace( '_', '-', $field_type ) . '.php'; ?>
					<# } #>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</li>
	</div>
</script>
