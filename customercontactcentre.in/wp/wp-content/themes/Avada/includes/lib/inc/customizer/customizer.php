<?php
/**
 * WIP for the customizer.
 *
 * @package Fusion-Library
 * @since 2.0
 */

/**
 * Alter the array of standard fonts.
 *
 * @return array Standard websafe fonts.
 */
add_filter(
	'kirki/fonts/standard_fonts',
	function() {
		$final_fonts = [];
		$fonts       = [
			'Arial, Helvetica, sans-serif',
			"'Arial Black', Gadget, sans-serif",
			"'Bookman Old Style', serif",
			"'Comic Sans MS', cursive",
			'Courier, monospace',
			'Garamond, serif',
			'Georgia, serif',
			'Impact, Charcoal, sans-serif',
			"'Lucida Console', Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
			'Tahoma,Geneva, sans-serif',
			"'Times New Roman', Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif",
			'Verdana, Geneva, sans-serif',
		];

		foreach ( $fonts as $font ) { // phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact
			$final_fonts[ $font ] = [
				'label' => $font,
				'stack' => $font,
			];
		} // phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact
		return $final_fonts;
	}
);

/**
 * Sanitize callback for switches & toggles (compatibility with Redux).
 *
 * @since 2.0.0
 * @param bool|int|string $value The value.
 * @return string
 */
function fusion_customizer_sanitize_bool_string( $value ) {
	if ( true === $value || 1 === $value || '1' === $value || 'yes' === $value || 'true' === $value ) {
		return '1';
	}
	return '0';
}

/**
 * Sanitize callback for repeaters (redux compatibility).
 *
 * @since 2.0.0
 * @param array $value The value.
 * @return array       The converted value.
 */
function fusion_customizer_sanitize_repeater( $value ) {

	$value = maybe_unserialize( $value );
	if ( ! is_array( $value ) ) {
		return [];
	}

	$newval = [];
	if ( isset( $value[0] ) ) {
		foreach ( $value as $key => $val ) {
			foreach ( $val as $val_key => $val_val ) {
				if ( ! isset( $newval[ $val_key ] ) ) {
					$newval[ $val_key ] = [];
				}
				$newval[ $val_key ][ $key ]                  = $val_val;
				$newval['fusionredux_repeater_data'][ $key ] = [ 'title' => '' ];
			}
		}
		return $newval;
	}
	return $value;
}

/**
 * Reverse sanitization for repeater fields.
 * This takes the value saved in a redux-compatible format
 * and converts it to something the Customizer controls can understand.
 *
 * @since 2.0.0
 * @param array $value The value.
 * @return array       The converted value.
 */
function fusion_customizer_reverse_sanitize_repeater( $value ) {
	if ( ! is_array( $value ) ) {
		return [];
	}
	$newval = [];
	if ( isset( $value['fusionredux_repeater_data'] ) ) {
		foreach ( $value['fusionredux_repeater_data'] as $k => $v ) {
			$newval[ $k ] = [];
			foreach ( $value as $key => $values ) {
				if ( isset( $values[ $k ] ) && 'fusionredux_repeater_data' !== $key ) {
					$newval[ $k ][ $key ] = $values[ $k ];
				}
			}
		}
		return $newval;
	}
	return $value;
}

// Add filters.
add_filter( 'kirki/controls/repeater/value/' . Fusion_Settings::get_option_name() . '[social_media_icons]', 'fusion_customizer_reverse_sanitize_repeater' );
add_filter( 'kirki/controls/repeater/value/' . Fusion_Settings::get_option_name() . '[custom_fonts]', 'fusion_customizer_reverse_sanitize_repeater' );

require_once dirname( __FILE__ ) . '/class-fusion-customizer.php';
global $fusion_customizer;
$fusion_customizer = new Fusion_Customizer();
