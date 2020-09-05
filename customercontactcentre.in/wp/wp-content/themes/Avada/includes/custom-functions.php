<?php
/**
 * Custom avada functions.
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
add_action( 'wp_head', 'avada_set_post_views' );
if ( ! function_exists( 'avada_set_post_views' ) ) {
	/**
	 * Post views inc.
	 */
	function avada_set_post_views() {
		global $post;
		if ( 'post' === get_post_type() && is_single() ) {
			$post_id = $post->ID;
			if ( ! empty( $post_id ) ) {
				$count_key = 'avada_post_views_count';
				$count     = get_post_meta( $post_id, $count_key, true );
				if ( empty( $count ) ) {
					$count = 0;
					delete_post_meta( $post_id, $count_key );
					add_post_meta( $post_id, $count_key, '0' );
				} else {
					$count++;
					update_post_meta( $post_id, $count_key, $count );
				}
			}
		}
	}
}

if ( ! function_exists( 'avada_get_slider' ) ) {
	/**
	 * Get the slider type.
	 *
	 * @param int    $post_id    The post ID.
	 * @param string $type       The slider type.
	 * @param bool   $is_archive Whether archive page.
	 * @return  string
	 */
	function avada_get_slider( $post_id, $type, $is_archive = false ) {
		$type = Avada_Helper::slider_name( $type );
		if ( $is_archive ) {
			$fusion_taxonomy_options = get_term_meta( $post_id, 'fusion_taxonomy_options', true );
			return ( $type ) ? Avada_Helper::get_fusion_tax_meta( $fusion_taxonomy_options, 'fusion_tax_' . $type ) : false;
		} else {
			return ( $type ) ? get_post_meta( $post_id, 'pyre_' . $type, true ) : false;
		}
	}
}

if ( ! function_exists( 'avada_slider' ) ) {
	/**
	 * Slider.
	 *
	 * @param int  $post_id The post ID.
	 * @param bool $is_archive Whether archive page.
	 */
	function avada_slider( $post_id, $is_archive = false ) {

		$slider_type = Avada_Helper::get_slider_type( $post_id, $is_archive );
		$slider      = avada_get_slider( $post_id, $slider_type, $is_archive );

		if ( $slider ) {
			$slider_name = Avada_Helper::slider_name( $slider_type );
			$slider_name = ( 'slider' === $slider_name ) ? 'layerslider' : $slider_name;

			$function = 'avada_' . $slider_name;
			$function( $slider );
		}
	}
}

if ( ! function_exists( 'avada_revslider' ) ) {
	/**
	 * Slider Revolution.
	 *
	 * @param string $name The Slider Revolution slider name.
	 */
	function avada_revslider( $name ) {

		// We use include() instead of get_template_part() to pass the $name.
		include wp_normalize_path( locate_template( 'templates/revslider.php' ) );
	}
}

if ( ! function_exists( 'avada_layerslider' ) ) {
	/**
	 * Layerslider.
	 *
	 * @param int|string $id The layerslider ID.
	 */
	function avada_layerslider( $id ) {

		// We use include() instead of get_template_part() to pass the $id.
		include wp_normalize_path( locate_template( 'templates/layerslider.php' ) );
	}
}

if ( ! function_exists( 'avada_elasticslider' ) ) {
	/**
	 * The elastic-slider.
	 *
	 * @param int|string $term The term.
	 */
	function avada_elasticslider( $term ) {

		// We use include() instead of get_template_part() to pass the $term.
		include wp_normalize_path( locate_template( 'templates/elasticslider.php' ) );
	}
}

if ( ! function_exists( 'avada_wooslider' ) ) {
	/**
	 * Per-term slider.
	 *
	 * @param int|string $term The term.
	 */
	function avada_wooslider( $term ) {
		if ( method_exists( 'Fusion_Slider', 'render_fusion_slider' ) ) {
			Fusion_Slider::render_fusion_slider( $term );
		}
	}
}

