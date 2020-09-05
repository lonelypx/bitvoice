<?php
/**
 * Avada Options.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      4.0.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Page Title Bar
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_options_section_page_title_bar( $sections ) {

	$settings = get_option( Avada::get_option_name(), [] );

	$sections['page_title_bar'] = [
		'label'    => esc_html__( 'Page Title Bar', 'Avada' ),
		'id'       => 'heading_page_title_bar',
		'priority' => 7,
		'icon'     => 'el-icon-adjust-alt',
		'alt_icon' => 'fusiona-page_title',
		'class'    => 'hidden-section-heading',
		'fields'   => [
			'heading_page_title_bar_info_1'  => [
				'label'  => esc_html__( 'Page Title Bar', 'Avada' ),
				'id'     => 'heading_page_title_bar_info_1',
				'icon'   => true,
				'type'   => 'sub-section',
				'fields' => [
					'page_title_bar'                 => [
						'label'           => esc_html__( 'Page Title Bar', 'Avada' ),
						'description'     => esc_html__( 'Controls how the page title bar displays.', 'Avada' ),
						'id'              => 'page_title_bar',
						'default'         => 'bar_and_content',
						'choices'         => [
							'bar_and_content' => esc_html__( 'Show Bar and Content', 'Avada' ),
							'content_only'    => esc_html__( 'Show Content Only', 'Avada' ),
							'hide'            => esc_html__( 'Hide', 'Avada' ),
						],
						'type'            => 'select',
						'partial_refresh' => [
							'page_title_bar_contents_page_title_bar' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
						'output'          => [
							// Change classes in <body>.
							[
								'element'       => 'body',
								'function'      => 'attr',
								'attr'          => 'class',
								'value_pattern' => 'avada-has-titlebar-$',
								'remove_attrs'  => [ 'avada-has-titlebar-hide', 'avada-has-titlebar-bar_and_content', 'avada-has-titlebar-content_only' ],
							],
						],
					],
					'page_title_bar_text'            => [
						'label'           => esc_html__( 'Page Title Bar Headings', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display the page title bar headings.', 'Avada' ),
						'id'              => 'page_title_bar_text',
						'default'         => '1',
						'type'            => 'switch',
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_page_title_bar_text' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
					'page_title_bar_styling_title'   => [
						'label'       => esc_html__( 'Page Title Bar Styling', 'Avada' ),
						'description' => '',
						'id'          => 'page_title_bar_styling_title',
						'icon'        => true,
						'type'        => 'info',
					],
					'page_title_100_width'           => [
						'label'           => esc_html__( 'Page Title Bar 100% Width', 'Avada' ),
						'description'     => esc_html__( 'Turn on to have the page title bar area display at 100% width according to the viewport size. Turn off to follow site width.', 'Avada' ),
						'id'              => 'page_title_100_width',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ '', 'true' ],
										'element'   => 'body',
										'className' => 'avada-has-pagetitle-100-width',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'page_title_height'              => [
						'label'           => esc_html__( 'Page Title Bar Height', 'Avada' ),
						'description'     => esc_html__( 'Controls the height of the page title bar on desktop.', 'Avada' ),
						'id'              => 'page_title_height',
						'default'         => '87px',
						'type'            => 'dimension',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'    => '--page_title_height',
								'element' => '.fusion-page-title-bar',
							],
						],
					],
					'page_title_mobile_height'       => [
						'label'           => esc_html__( 'Page Title Bar Mobile Height', 'Avada' ),
						'description'     => esc_html__( 'Controls the height of the page title bar on mobile.', 'Avada' ),
						'id'              => 'page_title_mobile_height',
						'default'         => '70px',
						'type'            => 'dimension',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_mobile_height',
								'element'  => '.fusion-page-title-bar',
								'callback' => [
									'convert_font_size_to_px',
									[
										'setting'  => 'page_title_font_size',
										'addUnits' => true,
									],
								],
							],
						],
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ 'auto', '===' ],
										'element'   => 'body',
										'className' => 'avada-has-page-title-mobile-height-auto',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'page_title_bg_color'            => [
						'label'           => esc_html__( 'Page Title Bar Background Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the background color of the page title bar.', 'Avada' ),
						'id'              => 'page_title_bg_color',
						'default'         => '#F6F6F6',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_bg_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
					],
					'page_title_border_color'        => [
						'label'           => esc_html__( 'Page Title Bar Borders Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the border colors of the page title bar.', 'Avada' ),
						'id'              => 'page_title_border_color',
						'default'         => '#d2d3d4',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_border_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
						'output'          => [
							[
								'element'           => '.fusion-page-title-bar',
								'property'          => 'border',
								'js_callback'       => [
									'fusionReturnStringIfTransparent',
									[
										'transparent' => 'none',
										'opaque'      => '',
									],
								],
								'sanitize_callback' => [ 'Avada_Output_Callbacks', 'page_title_border_color' ],
							],
						],
					],
					'page_title_font_size'           => [
						'label'           => esc_html__( 'Page Title Bar Heading Font Size', 'Avada' ),
						'description'     => esc_html__( 'Controls the font size for the page title bar main heading.', 'Avada' ),
						'id'              => 'page_title_font_size',
						'default'         => '18px',
						'type'            => 'dimension',
						'choices'         => [
							'units' => [ 'px', 'em' ],
						],
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'    => '--page_title_font_size',
								'element' => '.fusion-page-title-bar',
							],
						],
					],
					'page_title_line_height'         => [
						'label'           => esc_html__( 'Page Title Bar Heading Line Height', 'Avada' ),
						'description'     => esc_html__( 'Controls the line height for the page title bar main heading.', 'Avada' ),
						'id'              => 'page_title_line_height',
						'default'         => ( isset( $settings['h1_typography']['line-height'] ) && ! empty( $settings['h1_typography']['line-height'] ) ) ? $settings['h1_typography']['line-height'] : 'normal',
						'type'            => 'dimension',
						'choices'         => [
							'units' => [ 'px', 'em' ],
						],
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'    => '--page_title_line_height',
								'element' => '.fusion-page-title-bar',
							],
						],
					],
					'page_title_color'               => [
						'label'           => esc_html__( 'Page Title Bar Heading Font Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the text color of the page title bar main heading.', 'Avada' ),
						'id'              => 'page_title_color',
						'default'         => '#333333',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
					],
					'page_title_subheader_font_size' => [
						'label'           => esc_html__( 'Page Title Bar Subheading Font Size', 'Avada' ),
						'description'     => esc_html__( 'Controls the font size for the page titlebar subheading.', 'Avada' ),
						'id'              => 'page_title_subheader_font_size',
						'default'         => '14px',
						'type'            => 'dimension',
						'choices'         => [
							'units' => [ 'px', 'em' ],
						],
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'    => '--page_title_subheader_font_size',
								'element' => '.fusion-page-title-bar',
							],
						],
					],
					'page_title_subheader_color'     => [
						'label'           => esc_html__( 'Page Title Bar Subheading Font Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the text color of the page title bar subheading.', 'Avada' ),
						'id'              => 'page_title_subheader_color',
						'default'         => '#333333',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_subheader_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
					],
					'page_title_alignment'           => [
						'label'           => esc_html__( 'Page Title Bar Text Alignment', 'Avada' ),
						'description'     => esc_html__( 'Choose the title and subhead text alignment. Breadcrumbs / search field will be on opposite side for left / right alignment and below the title for center alignment.', 'Avada' ),
						'id'              => 'page_title_alignment',
						'default'         => 'left',
						'type'            => 'radio-buttonset',
						'choices'         => [
							'left'   => esc_html__( 'Left', 'Avada' ),
							'center' => esc_html__( 'Center', 'Avada' ),
							'right'  => esc_html__( 'Right', 'Avada' ),
						],
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_page_title_alignment' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
					'page_title_bar_bg_image_title'  => [
						'label'       => esc_html__( 'Page Title Bar Background Image', 'Avada' ),
						'description' => '',
						'id'          => 'page_title_bar_bg_image_title',
						'icon'        => true,
						'type'        => 'info',
					],
					'page_title_bg'                  => [
						'label'           => esc_html__( 'Page Title Bar Background Image', 'Avada' ),
						'description'     => esc_html__( 'Select an image for the page title bar background. If left empty, the page title bar background color will be used.', 'Avada' ),
						'id'              => 'page_title_bg',
						'default'         => Avada::$template_dir_url . '/assets/images/page_title_bg.png',
						'mod'             => '',
						'type'            => 'media',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_bg',
								'element'  => '.fusion-page-title-bar',
								'choice'   => 'url',
								'callback' => [ 'fallback_to_value', [ 'url("$")', 'none' ] ],
							],
						],
					],
					'page_title_bg_retina'           => [
						'label'           => esc_html__( 'Retina Page Title Bar Background Image', 'Avada' ),
						'description'     => esc_html__( 'Select an image for the retina version of the page title bar background. It should be exactly 2x the size of the page title bar background.', 'Avada' ),
						'id'              => 'page_title_bg_retina',
						'default'         => '',
						'mod'             => '',
						'type'            => 'media',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--page_title_bg_retina',
								'element'  => '.fusion-page-title-bar',
								'choice'   => 'url',
								'callback' => [ 'fallback_to_value', [ 'url("$")', '' ] ],
							],
						],
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ '', 'has-image' ],
										'element'   => 'html',
										'className' => 'avada-has-pagetitlebar-retina-bg-image',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'page_title_bg_full'             => [
						'label'           => esc_html__( '100% Background Image', 'Avada' ),
						'description'     => esc_html__( 'Turn on to have the page title bar background image display at 100% in width and height according to the window size.', 'Avada' ),
						'id'              => 'page_title_bg_full',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ '', 'true' ],
										'element'   => 'body',
										'className' => 'avada-has-pagetitle-bg-full',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'page_title_bg_parallax'         => [
						'label'           => esc_html__( 'Parallax Background Image', 'Avada' ),
						'description'     => esc_html__( 'Turn on to use a parallax scrolling effect on the background image.', 'Avada' ),
						'id'              => 'page_title_bg_parallax',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ '', 'true' ],
										'element'   => 'body',
										'className' => 'avada-has-pagetitle-bg-parallax',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'page_title_fading'              => [
						'label'           => esc_html__( 'Fading Animation', 'Avada' ),
						'description'     => esc_html__( 'Turn on to have the page title text fade on scroll.', 'Avada' ),
						'id'              => 'page_title_fading',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'output'          => [
							// This is for the avadaFadeVars.page_title_fading var.
							[
								'element'           => 'helperElement',
								'property'          => 'bottom',
								'js_callback'       => [
									'fusionGlobalScriptSet',
									[
										'globalVar' => 'avadaFadeVars',
										'id'        => 'page_title_fading',
										'trigger'   => [ 'avadaTriggerPageTitleFading' ],
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
				],
			],
			'breadcrumb_options_header_info' => [
				'label'  => esc_html__( 'Breadcrumbs / Search Bar', 'Avada' ),
				'id'     => 'breadcrumb_options_header_info',
				'icon'   => true,
				'type'   => 'sub-section',
				'fields' => [
					'breadcrumb_important_note_info'    => ( '0' === Avada()->settings->get( 'dependencies_status' ) ) ? [] : [
						'label'           => '',
						'description'     => '<div class="fusion-redux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The page title bar is set to "Hide". However, there are breadcrumb options below that are still visible due to having a fusion page option dependency. Click the link in the option description to learn more. ', 'Avada' ) . '</div>',
						'id'              => 'breadcrumb_important_note_info',
						'type'            => 'custom',
						'soft_dependency' => true,
					],
					'page_title_bar_bs'                 => [
						'label'           => esc_html__( 'Breadcrumbs / Search Bar Content Display', 'Avada' ),
						'description'     => esc_html__( 'Controls what displays in the breadcrumbs area. ', 'Avada' ),
						'id'              => 'page_title_bar_bs',
						'default'         => 'breadcrumbs',
						'type'            => 'radio-buttonset',
						'choices'         => [
							'none'        => esc_html__( 'None', 'Avada' ),
							'breadcrumbs' => esc_html__( 'Breadcrumbs', 'Avada' ),
							'search_box'  => esc_html__( 'Search Bar', 'Avada' ),
						],
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_breadcrumb_show_post_type_archive' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],

					'breadcrumb_mobile'                 => [
						'label'           => esc_html__( 'Breadcrumbs on Mobile Devices', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display breadcrumbs on mobile devices.', 'Avada' ),
						'id'              => 'breadcrumb_mobile',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'output'          => [
							[
								'element'           => 'helperElement',
								'property'          => 'dummy',
								'callback'          => [
									'toggle_class',
									[
										'condition' => [ '', 'false' ],
										'element'   => 'body',
										'className' => 'avada-has-breadcrumb-mobile-hidden',
									],
								],
								'sanitize_callback' => '__return_empty_string',
							],
						],
					],
					'breacrumb_prefix'                  => [
						'label'           => esc_html__( 'Breadcrumbs Prefix', 'Avada' ),
						'description'     => esc_html__( 'Controls the text before the breadcrumb menu.', 'Avada' ),
						'id'              => 'breacrumb_prefix',
						'default'         => '',
						'type'            => 'text',
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_breacrumb_prefix' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
					'breadcrumb_separator'              => [
						'label'           => esc_html__( 'Breadcrumbs Separator', 'Avada' ),
						'description'     => esc_html__( 'Controls the type of separator between each breadcrumb.', 'Avada' ),
						'id'              => 'breadcrumb_separator',
						'default'         => '/',
						'type'            => 'text',
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_breadcrumb_separator' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
					'breadcrumbs_font_size'             => [
						'label'           => esc_html__( 'Breadcrumbs Font Size', 'Avada' ),
						'description'     => esc_html__( 'Controls the font size for the breadcrumbs text.', 'Avada' ),
						'id'              => 'breadcrumbs_font_size',
						'default'         => '10px',
						'type'            => 'dimension',
						'choices'         => [
							'units' => [ 'px', 'em' ],
						],
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'    => '--breadcrumbs_font_size',
								'element' => '.fusion-page-title-bar',
							],
						],
					],
					'breadcrumbs_text_color'            => [
						'label'           => esc_html__( 'Breadcrumbs Text Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the text color of the breadcrumbs font.', 'Avada' ),
						'id'              => 'breadcrumbs_text_color',
						'default'         => '#333333',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--breadcrumbs_text_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
					],
					'breadcrumbs_text_hover_color'      => [
						'label'           => esc_html__( 'Breadcrumbs Text Hover Color', 'Avada' ),
						'description'     => esc_html__( 'Controls the text hover color of the breadcrumbs font.', 'Avada' ),
						'id'              => 'breadcrumbs_text_hover_color',
						'default'         => '#333333',
						'type'            => 'color-alpha',
						'soft_dependency' => true,
						'css_vars'        => [
							[
								'name'     => '--breadcrumbs_text_hover_color',
								'element'  => '.fusion-page-title-bar',
								'callback' => [ 'sanitize_color' ],
							],
						],
					],
					'breadcrumb_show_categories'        => [
						'label'           => esc_html__( 'Post Categories on Breadcrumbs', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display the post categories in the breadcrumbs path.', 'Avada' ),
						'id'              => 'breadcrumb_show_categories',
						'default'         => '1',
						'type'            => 'switch',
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_breadcrumb_show_categories' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
					'breadcrumb_show_post_type_archive' => [
						'label'           => esc_html__( 'Post Type Archives on Breadcrumbs', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display post type archives in the breadcrumbs path.', 'Avada' ),
						'id'              => 'breadcrumb_show_post_type_archive',
						'default'         => '0',
						'type'            => 'switch',
						'soft_dependency' => true,
						'partial_refresh' => [
							'page_title_bar_contents_breadcrumb_show_post_type_archive' => [
								'selector'              => '.avada-page-titlebar-wrapper',
								'container_inclusive'   => false,
								'render_callback'       => [ 'Avada_Partial_Refresh_Callbacks', 'page_titlebar_wrapper' ],
								'success_trigger_event' => 'fusion-ptb-refreshed',
							],
						],
					],
				],
			],
		],
	];

	return $sections;

}
