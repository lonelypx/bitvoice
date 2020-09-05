<?php
/**
 * Post Metabox options.
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
 * Post page settings
 *
 * @param array $sections An array of our sections.
 * @return array
 */
function avada_page_options_tab_post( $sections ) {

	$sections['post'] = [
		'label'    => esc_html__( 'Post', 'Avada' ),
		'id'       => 'post',
		'alt_icon' => 'fusiona-feather',
		'fields'   => [
			'portfolio_width_100'       => [
				'id'          => 'portfolio_width_100',
				'label'       => esc_attr__( 'Use 100% Width Page', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to set this post to 100&#37; browser width. %s', 'Avada' ), Avada()->settings->get_default_description( 'blog_width_100', '', 'yesno' ) ),
				'to_default'  => [
					'id' => 'blog_width_100',
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
			'show_first_featured_image' => [
				'id'          => 'show_first_featured_image',
				'label'       => esc_attr__( 'Disable First Featured Image', 'Avada' ),
				'description' => esc_html__( 'Disable the 1st featured image on single post pages.', 'Avada' ),
				'choices'     => [
					'yes' => esc_attr__( 'Yes', 'Avada' ),
					'no'  => esc_attr__( 'No', 'Avada' ),
				],
				'default'     => 'no',
				'dependency'  => [],
				'type'        => 'radio-buttonset',
			],
			'fimg'                      => [
				'id'          => 'fimg',
				'label'       => esc_attr__( 'Featured Image Dimensions', 'Avada' ),
				'description' => esc_html__( 'In pixels or percentage, ex: 100% or 100px. Or Use "auto" for automatic resizing if you added either width or height.', 'Avada' ),
				'dependency'  => [
					[
						'field'      => 'show_first_featured_image',
						'value'      => 'yes',
						'comparison' => '!=',
					],
				],
				'value'       => [
					'fimg_width'  => '',
					'fimg_height' => '',
				],
				'type'        => 'dimensions',
			],
			'video'                     => [
				'id'          => 'video',
				'label'       => esc_attr__( 'Video Embed Code', 'Avada' ),
				'description' => esc_attr__( 'Insert Youtube or Vimeo embed code.', 'Avada' ),
				'dependency'  => [],
				'type'        => 'textarea',
			],
			'post_pagination'           => [
				'id'          => 'post_pagination',
				'label'       => esc_html__( 'Show Previous/Next Pagination', 'Avada' ),
				/* translators: Additional description (defaults). */
				'description' => sprintf( esc_html__( 'Choose to show or hide the post navigation. %s', 'Avada' ), Avada()->settings->get_default_description( 'blog_pn_nav', '', 'showhide' ) ),
				'to_default'  => [
					'id' => 'blog_pn_nav',
				],
				'dependency'  => [],
				'type'        => 'radio-buttonset',
				'map'         => 'showhide',
				'choices'     => [
					'default' => esc_attr__( 'Default', 'Avada' ),
					'yes'     => esc_attr__( 'Show', 'Avada' ),
					'no'      => esc_attr__( 'Hide', 'Avada' ),
				],
				'default'     => 'default',
			],
		],
	];

	$post_type = get_post_type();

	if ( 'avada_faq' !== $post_type ) {
		$sections['post']['fields']['image_rollover_icons'] = [
			'id'          => 'image_rollover_icons',
			'label'       => esc_attr__( 'Image Rollover Icons', 'Avada' ),
			/* translators: Additional description (defaults). */
			'description' => sprintf( esc_html__( 'Choose which icons display on this post. %s', 'Avada' ), Avada()->settings->get_default_description( 'image_rollover', '', 'rollover' ) ),
			'to_default'  => [
				'id' => 'image_rollover',
			],
			'dependency'  => [],
			'default'     => 'default',
			'choices'     => [
				'default'  => esc_attr__( 'Default', 'Avada' ),
				'linkzoom' => esc_html__( 'Link + Zoom', 'Avada' ),
				'link'     => esc_attr__( 'Link', 'Avada' ),
				'zoom'     => esc_attr__( 'Zoom', 'Avada' ),
				'no'       => esc_attr__( 'No Icons', 'Avada' ),
			],
			'type'        => 'select',
		];

		$sections['post']['fields']['link_icon_url']     = [
			'id'          => 'link_icon_url',
			'label'       => esc_attr__( 'Custom Link URL On Archives', 'Avada' ),
			'description' => esc_attr__( 'Link URL that will be used on archives either for the rollover link icon or on the image if rollover icons are disabled. Leave blank for post URL.', 'Avada' ),
			'type'        => 'text',
		];
		$sections['post']['fields']['post_links_target'] = [
			'id'          => 'post_links_target',
			'label'       => esc_html__( 'Open Blog Links In New Window', 'Avada' ),
			'description' => esc_html__( 'Choose to open the single post page link in a new window.', 'Avada' ),
			'dependency'  => [],
			'type'        => 'radio-buttonset',
			'choices'     => [
				'yes' => esc_attr__( 'Yes', 'Avada' ),
				'no'  => esc_attr__( 'No', 'Avada' ),
			],
			'default'     => 'no',
		];
	}

	$sections['post']['fields']['post_meta']     = [
		'id'          => 'post_meta',
		'label'       => esc_html__( 'Show Post Meta', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_html__( 'Choose to show or hide the post meta. %s', 'Avada' ), Avada()->settings->get_default_description( 'post_meta', '', 'showhide' ) ),
		'to_default'  => [
			'id' => 'post_meta',
		],
		'dependency'  => [],
		'type'        => 'radio-buttonset',
		'map'         => 'showhide',
		'choices'     => [
			'default' => esc_attr__( 'Default', 'Avada' ),
			'yes'     => esc_attr__( 'Show', 'Avada' ),
			'no'      => esc_attr__( 'Hide', 'Avada' ),
		],
		'default'     => 'default',
	];
	$sections['post']['fields']['share_box']     = [
		'id'          => 'share_box',
		'label'       => esc_attr__( 'Show Social Share Box', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_html__( 'Choose to show or hide the social share box. %s', 'Avada' ), Avada()->settings->get_default_description( 'social_sharing_box', '', 'showhide' ) ),
		'to_default'  => [
			'id' => 'social_sharing_box',
		],
		'dependency'  => [],
		'type'        => 'radio-buttonset',
		'map'         => 'showhide',
		'choices'     => [
			'default' => esc_attr__( 'Default', 'Avada' ),
			'yes'     => esc_attr__( 'Show', 'Avada' ),
			'no'      => esc_attr__( 'Hide', 'Avada' ),
		],
		'default'     => 'default',
	];
	$sections['post']['fields']['author_info']   = [
		'id'          => 'author_info',
		'label'       => esc_attr__( 'Show Author Info Box', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_html__( 'Choose to show or hide the author info box. %s', 'Avada' ), Avada()->settings->get_default_description( 'author_info', '', 'showhide' ) ),
		'to_default'  => [
			'id' => 'author_info',
		],
		'dependency'  => [],
		'type'        => 'radio-buttonset',
		'map'         => 'showhide',
		'choices'     => [
			'default' => esc_attr__( 'Default', 'Avada' ),
			'yes'     => esc_attr__( 'Show', 'Avada' ),
			'no'      => esc_attr__( 'Hide', 'Avada' ),
		],
		'default'     => 'default',
	];
	$sections['post']['fields']['related_posts'] = [
		'id'          => 'related_posts',
		'label'       => esc_attr__( 'Show Related Posts', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_html__( 'Choose to show or hide related posts on this post. %s', 'Avada' ), Avada()->settings->get_default_description( 'related_posts', '', 'showhide' ) ),
		'to_default'  => [
			'id' => 'related_posts',
		],
		'dependency'  => [],
		'type'        => 'radio-buttonset',
		'map'         => 'showhide',
		'choices'     => [
			'default' => esc_attr__( 'Default', 'Avada' ),
			'yes'     => esc_attr__( 'Show', 'Avada' ),
			'no'      => esc_attr__( 'Hide', 'Avada' ),
		],
		'default'     => 'default',
	];
	$sections['post']['fields']['post_comments'] = [
		'id'          => 'post_comments',
		'label'       => esc_attr__( 'Show Comments', 'Avada' ),
		/* translators: Additional description (defaults). */
		'description' => sprintf( esc_attr__( 'Choose to show or hide comments area. %s', 'Avada' ), Avada()->settings->get_default_description( 'blog_comments', '', 'showhide' ) ),
		'to_default'  => [
			'id' => 'blog_comments',
		],
		'dependency'  => [],
		'type'        => 'radio-buttonset',
		'map'         => 'showhide',
		'choices'     => [
			'default' => esc_attr__( 'Default', 'Avada' ),
			'yes'     => esc_attr__( 'Show', 'Avada' ),
			'no'      => esc_attr__( 'Hide', 'Avada' ),
		],
		'default'     => 'default',
	];
	return $sections;
}
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