if ( ! function_exists( 'avada_get_page_title_bar_contents' ) ) {
	/**
	 * Get the contents of the title bar.
	 *
	 * @param  int  $post_id               The post ID.
	 * @param  bool $get_secondary_content Determine if we want secondary content.
	 * @return array
	 */
	function avada_get_page_title_bar_contents( $post_id, $get_secondary_content = true ) {

		if ( $get_secondary_content ) {
			ob_start();

			if ( 'breadcrumbs' === fusion_get_option( 'page_title_bar_bs' ) ) {
				fusion_breadcrumbs();
			} elseif ( 'search_box' === fusion_get_option( 'page_title_bar_bs' ) ) {
				get_search_form();
			}
			$secondary_content = ob_get_contents();
			ob_get_clean();
		} else {
			$secondary_content = '';
		}

		$title                       = '';
		$subtitle                    = '';
		$page_title_custom_text      = get_post_meta( $post_id, 'pyre_page_title_custom_text', true );
		$page_title_custom_subheader = get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true );
		$page_title_text             = fusion_get_option( 'page_title_bar_text' );

		if ( ! empty( $page_title_custom_text ) ) {
			$title = $page_title_custom_text;
		}

		if ( ! empty( $page_title_custom_subheader ) ) {
			$subtitle = $page_title_custom_subheader;
		}

		if ( is_search() ) {
			/* translators: The search query. */
			$title    = sprintf( esc_html__( 'Search results for: %s', 'Avada' ), get_search_query() );
			$subtitle = '';
		}

		if ( ! $title ) {
			$title = get_the_title( $post_id );

			// Only assign blog title theme option to default blog page and not posts page.
			if ( is_home() && 'page' !== get_option( 'show_on_front' ) ) {
				$title = Avada()->settings->get( 'blog_title' );
			}

			if ( is_404() ) {
				$title = esc_html__( 'Error 404 Page', 'Avada' );
			}

			if ( class_exists( 'Tribe__Events__Main' ) && ( ( Avada_Helper::tribe_is_event( $post_id ) && ! is_single() && ! is_home() && ! is_tag() ) || Avada_Helper::is_events_archive( $post_id ) && ! is_tag() || ( Avada_Helper::is_events_archive( $post_id ) && is_404() ) ) ) {
				$title = tribe_get_events_title();
			} elseif ( is_archive() && ! Avada_Helper::is_bbpress() && ! is_search() ) {
				if ( is_day() ) {
					/* translators: Date. */
					$title = sprintf( esc_html__( 'Daily Archives: %s', 'Avada' ), '<span>' . get_the_date() . '</span>' );
				} elseif ( is_month() ) {
					/* translators: Date. */
					$title = sprintf( esc_html__( 'Monthly Archives: %s', 'Avada' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
				} elseif ( is_year() ) {
					/* translators: Date. */
					$title = sprintf( esc_html__( 'Yearly Archives: %s', 'Avada' ), '<span> ' . get_the_date( 'Y' ) . '</span>' );
				} elseif ( is_author() ) {
					$curauth = get_user_by( 'id', get_query_var( 'author' ) );
					$title   = $curauth->nickname;
				} elseif ( is_post_type_archive() ) {
					$title = post_type_archive_title( '', false );

					$sermon_settings = get_option( 'wpfc_options' );
					if ( is_array( $sermon_settings ) ) {
						$title = $sermon_settings['archive_title'];
					}
				} else {
					$title = single_cat_title( '', false );
				}
			} elseif ( class_exists( 'bbPress' ) && Avada_Helper::is_bbpress() && Avada_Helper::bbp_is_forum_archive() ) {
				$title = post_type_archive_title( '', false );
			}

			if ( class_exists( 'WooCommerce' ) && Avada_Helper::is_woocommerce() && ( is_product() || is_shop() ) && ! is_search() ) {
				if ( ! is_product() ) {
					$title = woocommerce_page_title( false );
				}
			}
		}

		// Only assign blog subtitle theme option to default blog page and not posts page.
		if ( ! $subtitle && is_home() && 'page' !== get_option( 'show_on_front' ) ) {
			$subtitle = Avada()->settings->get( 'blog_subtitle' );
		}

		if ( 'hide' !== fusion_get_option( 'page_title_bar' ) && ! $page_title_text ) {
			$title    = '';
			$subtitle = '';
		}

		return apply_filters( 'avada_page_title_bar_contents', [ $title, $subtitle, $secondary_content ] );
	}
}

if ( ! function_exists( 'avada_current_page_title_bar' ) ) {
	/**
	 * Get the current page title.
	 *
	 * @param int|string $post_id The post ID.
	 */
	function avada_current_page_title_bar( $post_id = false ) {
		$post_id = $post_id ? $post_id : Avada()->fusion_library->get_page_id();
		if ( has_action( 'avada_override_current_page_title_bar' ) ) {
			do_action( 'avada_override_current_page_title_bar', $post_id );
		} elseif ( 'hide' !== fusion_get_option( 'page_title_bar' ) ) {
			$page_title_bar_contents = avada_get_page_title_bar_contents( $post_id );

			avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
		}
		do_action( 'avada_after_page_title_bar' );
	}
}

if ( ! function_exists( 'avada_is_page_title_bar_active' ) ) {
	/**
	 * Check if page title bar is active.
	 * Note: This checks if the PTB is displayed at all.
	 *
	 * @since 5.8.1
	 * @param int $post_id The post ID.
	 * @return bool
	 */
	function avada_is_page_title_bar_active( $post_id ) {
		return 'hide' !== fusion_get_option( 'page_title_bar' );
	}
}

if ( ! function_exists( 'avada_is_page_title_bar_enabled' ) ) {
	/**
	 * Check if page title bar is enabled.
	 *
	 * @param int $post_id The post ID.
	 * @return bool
	 */
	function avada_is_page_title_bar_enabled( $post_id ) {
		return fusion_get_option( 'page_title_bar_text' ) && 'hide' !== fusion_get_option( 'page_title_bar' );
	}
}

if ( ! function_exists( 'avada_backend_check_new_bbpress_post' ) ) {
	/**
	 * Check if we're creating a new bbPress post.
	 *
	 * @return bool
	 */
	function avada_backend_check_new_bbpress_post() {
		global $pagenow, $post_type;
		return ( 'post-new.php' === $pagenow && in_array( $post_type, array( 'forum', 'topic', 'reply' ) ) ) ? true : false;
	}
}

if ( ! function_exists( 'avada_display_sidenav' ) ) {
	/**
	 * Displays side navigation.
	 *
	 * @param  int $post_id The post ID.
	 * @return string
	 */
	function avada_display_sidenav( $post_id ) {

		if ( is_page_template( 'side-navigation.php' ) && 0 !== get_queried_object_id() ) {
			$html = '<ul class="side-nav">';

			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = end( $post_ancestors );

			$html .= ( is_page( $post_parent ) ) ? '<li class="current_page_item">' : '<li>';

			if ( $post_parent ) {
				$html    .= '<a href="' . get_permalink( $post_parent ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $post_parent ) . '</a></li>';
				$children = wp_list_pages( 'title_li=&child_of=' . $post_parent . '&echo=0' );
			} else {
				$html    .= '<a href="' . get_permalink( $post_id ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $post_id ) . '</a></li>';
				$children = wp_list_pages( 'title_li=&child_of=' . $post_id . '&echo=0' );
			}

			if ( $children ) {
				$html .= $children;
			}

			$html .= '</ul>';

			return $html;
		}
	}
}

if ( ! function_exists( 'avada_number_of_featured_images' ) ) {
	/**
	 * Get the number of featured images.
	 *
	 * @return int
	 */
	function avada_number_of_featured_images() {
		global $post;
		$number_of_images = 0;

		if ( has_post_thumbnail() && 'yes' !== get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) {
			$number_of_images++;
		}

		$posts_slideshow_number = Avada()->settings->get( 'posts_slideshow_number' );
		for ( $i = 2; $i <= $posts_slideshow_number; $i++ ) {
			$attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, $post->post_type );

			if ( $attachment_new_id ) {
				$number_of_images++;
			}
		}

		return $number_of_images;
	}
}

