<?php
/**
 * Correlate Theme-Options, Page-Options and Taxonomy Options.
 *
 * @since 2.0
 * @package avada
 */

/**
 * The Fusion_Options_Map object.
 */
class Fusion_Options_Map {

	/**
	 * The options map.
	 *
	 * @static
	 * @access private
	 * @since 2.0
	 * @var array
	 */
	private static $map = [
		'main_padding[top]'                      => [
			'theme' => [ 'main_padding', 'top' ],
			'post'  => 'main_top_padding',
			'term'  => 'main_padding_top',
		],
		'main_padding[bottom]'                   => [
			'theme' => [ 'main_padding', 'bottom' ],
			'post'  => 'main_bottom_padding',
			'term'  => 'main_padding_bottom',
		],
		'bg_image[url]'                          => [
			'theme' => [ 'bg_image', 'url' ],
			'post'  => 'page_bg',
		],
		'portfolio_related_posts'                => [
			'post' => 'related_posts',
		],
		'faq_related_posts'                      => [
			'post'    => 'related_posts',
			'is_bool' => true,
		],
		'layout'                                 => [
			'post' => 'page_bg_layout',
		],
		'bg_repeat'                              => [
			'post' => 'page_bg_repeat',
		],
		'bg_full'                                => [
			'theme'   => 'bg_full',
			'post'    => 'page_bg_full',
			'is_bool' => true,
		],
		'page_title_bg_full'                     => [
			'post'    => 'page_title_bar_bg_full',
			'is_bool' => true,
		],
		'header_bg_color'                        => [
			'theme'   => 'header_bg_color',
			'post'    => 'combined_header_bg_color',
			'archive' => 'archive_header_bg_color',
			'term'    => 'header_bg_color',
		],
		'bg_color'                               => [
			'theme' => 'bg_color',
			'post'  => 'page_bg_color',
		],
		'mobile_header_bg_color'                 => [
			'theme'   => 'mobile_header_bg_color',
			'post'    => 'mobile_header_bg_color',
			'archive' => 'mobile_archive_header_bg_color',
			'term'    => 'mobile_header_bg_color',
		],
		'header_bg_full'                         => [
			'post'    => 'header_bg_full',
			'is_bool' => true,
		],
		'header_bg_repeat'                       => [
			'post' => 'header_bg_repeat',
		],
		'header_bg_parallax'                     => [
			'is_bool' => true,
		],
		'avada_rev_styles'                       => [
			'is_bool'  => true,
			'mismatch' => true,
		],
		'page_title_bg_full'                     => [
			'is_bool' => true,
		],
		'page_title_bg_parallax'                 => [
			'is_bool' => true,
		],
		'header_100_width'                       => [
			'post'    => 'header_100_width',
			'is_bool' => true,
		],
		'hundredp_padding'                       => [
			'post' => 'hundredp_padding',
		],
		'page_title_100_width'                   => [
			'is_bool' => true,
		],
		'footer_100_width'                       => [
			'post'    => 'footer_100_width',
			'is_bool' => true,
		],
		'footer_widgets'                         => [
			'post'    => 'display_footer',
			'is_bool' => true,
		],
		'footer_copyright'                       => [
			'post'    => 'display_copyright',
			'is_bool' => true,
		],
		'content_bg_color'                       => [
			'post' => 'wide_page_bg_color',
		],
		'content_bg_full'                        => [
			'theme'   => 'content_bg_full',
			'post'    => 'wide_page_bg_full',
			'is_bool' => true,
		],
		// TODO: handle images.
		'content_bg_image[url]'                  => [
			'theme' => [ 'content_bg_image', 'url' ],
			'post'  => 'wide_page_bg',
		],
		'content_bg_repeat'                      => [
			'post' => 'wide_page_bg_repeat',
		],
		'header_bg_image[url]'                   => [
			'theme' => [ 'header_bg_image', 'url' ],
			'post'  => 'header_bg',
		],
		'portfolio_width_100'                    => [
			'is_bool' => true,
		],
		'blog_width_100'                         => [
			'is_bool' => true,
		],
		'page_title_bg_retina[url]'              => [
			'theme' => [ 'page_title_bg_retina', 'url' ],
			'post'  => 'page_title_bar_bg_retina',
			'term'  => 'page_title_bg_retina',
		],
		'page_title_bg[url]'                     => [
			'theme' => [ 'page_title_bg', 'url' ],
			'post'  => 'page_title_bar_bg',
			'term'  => 'page_title_bg',
		],
		'page_title_border_color'                => [
			'post' => 'page_title_bar_borders_color',
		],
		'page_title_bg_color'                    => [
			'post' => 'page_title_bar_bg_color',
		],
		'page_title_subheader_color'             => [
			'post' => 'page_title_subheader_font_color',
		],
		'page_title_subheader_font_size'         => [
			'post' => 'page_title_custom_subheader_text_size',
		],
		'page_title_color'                       => [
			'post' => 'page_title_font_color',
		],
		'page_title_font_size'                   => [
			'post' => 'page_title_text_size',
		],
		'page_title_alignment'                   => [
			'post' => 'page_title_text_alignment',
		],
		'page_title_bar_text'                    => [
			'post'    => 'page_title_text',
			'is_bool' => true,
		],
		'page_title_bar_bs'                      => [
			'post' => 'page_title_breadcrumbs_search_bar',
		],
		'page_title_bar'                         => [
			'post'             => 'page_title',
			'is_home'          => 'page_for_posts',
			'is_tag'           => 'blog_page_title_bar',
			'is_category'      => 'blog_page_title_bar',
			'is_author'        => 'blog_page_title_bar',
			'is_date'          => 'blog_page_title_bar',
			'is_singular_post' => 'blog_page_title_bar',
		],
		'page_title_height'                      => [
			'theme' => 'page_title_height',
			'post'  => 'page_title_height',
			'term'  => 'page_title_height',
		],
		'page_title_mobile_height'               => [
			'theme' => 'page_title_mobile_height',
			'post'  => 'page_title_mobile_height',
			'term'  => 'page_title_mobile_height',
		],
		'blog_show_page_title_bar'               => [
			'post' => 'page_title',
		],
		'blog_page_title_bar'                    => [
			'post' => 'page_title',
		],
		'portfolio_featured_image_width'         => [
			'post' => 'width',
		],
		'portfolio_project_desc_title'           => [
			'post'    => 'project_desc_title',
			'is_bool' => true,
		],
		'portfolio_project_details'              => [
			'post'    => 'project_details',
			'is_bool' => true,
		],
		'portfolio_disable_first_featured_image' => [
			'post'     => 'show_first_featured_image',
			'is_bool'  => true,
			'mismatch' => true,
		],
		'portfolio_link_icon_target'             => [
			'post'    => 'link_icon_target',
			'is_bool' => true,
		],
		'portfolio_related_posts'                => [
			'post'    => 'related_posts',
			'is_bool' => true,
		],
		'related_posts'                          => [
			'is_bool' => true,
		],
		'portfolio_social_sharing_box'           => [
			'post'    => 'share_box',
			'is_bool' => true,
		],
		'events_social_sharing_box'              => [
			'post'    => 'share_box',
			'is_bool' => true,
		],
		'blog_pn_nav'                            => [
			'post'    => 'post_navigation',
			'is_bool' => true,
		],
		'portfolio_pn_nav'                       => [
			'post'    => 'post_navigation',
			'is_bool' => true,
		],
		'disable_woo_gallery'                    => [
			'is_bool' => true,
		],
		'breadcrumb_mobile'                      => [
			'is_bool' => true,
		],
		'responsive'                             => [
			'is_bool' => true,
		],
		'smooth_scrolling'                       => [
			'is_bool' => true,
		],
		'bg_pattern_option'                      => [
			'is_bool' => true,
		],
		'header_sticky'                          => [
			'is_bool' => true,
		],
		'header_sticky_tablet'                   => [
			'is_bool' => true,
		],
		'header_sticky_mobile'                   => [
			'is_bool' => true,
		],
		'header_sticky_shrinkage'                => [
			'is_bool' => true,
		],
		'main_nav_search_icon'                   => [
			'is_bool' => true,
		],
		'mobile_menu_submenu_indicator'          => [
			'is_bool' => true,
		],
		'footerw_bg_image[url]'                  => [
			'theme' => [ 'footerw_bg_image', 'url' ],
		],
		'media_queries_async'                    => [
			'is_bool' => true,
		],
		'css_vars'                               => [
			'is_bool' => true,
		],
		'header_sticky_shadow'                   => [
			'is_bool' => true,
		],
		'typography_responsive'                  => [
			'is_bool' => true,
		],
		'bg_pattern_option'                      => [
			'is_bool' => true,
		],
		'page_title_fading'                      => [
			'is_bool' => true,
		],
		'margin_offset[top]'                     => [
			'theme' => [ 'margin_offset', 'top' ],
		],
		'slider_position'                        => [
			'theme' => 'slider_position',
			'post'  => 'slider_position',
			'term'  => 'slider_position',
		],
		'live_search'                            => [
			'is_bool' => true,
		],
		'search_limit_to_post_titles'            => [
			'is_bool' => true,
		],
		'live_search_display_featured_image'     => [
			'is_bool' => true,
		],
		'live_search_display_post_type'          => [
			'is_bool' => true,
		],
		'lightbox_post_images'                   => [
			'is_bool' => true,
		],
		'lightbox_social'                        => [
			'is_bool' => true,
		],
		'lightbox_desc'                          => [
			'is_bool' => true,
		],
		'lightbox_title'                         => [
			'is_bool' => true,
		],
		'lightbox_autoplay'                      => [
			'is_bool' => true,
		],
		'lightbox_gallery'                       => [
			'is_bool' => true,
		],
		'lightbox_arrows'                        => [
			'is_bool' => true,
		],
		'mobile_nav_submenu_slideout'            => [
			'is_bool' => true,
		],
		'defer_styles'                           => [
			'is_bool' => true,
		],
	];

