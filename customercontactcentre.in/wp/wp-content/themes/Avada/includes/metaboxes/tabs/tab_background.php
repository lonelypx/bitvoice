<?php
/**
 * Background Metabox options.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Background page settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_background( $sections ) {

	// Dependency check for boxed mode.
	$boxed_dependency = [];

	$page_bg_color = Fusion_Color::new_color(
		[
			'color'    => Avada()->settings->get( 'bg_color' ),
			'fallback' => '#ffffff',
		]
	);

	// Also add check for background image.
	$boxed_dependency_new   = $boxed_dependency;
	$boxed_dependency_new[] = [
		'field'      => 'page_bg',
		'value'      => '',
		'comparison' => '!=',
	];

	// Dependency check for wide mode.
	$wide_dependency = [];

	$content_bg_color = Fusion_Color::new_color(
		[
			'color'    => Avada()->settings->get( 'content_bg_color' ),
			'fallback' => '#ffffff',
		]
	);

	// Also add check for background image.
	$wide_dependency_new   = $wide_dependency;
	$wide_dependency_new[] = [
		'field'      => 'wide_page_bg',
		'value'      => '',
		'comparison' => '!=',
	];

	$sections['background'] = [
		'label'    => esc_html__( 'Background', 'Avada' ),
		'id'       => 'background',
		'alt_icon' => 'fusiona-image',
		'fields'   => [
			'page_bg_layout'      => [
				'id'          => 'page_bg_layout',
				'label'       => esc_attr__( 'Layout', 'Avada' ),
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'wide'    => esc_attr__( 'Wide', 'Avada' ),
					'boxed'   => esc_attr__( 'Boxed', 'Avada' ),
				],
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_attr__( 'Select boxed or wide layout. %s', 'Avada' ), Avada()->settings->get_default_description( 'layout', '', 'select' ) ),
				'to_default'  => [
					'id' => 'layout',
				],
				'dependency'  => [],
				'type'        => 'radio-buttonset',
			],
			'page_bg_color'       => [
				'id'          => 'page_bg_color',
				'label'       => esc_attr__( 'Background Color For Page', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Controls the background color for the page. When the color value is set to anything below 100&#37; opacity, the color will overlay the background image if one is uploaded. Hex code, ex: #000. %s', 'Avada' ), Avada()->settings->get_default_description( 'bg_color' ) ),
				'dependency'  => $boxed_dependency,
				'default'     => $page_bg_color->color,
				'type'        => 'color-alpha',
				'to_default'  => [
					'id' => 'bg_color',
				],
			],
			'page_bg'             => [
				'id'          => 'page_bg',
				'label'       => esc_attr__( 'Background Image For Page', 'Avada' ),
				'alpha'       => true,
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_attr__( 'Select an image to use for a full page background. %s', 'Avada' ), Avada()->settings->get_default_description( 'bg_image', 'url' ) ),
				'to_default'  => [
					'id' => 'bg_image',
				],
				'dependency'  => $boxed_dependency,
				'type'        => 'media_url',
			],
			'page_bg_full'        => [
				'id'          => 'page_bg_full',
				'label'       => esc_attr__( '100% Background Image', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to have the background image display at 100&#37;. %s', 'Avada' ), Avada()->settings->get_default_description( 'bg_full', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'bg_full',
				],
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
				],
				'dependency'  => $boxed_dependency_new,
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
				'default'     => 'no',
			],
			'page_bg_repeat'      => [
				'id'          => 'page_bg_repeat',
				'label'       => esc_attr__( 'Background Repeat', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Select how the background image repeats. %s', 'Avada' ), Avada()->settings->get_default_description( 'bg_repeat', '', 'select' ) ),
				'to_default'  => [
					'id' => 'bg_repeat',
				],
				'choices'     => [
					'default'   => esc_attr__( 'Default', 'Avada' ),
					'repeat'    => esc_attr__( 'Tile', 'Avada' ),
					'repeat-x'  => esc_attr__( 'Tile Horizontally', 'Avada' ),
					'repeat-y'  => esc_attr__( 'Tile Vertically', 'Avada' ),
					'no-repeat' => esc_attr__( 'No Repeat', 'Avada' ),
				],
				'dependency'  => $boxed_dependency_new,
				'type'        => 'select',
			],
			'wide_page_bg_color'  => [
				'id'          => 'wide_page_bg_color',
				'label'       => esc_attr__( 'Background Color for Main Content Area', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Controls the background color for the main content area. Hex code, ex: #000. %s', 'Avada' ), Avada()->settings->get_default_description( 'content_bg_color' ) ),
				'dependency'  => $wide_dependency,
				'default'     => $content_bg_color->color,
				'type'        => 'color-alpha',
				'to_default'  => [
					'id' => 'content_bg_color',
				],
			],
			'wide_page_bg'        => [
				'id'          => 'wide_page_bg',
				'label'       => esc_attr__( 'Background Image for Main Content Area', 'Avada' ),
				'alpha'       => true,
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Select an image to use for the main content area. %s', 'Avada' ), Avada()->settings->get_default_description( 'content_bg_image', 'url' ) ),
				'to_default'  => [
					'id' => 'content_bg_image',
				],
				'dependency'  => $wide_dependency,
				'type'        => 'media_url',
			],
			'wide_page_bg_full'   => [
				'id'          => 'wide_page_bg_full',
				'label'       => esc_html__( '100% Background Image', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to have the background image display at 100&#37;. %s', 'Avada' ), Avada()->settings->get_default_description( 'content_bg_full', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'content_bg_full',
				],
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
				],
				'dependency'  => $wide_dependency_new,
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
				'default'     => 'no',
			],
			'wide_page_bg_repeat' => [
				'id'          => 'wide_page_bg_repeat',
				'label'       => esc_attr__( 'Background Repeat', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Select how the background image repeats. %s', 'Avada' ), Avada()->settings->get_default_description( 'content_bg_repeat', '', 'select' ) ),
				'to_default'  => [
					'id' => 'content_bg_repeat',
				],
				'choices'     => [
					'default'   => esc_attr__( 'Default', 'Avada' ),
					'repeat'    => esc_attr__( 'Tile', 'Avada' ),
					'repeat-x'  => esc_attr__( 'Tile Horizontally', 'Avada' ),
					'repeat-y'  => esc_attr__( 'Tile Vertically', 'Avada' ),
					'no-repeat' => esc_attr__( 'No Repeat', 'Avada' ),
				],
				'dependency'  => $wide_dependency_new,
				'type'        => 'select',
			],
		],
	];
	return $sections;
}
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
