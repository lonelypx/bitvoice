<?php
/**
 * Portfolio Metabox options.
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
 * Page portfolio post settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_portfolio_post( $sections ) {

	// Dependency check for whether link icon is showing.
	$featured_image_dependency = [
		[
			'field'      => 'show_first_featured_image',
			'value'      => 'yes',
			'comparison' => '!=',
		],
	];
	if ( 0 == Avada()->settings->get( 'portfolio_disable_first_featured_image' ) ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		$featured_image_dependency[] = [
			'field'      => 'show_first_featured_image',
			'value'      => 'default',
			'comparison' => '!=',
		];
	}

	$sections['portfolio_post'] = [
		'label'    => esc_html__( 'Portfolio', 'Avada' ),
		'id'       => 'portfolio_post',
		'alt_icon' => 'fusiona-insertpicture',
		'fields'   => [
			'post_pagination'           => [
				'id'          => 'post_pagination',
				'label'       => esc_attr__( 'Show Previous/Next Pagination', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the post navigation. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_pn_nav', '', 'showhide' ) ),
				'to_default'  => [
					'id' => 'portfolio_pn_nav',
				],
				'dependency'  => [],
				'default'     => 'default',
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
			],
			'portfolio_width_100'       => [
				'id'          => 'portfolio_width_100',
				'label'       => esc_attr__( 'Use 100% Width Page', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to set this post to 100&#37; browser width. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_width_100', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'portfolio_width_100',
				],
				'dependency'  => [],
				'default'     => 'default',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
			],
			'width'                     => [
				'id'          => 'width',
				'label'       => esc_html__( 'Width (Content Columns for Featured Image)', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose if the featured image is full or half width. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_featured_image_width', '', 'select' ) ),
				'to_default'  => [
					'id' => 'portfolio_featured_image_width',
				],
				'dependency'  => [],
				'default'     => 'default',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'full'    => esc_attr__( 'Full Width', 'Avada' ),
					'half'    => esc_attr__( 'Half Width', 'Avada' ),
				],
				'type'        => 'radio-buttonset',
			],
			'show_first_featured_image' => [
				'id'          => 'show_first_featured_image',
				'label'       => esc_html__( 'Disable First Featured Image', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Disable the 1st featured image on single post pages. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_disable_first_featured_image', '', 'reverseyesno' ) ),
				'to_default'  => [
					'id' => 'portfolio_disable_first_featured_image',
				],
				'dependency'  => [],
				'default'     => 'default',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				'type'        => 'radio-buttonset',
				'map'         => 'reverseyesno',
			],
			'fimg'                      => [
				'id'          => 'fimg',
				'value'       => [
					'fimg_width'  => '',
					'fimg_height' => '',
				],
				'label'       => esc_attr__( 'Featured Image Dimensions', 'Avada' ),
				'description' => esc_html__( 'In pixels or percentage, ex: 100% or 100px. Or Use "auto" for automatic resizing if you added either width or height.', 'Avada' ),
				'dependency'  => $featured_image_dependency,
				'type'        => 'dimensions',
			],
			'video'                     => [
				'id'          => 'video',
				'label'       => esc_attr__( 'Video Embed Code', 'Avada' ),
				'description' => esc_attr__( 'Insert Youtube or Vimeo embed code.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'textarea',
			],
			'video_url'                 => [
				'id'          => 'video_url',
				'label'       => esc_attr__( 'Youtube/Vimeo Video URL for Lightbox', 'Avada' ),
				'description' => esc_attr__( 'Insert the video URL that will show in the lightbox.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'text',
			],
			'project_desc_title'        => [
				'id'          => 'project_desc_title',
				'label'       => esc_html__( 'Show Project Description Title', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the project description title. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_project_desc_title', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'portfolio_project_desc_title',
				],
				'dependency'  => [],
				'default'     => 'default',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
			],
			'project_details'           => [
				'id'          => 'project_details',
				'label'       => esc_html__( 'Show Project Details', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the project details text. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_project_details', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'portfolio_project_details',
				],
				'dependency'  => [],
				'default'     => 'default',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
			],
			'project_url'               => [
				'id'          => 'project_url',
				'label'       => esc_attr__( 'Project URL', 'Avada' ),
				'description' => esc_attr__( 'The URL the project text links to.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'text',
			],
			'project_url_text'          => [
				'id'          => 'project_url_text',
				'label'       => esc_attr__( 'Project URL Text', 'Avada' ),
				'description' => esc_html__( 'The custom project text that will link.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'text',
			],
			'copy_url'                  => [
				'id'          => 'copy_url',
				'label'       => esc_attr__( 'Copyright URL', 'Avada' ),
				'description' => esc_html__( 'The URL the copyright text links to.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'text',
			],
			'copy_url_text'             => [
				'id'          => 'copy_url_text',
				'label'       => esc_attr__( 'Copyright URL Text', 'Avada' ),
				'description' => esc_html__( 'The custom copyright text that will link.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'text',
			],
			'image_rollover_icons'      => [
				'id'          => 'image_rollover_icons',
				'label'       => esc_attr__( 'Image Rollover Icons', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose which icons display on this post. %s', 'Avada' ), Avada()->settings->get_default_description( 'link_image_rollover', '', 'rollover' ) ),
				'to_default'  => [
					'id' => 'link_image_rollover',
				],
				'dependency'  => [],
				'type'        => 'select',
				'choices'     => [
					'default'  => esc_attr__( 'Default', 'Avada' ),
					'linkzoom' => esc_html__( 'Link + Zoom', 'Avada' ),
					'link'     => esc_attr__( 'Link', 'Avada' ),
					'zoom'     => esc_attr__( 'Zoom', 'Avada' ),
					'no'       => esc_attr__( 'No Icons', 'Avada' ),
				],
			],
			'link_icon_url'             => [
				'id'          => 'link_icon_url',
				'label'       => esc_attr__( 'Custom Link URL On Archives', 'Avada' ),
				'description' => esc_attr__( 'Link URL that will be used on archives either for the rollover link icon or on the image if rollover icons are disabled. Leave blank for post URL.', 'Avada' ),
				'type'        => 'text',
			],
			'link_icon_target'          => [
				'id'          => 'link_icon_target',
				'label'       => esc_attr__( 'Open Portfolio Links In New Window', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to open the single post page, project url and copyright url links in a new window. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_link_icon_target', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'portfolio_link_icon_target',
				],
				'dependency'  => [],
				'default'     => 'default',
				'type'        => 'radio-buttonset',
				'map'         => 'yesno',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Yes', 'Avada' ),
					'no'      => esc_attr__( 'No', 'Avada' ),
				],
				// Don't change anything since it's not relavant in builder mode.
				'transport'   => 'postMessage',
			],
			'portfolio_author'          => [
				'id'          => 'portfolio_author',
				'label'       => esc_attr__( 'Show Author', 'Avada' ),
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
				'to_default'  => [
					'id' => 'portfolio_author',
				],
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the author in the Project Details. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_author', '', 'showhide' ) ),
				'dependency'  => [],
				'default'     => 'default',
			],
			'share_box'                 => [
				'id'          => 'share_box',
				'label'       => esc_attr__( 'Show Social Share Box', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the social share box. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_social_sharing_box', '', 'showhide' ) ),
				'to_default'  => [
					'id' => 'portfolio_social_sharing_box',
				],
				'dependency'  => [],
				'default'     => 'default',
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
			],
			'related_posts'             => [
				'id'          => 'related_posts',
				'label'       => esc_attr__( 'Show Related Projects', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide related projects on this post. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_related_posts', '', 'showhide' ) ),
				'to_default'  => [
					'id' => 'portfolio_related_posts',
				],
				'dependency'  => [],
				'default'     => 'default',
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
			],
			'portfolio_comments'        => [
				'id'          => 'portfolio_comments',
				'label'       => esc_attr__( 'Show Comments', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_attr__( 'Choose to show or hide comments area. %s', 'Avada' ), Avada()->settings->get_default_description( 'portfolio_comments', '', 'showhide' ) ),
				'to_default'  => [
					'id' => 'portfolio_comments',
				],
				'dependency'  => [],
				'default'     => 'default',
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
			],
		],
	];

	return $sections;
}
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