if ( ! function_exists( 'avada_singular_featured_image' ) ) {
	/**
	 * Featured images for singular pages.
	 *
	 * @since 6.0
	 * @param string|false $context The featured-image context(example: tribe_event, avada_portfolio etc).
	 * @return void
	 */
	function avada_singular_featured_image( $context = '' ) {

		if ( '' === $context ) {
			$context = get_post_type();
		}

		// ID check is needed for EC.
		if ( 0 === get_the_ID() || false === $context ) {
			return;
		}

		if ( 'tribe_events' !== $context && ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() || fusion_doing_ajax() ) ) {
			echo '<div class="fusion-featured-image-wrapper">';
		}

		if ( 'tribe_events' === $context ) {
			if ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() || fusion_doing_ajax() ) {
				add_filter( 'tribe_event_featured_image', 'avada_event_featured_image_wrap', 99, 3 );
			}

			// Event featured image, but exclude link.
			echo tribe_event_featured_image( get_the_ID(), 'full', false ); // phpcs:ignore WordPress.Security.EscapeOutput

			if ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() || fusion_doing_ajax() ) {
				remove_filter( 'tribe_event_featured_image', 'avada_event_featured_image_wrap', 99 );
			}
		} elseif ( 'avada_portfolio' === $context ) {
			include FUSION_CORE_PATH . '/templates/featured-image-' . $context . '.php';
		} elseif ( 'product' === $context && function_exists( 'woocommerce_show_product_images' ) ) {
			woocommerce_show_product_images();
		} else {
			get_template_part( 'templates/featured-image-' . $context );
		}

		if ( 'tribe_events' !== $context && ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() || fusion_doing_ajax() ) ) {
			echo '</div>';
		}

	}
}

/**
 * Filter EC featured image markup on single page.
 *
 * @since 6.0
 * @param string $featured_image Generated HTML.
 * @param int    $post_id        Post ID.
 * @param string $size           Image size.
 * @return $string
 */
function avada_event_featured_image_wrap( $featured_image, $post_id, $size ) {
	return '<div class="fusion-featured-image-wrapper">' . $featured_image . '</div>';
}

add_action( 'woocommerce_before_template_part', 'avada_product_before_featured_image_wrap', 99, 4 );
/**
 * Open product featured image wrapper div on single page.
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $located       Path to be included.
 * @param array  $args          Arguments. (default: array).
 */
function avada_product_before_featured_image_wrap( $template_name, $template_path, $located, $args ) {

	if ( 'single-product/product-image.php' === $template_name && ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() ) ) {
		echo '<div class="fusion-featured-image-wrapper">';
	}
}

add_action( 'woocommerce_after_template_part', 'avada_product_after_featured_image_wrap', 99, 4 );
/**
 * Close product featured image wrapper div on single page.
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $located       Path to be included.
 * @param array  $args          Arguments. (default: array).
 */
function avada_product_after_featured_image_wrap( $template_name, $template_path, $located, $args ) {

	if ( 'single-product/product-image.php' === $template_name && ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() ) ) {
		echo '</div>';
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
