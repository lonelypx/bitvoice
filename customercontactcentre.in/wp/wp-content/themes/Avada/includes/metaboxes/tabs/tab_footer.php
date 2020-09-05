<?php
/**
 * Footer Metabox options.
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
 * Footer page settings.
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_footer( $sections ) {
	$sections['footer'] = [
		'label'    => esc_attr__( 'Footer', 'Avada' ),
		'id'       => 'footer',
		'alt_icon' => 'fusiona-footer',
		'fields'   => [
			'display_footer'    => [
				'id'              => 'display_footer',
				'label'           => esc_attr__( 'Layout', 'Avada' ),
				'choices'         => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				/* translators: Additional description (defaults). */
				'description'     => sprintf( esc_html__( 'Choose to show or hide the footer. %s', 'Avada' ), Avada()->settings->get_default_description( 'footer_widgets', '', 'yesno' ) ),
				'to_default'      => [
					'id' => 'footer_widgets',
				],
				'type'            => 'radio-buttonset',
				'map'             => 'yesno',
				'default'         => 'default',
				'transport'       => 'postMessage',
				'partial_refresh' => [
					'footer_content_footer_widgets' => [
						'selector'            => '.fusion-footer',
						'container_inclusive' => false,
						'render_callback'     => [ 'Avada_Partial_Refresh_Callbacks', 'footer' ],
					],
				],
			],
			'display_copyright' => [
				'id'              => 'display_copyright',
				'label'           => esc_attr__( 'Display Copyright Area', 'Avada' ),
				'choices'         => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				/* translators: Additional description (defaults). */
				'description'     => sprintf( esc_html__( 'Choose to show or hide the copyright area. %s', 'Avada' ), Avada()->settings->get_default_description( 'footer_copyright', '', 'yesno' ) ),
				'to_default'      => [
					'id' => 'footer_copyright',
				],
				'type'            => 'radio-buttonset',
				'map'             => 'yesno',
				'default'         => 'default',
				'transport'       => 'postMessage',
				'partial_refresh' => [
					'footer_content_footer_widgets' => [
						'selector'            => '.fusion-footer',
						'container_inclusive' => false,
						'render_callback'     => [ 'Avada_Partial_Refresh_Callbacks', 'footer' ],
					],
				],
			],
			'footer_100_width'  => [
				'id'          => 'footer_100_width',
				'label'       => esc_html__( '100% Footer Width', 'Avada' ),
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to set footer width to 100&#37; of the browser width. Select "No" for site width. %s', 'Avada' ), Avada()->settings->get_default_description( 'footer_100_width', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'footer_100_width',
				],
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
				'default'     => 'default',
			],
		],
	];
	return $sections;
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