	/**
	 * Get the option-map.
	 *
	 * @static
	 * @access public
	 * @since 2.0
	 * @return array
	 */
	public static function get_option_map() {
		return apply_filters( 'fusion_get_option_names', self::$map );
	}

	/**
	 * Get the name of an option.
	 *
	 * @static
	 * @since 2.0
	 * @param string $option  The option-name in the map.
	 * @param string $context Can be 'theme', 'post', 'term' or 'archive'.
	 * @return string|array
	 */
	public static function get_option_name( $option, $context = 'theme' ) {

		// Change context if we're on an archive.
		$option_names = self::get_option_map();
		$id           = Fusion::get_instance()->get_page_id();
		if ( 'theme' === $context && false !== strpos( $id, 'archive' ) || false === $id ) {
			$context = 'archive';
			foreach ( $option_names as $name => $options ) {
				if ( isset( $options['archive'] ) && $options['archive'] === $option ) {
					$option = $name;
				}
			}
		}

		if ( isset( $option_names[ $option ] ) ) {

			if ( 'theme' === $context ) {
				foreach ( [ 'is_home', 'is_tag', 'is_category', 'is_author', 'is_date', 'is_singular_post' ] as $condition ) {
					if ( isset( $option_names[ $option ][ $condition ] ) ) {
						$evaluate = (bool) ( function_exists( $condition ) && $condition() );
						$evaluate = ( 'is_singular_post' === $condition && is_singular( 'post' ) ) ? true : $evaluate;

						if ( $evaluate ) {
							return $option_names[ $option ][ $condition ];
						}
					}
				}
			}

			if ( isset( $option_names[ $option ][ $context ] ) ) {
				return $option_names[ $option ][ $context ];
			}
		}

		return $option;
	}

