<?php
/**
 * Sidebars Metabox options.
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
 * Sidebars page settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_sidebars( $sections ) {
	global $wp_registered_sidebars;

	$sections['sidebars'] = [
		'label'    => esc_html__( 'Sidebars', 'Avada' ),
		'id'       => 'sidebars',
		'alt_icon' => 'fusiona-sidebar',
	];

	$post_type          = get_post_type();
	$sidebar_post_types = [
		'page'            => [
			'global'   => 'pages_global_sidebar',
			'sidebar'  => 'pages_sidebar',
			'position' => 'default_sidebar_pos',
		],
		'post'            => [
			'global'   => 'posts_global_sidebar',
			'sidebar'  => 'posts_sidebar',
			'position' => 'blog_sidebar_position',
		],
		'avada_faq'       => [
			'global'    => 'posts_global_sidebar',
			'sidebar'   => 'posts_sidebar',
			'sidebar_2' => 'posts_sidebar_2',
			'position'  => 'blog_sidebar_position',
		],
		'avada_portfolio' => [
			'global'   => 'portfolio_global_sidebar',
			'sidebar'  => 'portfolio_sidebar',
			'position' => 'portfolio_sidebar_position',
		],
		'product'         => [
			'global'   => 'woo_global_sidebar',
			'sidebar'  => 'woo_sidebar',
			'position' => 'woo_sidebar_position',
		],
		'tribe_events'    => [
			'global'   => 'ec_global_sidebar',
			'sidebar'  => 'ec_sidebar',
			'position' => 'ec_sidebar_pos',
		],
		'forum'           => [
			'global'   => 'bbpress_global_sidebar',
			'sidebar'  => 'ppbress_sidebar',
			'position' => 'bbpress_sidebar_position',
		],
		'topic'           => [
			'global'   => 'bbpress_global_sidebar',
			'sidebar'  => 'ppbress_sidebar',
			'position' => 'bbpress_sidebar_position',
		],
		'reply'           => [
			'global'   => 'bbpress_global_sidebar',
			'sidebar'  => 'ppbress_sidebar',
			'position' => 'bbpress_sidebar_position',
		],
	];
	$post_type_options  = '';
	if ( isset( $sidebar_post_types[ $post_type ] ) ) {
		$post_type_options = $sidebar_post_types[ $post_type ];
	}

	$sidebars_update_callback = [
		[
			'where'     => 'postMeta',
			'condition' => '_wp_page_template',
			'operator'  => '!==',
			'value'     => '100-width.php',
		],
	];

	if ( ! isset( $post_type_options['global'] ) || ( isset( $post_type_options['global'] ) && '1' !== Avada()->settings->get( $post_type_options['global'] ) ) ) {
		if ( is_admin() ) {
			// If page options.
			sidebar_generator::edit_form( $post_type_options );
		} else {
			// If in builder.
			$sidebar_choices = [
				'' => esc_html__( 'No Sidebar', 'Avada' ),
			];

			if ( isset( $post_type_options['sidebar'] ) ) {
				$sidebar_choices['default_sidebar'] = esc_html__( 'Default', 'Avada' ) . ' (' . esc_html( Avada()->settings->get( $post_type_options['sidebar'] ) ) . ')';
			}

			$sidebars = $wp_registered_sidebars;

			if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
				foreach ( $sidebars as $sidebar ) {
					$sidebar_choices[ $sidebar['name'] ] = esc_html( $sidebar['name'] );
				}
			}

			if ( isset( $_GET['builder'] ) && isset( $_GET['builder_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$sections['sidebars']['fields']['sidebars_important_note'] = [
					'id'          => 'sidebars_important_note',
					'label'       => '',
					'description' => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> Sidebars cannot be assigned to this page because it is currently set to use the 100% width page template. To change this, go to the Settings tab and change the page template to default.', 'Avada' ) . '</div>',
					'type'        => 'custom',
					'dependency'  => [
						[
							'field'      => '_wp_page_template',
							'comparison' => '==',
							'value'      => '100-width.php',
						],
					],
				];
			}
			$sections['sidebars']['fields']['sidebar_1'] = [
				'id'              => 'sidebar_1',
				'label'           => esc_html__( 'Select Sidebar 1', 'Avada' ),
				'description'     => esc_html__( 'Select sidebar 1 that will display on this page. Choose "No Sidebar" for full width.', 'Avada' ),
				'dependency'      => [],
				'type'            => 'select',
				'choices'         => $sidebar_choices,
				'default'         => '',
				'update_callback' => $sidebars_update_callback,
				'dependency'      => [
					[
						'field'      => '_wp_page_template',
						'comparison' => '!=',
						'value'      => '100-width.php',
					],
				],
			];
			$sections['sidebars']['fields']['sidebar_2'] = [
				'id'              => 'sidebar_2',
				'label'           => esc_html__( 'Select Sidebar 2', 'Avada' ),
				'description'     => esc_html__( 'Select sidebar 2 that will display on this page. Choose "No Sidebar" for full width.', 'Avada' ),
				'dependency'      => [
					[
						'field'      => 'sidebar_1',
						'value'      => '',
						'comparison' => '!=',
					],
				],
				'type'            => 'select',
				'choices'         => $sidebar_choices,
				'default'         => '',
				'update_callback' => $sidebars_update_callback,
				'dependency'      => [
					[
						'field'      => '_wp_page_template',
						'comparison' => '!=',
						'value'      => '100-width.php',
					],
				],
			];
		}
		$sections['sidebars']['fields']['sidebar_position'] = [
			'id'              => 'sidebar_position',
			'label'           => esc_attr__( 'Sidebar 1 Position', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description'     => sprintf( esc_html__( 'Select the sidebar 1 position. If sidebar 2 is selected, it will display on the opposite side. %s', 'Avada' ), ( ! empty( $post_type_options ) ) ? Avada()->settings->get_default_description( $post_type_options['position'], '', 'select' ) : '' ),
			'to_default'      => [
				'id' => 'default_sidebar_pos',
			],
			'dependency'      => [
				[
					'field'      => '_wp_page_template',
					'comparison' => '!=',
					'value'      => '100-width.php',
				],
				[
					'field'      => 'sidebar_1',
					'value'      => '',
					'comparison' => '!=',
				],
			],
			'type'            => 'radio-buttonset',
			'choices'         => [
				'default' => esc_attr__( 'Default', 'Avada' ),
				'left'    => esc_attr__( 'Left', 'Avada' ),
				'right'   => esc_attr__( 'Right', 'Avada' ),
			],
			'default'         => 'default',
			'update_callback' => $sidebars_update_callback,
		];

		$sidebar_order = Avada()->settings->get( 'responsive_sidebar_order' );
		$sections['sidebars']['fields']['responsive_sidebar_order'] = [
			'id'              => 'responsive_sidebar_order',
			'label'           => esc_attr__( 'Responsive Sidebar Order', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description'     => sprintf( esc_html__( 'Choose the order of sidebars and main content area on mobile layouts through drag & drop sorting. %s', 'Avada' ), Avada()->settings->get_default_description( 'responsive_sidebar_order', '', 'sortable', 'responsive_sidebar_order' ) ),
			'to_default'      => [
				'id' => 'responsive_sidebar_order',
			],
			'type'            => 'sortable',
			'default'         => $sidebar_order,
			'choices'         => [
				'content'   => esc_html__( 'Content', 'Avada' ),
				'sidebar'   => esc_html__( 'Sidebar 1', 'Avada' ),
				'sidebar-2' => esc_html__( 'Sidebar 2', 'Avada' ),
			],
			'update_callback' => $sidebars_update_callback,
			'dependency'      => [
				[
					'field'      => '_wp_page_template',
					'comparison' => '!=',
					'value'      => '100-width.php',
				],
			],
		];

		$sections['sidebars']['fields']['sidebar_sticky'] = [
			'id'              => 'sidebar_sticky',
			'label'           => esc_attr__( 'Sticky Sidebars', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description'     => sprintf( esc_html__( 'Select the sidebar(s) that should remain sticky when scrolling the page. If the sidebar content is taller than the screen, it acts like a normal sidebar until the bottom of the sidebar is within the viewport, which will then remain fixed in place as you scroll down. %s', 'Avada' ), Avada()->settings->get_default_description( 'sidebar_sticky', '', 'select' ) ),
			'to_default'      => [
				'id' => 'sidebar_sticky',
			],
			'dependency'      => [
				[
					'field'      => '_wp_page_template',
					'comparison' => '!=',
					'value'      => '100-width.php',
				],
				[
					'field'      => 'sidebar_1',
					'value'      => '',
					'comparison' => '!=',
				],
			],
			'type'            => 'select',
			'choices'         => [
				'default'     => esc_attr__( 'Default', 'Avada' ),
				'none'        => esc_attr__( 'None', 'Avada' ),
				'sidebar_one' => esc_attr__( 'Sidebar 1', 'Avada' ),
				'sidebar_two' => esc_attr__( 'Sidebar 2', 'Avada' ),
				'both'        => esc_attr__( 'Both', 'Avada' ),
			],
			'default'         => 'default',
			'update_callback' => $sidebars_update_callback,
		];

		$ec_sidebar_bg_color = Fusion_Color::new_color(
			[
				'color'    => Avada()->settings->get( 'ec_sidebar_bg_color' ),
				'fallback' => '#f6f6f6',
			]
		);
		$ec_sidebar_bg_color = $ec_sidebar_bg_color->color;
		$sidebar_bg_color    = Fusion_Color::new_color(
			[
				'color'    => Avada()->settings->get( 'sidebar_bg_color' ),
				'fallback' => 'rgba(255,255,255,0)',
			]
		);
		$sidebar_bg_color    = $sidebar_bg_color->color;

		$sections['sidebars']['fields']['sidebar_bg_color'] = [
			'id'              => 'sidebar_bg_color',
			'label'           => esc_attr__( 'Sidebar Background Color', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description'     => sprintf( esc_html__( 'Controls the background color of the sidebar. Hex code, ex: #000. %s', 'Avada' ), ( 'tribe_events' === $post_type ) ? Avada()->settings->get_default_description( 'ec_sidebar_bg_color' ) : Avada()->settings->get_default_description( 'sidebar_bg_color' ) ),
			'to_default'      => [
				'id' => ( 'tribe_events' === $post_type ) ? 'ec_sidebar_bg_color' : 'sidebar_bg_color',
			],
			'dependency'      => [
				[
					'field'      => '_wp_page_template',
					'comparison' => '!=',
					'value'      => '100-width.php',
				],
				[
					'field'      => 'sidebar_1',
					'value'      => '',
					'comparison' => '!=',
				],
			],
			'type'            => 'color-alpha',
			'default'         => ( 'tribe_events' === $post_type ) ? $ec_sidebar_bg_color : $sidebar_bg_color,
			'update_callback' => $sidebars_update_callback,
		];

	} else {
		/* translators: Additional description (defaults). */
		$message = isset( $post_type_options['global'] ) ? sprintf( __( '<strong>IMPORTANT NOTE:</strong> The <a href="%s" target="_blank">Activate Global Sidebars</a> option is turned on which removes the ability to choose individual sidebars. Turn off that option to assign unique sidebars.', 'Avada' ), Avada()->settings->get_setting_link( $post_type_options['global'] ) ) : __( '<strong>IMPORTANT NOTE:</strong> The Activate Global Sidebars option is turned on which removes the ability to choose individual sidebars. Turn off that option to assign unique sidebars.', 'Avada' );

		$sections['sidebars']['fields']['sidebar_global_to_enabled'] = [
			'id'              => 'sidebar_global_to_enabled',
			'label'           => '',
			'description'     => '<div class="fusion-redux-important-notice">' . $message . '</div>',
			'dependency'      => [],
			'type'            => 'custom',
			'update_callback' => $sidebars_update_callback,
		];
	}

	return $sections;
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
