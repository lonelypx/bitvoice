<?php
/**
 * Page Metabox options.
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
 * Page page settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_page( $sections ) {

	$post_type = get_post_type();

	$sections['page'] = [
		'label'    => esc_html__( 'Page', 'Avada' ),
		'id'       => 'page',
		'alt_icon' => 'fusiona-file',
		'fields'   => [
			'main_padding' => [
				'id'          => 'main_padding',
				'value'       => [
					'main_top_padding'    => '',
					'main_bottom_padding' => '',
				],
				'label'       => esc_attr__( 'Page Content Padding', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'In pixels ex: 20px. %s', 'Avada' ), Avada()->settings->get_default_description( 'main_padding', [ 'top', 'bottom' ] ) ),
				'to_default'  => [
					'id' => 'main_padding',
				],
				'dependency'  => [],
				'type'        => 'dimensions',
			],
		],
	];

	if ( 'product' === $post_type ) {
		$sections['page']['fields']['portfolio_width_100'] = [
			'id'          => 'portfolio_width_100',
			'type'        => 'radio-buttonset',
			'map'         => 'yesno',
			'label'       => esc_attr__( 'Use 100% Width Page', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description' => sprintf( esc_html__( 'Choose to set this post to 100&#37; browser width. %s', 'Avada' ), Avada()->settings->get_default_description( 'product_width_100', '', 'yesno' ) ),
			'to_default'  => [
				'id' => 'product_width_100',
			],
			'default'     => 'default',
			'dependency'  => [],
			'choices'     => [
				'default' => esc_attr__( 'Default', 'Avada' ),
				'yes'     => esc_attr__( 'Yes', 'Avada' ),
				'no'      => esc_attr__( 'No', 'Avada' ),
			],
		];
	}

	$sections['page']['fields']['hundredp_padding'] = [
		'id'          => 'hundredp_padding',
		'label'       => esc_html__( '100% Width Padding', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_html__( 'Controls the left and right padding for page content when using 100&#37; site width, 100&#37; width page template or 100&#37; width post option. This does not affect Fusion Builder containers.  Enter value including any valid CSS unit, ex: 30px. %s', 'Avada' ), Avada()->settings->get_default_description( 'hundredp_padding' ) ),
		'to_default'  => [
			'id' => 'hundredp_padding',
		],
		'dependency'  => [],
		'type'        => 'text',
	];

	if ( 'page' === $post_type ) {
		$sections['page']['fields']['show_first_featured_image'] = [
			'id'          => 'show_first_featured_image',
			'label'       => esc_attr__( 'Disable First Featured Image', 'Avada' ),
			'description' => esc_html__( 'Disable the 1st featured image on page.', 'Avada' ),
			'dependency'  => [],
			'type'        => 'radio-buttonset',
			'default'     => 'no',
			'choices'     => [
				'yes' => esc_attr__( 'Yes', 'Avada' ),
				'no'  => esc_attr__( 'No', 'Avada' ),
			],
		];
	}

	if ( 'tribe_events' === $post_type ) {
		$sections['page']['fields']['share_box'] = [
			'id'          => 'share_box',
			'label'       => esc_attr__( 'Show Social Share Box', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description' => sprintf( esc_html__( 'Choose to show or hide the social share box. %s', 'Avada' ), Avada()->settings->get_default_description( 'events_social_sharing_box', '', 'showhide' ) ),
			'to_default'  => [
				'id' => 'events_social_sharing_box',
			],
			'dependency'  => [],
			'type'        => 'radio-buttonset',
			'map'         => 'showhide',
			'default'     => 'default',
			'choices'     => [
				'default' => esc_attr__( 'Default', 'Avada' ),
				'yes'     => esc_attr__( 'Show', 'Avada' ),
				'no'      => esc_attr__( 'Hide', 'Avada' ),
			],
		];
	}

	return $sections;
}
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