	/**
	 * Get the option-name using TO-name as a reference.
	 *
	 * @static
	 * @since 2.0
	 * @param string $option The option-name in theme-options.
	 * @return string|array
	 */
	public static function get_option_name_from_theme_option( $option ) {

		// Get the full map.
		$option_names = self::get_option_map();
		$option_array = [];

		// Loop the map to find our option.
		foreach ( $option_names as $id => $definition ) {

			// If the option is the key, return it.
			if ( $option === $id ) {
				return $option;
			}

			// If we found the option as a TO, return the ID.
			if ( isset( $definition['theme'] ) && $option === $definition['theme'] ) {
				return $id;
			}

			// If TO is an array, we'll need some extra calculations.
			if ( isset( $definition['theme'] ) && is_array( $definition['theme'] ) && isset( $definition['theme'][0] ) && $option === $definition['theme'][0] ) {
				$option_array[ $definition['theme'][1] ] = $id;
			}
		}
		return empty( $option_array ) ? $option : $option_array;
	}

	/**
	 * Get a map reference from an option-name and the context of that option.
	 *
	 * @static
	 * @access public
	 * @since 2.0
	 * @param string $option  The option-name in the map.
	 * @param string $context Can be 'theme', 'post', 'term' or 'archive'.
	 * @return string
	 */
	public static function get_map_key_from_context( $option, $context = 'theme' ) {
		$map = self::get_option_map();
		foreach ( $map as $key => $args ) {
			if ( isset( $args[ $context ] ) && $option === $args[ $context ] ) {
				return $key;
			}
		}
		return $option;
	}
}
