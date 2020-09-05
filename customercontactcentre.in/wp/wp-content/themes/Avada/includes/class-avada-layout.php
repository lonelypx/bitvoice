<?php
/**
 * Handles layouts.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      3.8
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handles layouts.
 */
class Avada_Layout {

	/**
	 * An array of all our sidebars.
	 *
	 * @access public
	 * @var array
	 */
	public $sidebars = [];

	/**
	 * The content-width.
	 *
	 * @static
	 * @access private
	 * @since 5.0.0
	 * @var int|null
	 */
	private static $content_width = null;

	/**
	 * The class constructor
	 */
	public function __construct() {

		add_action( 'wp', [ $this, 'add_sidebars' ], 20 );
		add_action( 'wp', [ $this, 'add_no_sidebar_layout_styling' ], 20 );
		add_filter( 'is_hundred_percent_template', [ $this, 'is_hundred_percent_template' ], 10, 2 );
		add_filter( 'fusion_is_hundred_percent_template', [ $this, 'is_hundred_percent_template' ], 10, 2 );

	}

	/**
	 * Add sidebar(s) to the pages.
	 *
	 * @return void
	 */
	public function add_sidebars() {

		$load_sidebars = false;

		// Append sidebar to after content div.
		if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {
			add_action( 'avada_after_content', [ $this, 'append_sidebar_single' ] );
			$load_sidebars = true;
		} elseif ( Avada()->template->double_sidebars() ) {
			add_action( 'avada_after_content', [ $this, 'append_sidebar_double' ] );
			$load_sidebars = true;
		} elseif ( ! Avada()->template->has_sidebar() && ( is_page_template( 'side-navigation.php' ) || ( is_singular( 'tribe_events' ) && 'sidebar' === Avada()->settings->get( 'ec_meta_layout' ) ) ) ) {
			add_action( 'avada_after_content', [ $this, 'append_sidebar_single' ] );
			$load_sidebars = true;
		}

		if ( $load_sidebars ) {
			// Get the sidebars and assign to public variable.
			$this->sidebars = $this->get_sidebar_settings( $this->sidebar_options() );

			// Set styling to content and sidebar divs.
			$this->add_sidebar_layout_styling( $this->sidebars );

			add_filter( 'fusion_responsive_sidebar_order', [ $this, 'correct_responsive_sidebar_order' ] );
		}
	}

	/**
	 * Get sidebar settings based on the page type.
	 *
	 * @return array
	 */
	public function sidebar_options() {
		$post_id = Avada()->fusion_library->get_page_id();

		if ( is_home() ) {
			$sidebars = [
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'blog_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'blog_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
			];
		} elseif ( Avada_Helper::is_bbpress() ) {
			$sidebars = [
				'global'    => Avada()->settings->get( 'bbpress_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
				'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
			];

			if ( Avada_Helper::bbp_is_forum_archive() || Avada_Helper::bbp_is_topic_archive() || Avada_Helper::bbp_is_user_home() || Avada_Helper::bbp_is_search() ) {
				$sidebars = [
					'global'    => '1',
					'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
					'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
				];
			}
		} elseif ( Avada_Helper::is_buddypress() ) {
			$sidebars = [
				'global'    => Avada()->settings->get( 'bbpress_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
				'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
			];
		} elseif ( class_exists( 'WooCommerce' ) && ( is_product() || is_shop() ) && ! is_search() ) {
			$sidebars = [
				'global'    => Avada()->settings->get( 'woo_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'woo_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'woo_sidebar_2' ),
				'position'  => Avada()->settings->get( 'woo_sidebar_position' ),
			];
		} elseif ( class_exists( 'WooCommerce' ) && ( ( Avada_Helper::is_woocommerce() && is_tax() ) || is_tax( 'product_brand' ) || is_tax( 'images_collections' ) || is_tax( 'shop_vendor' ) ) ) {
			$sidebars = [
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'woocommerce_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'woocommerce_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'woo_sidebar_position' ),
			];
		} elseif ( is_page() ) {
			$sidebars = [
				'global'    => Avada()->settings->get( 'pages_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'pages_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'pages_sidebar_2' ),
				'position'  => fusion_get_option( 'default_sidebar_pos' ),
			];
		} elseif ( is_single() ) {
			$sidebars = apply_filters(
				'avada_single_post_sidebar_theme_options',
				[
					'global'    => Avada()->settings->get( 'posts_global_sidebar' ),
					'sidebar_1' => Avada()->settings->get( 'posts_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'posts_sidebar_2' ),
					'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
				]
			);

			if ( is_singular( 'avada_portfolio' ) ) {
				$sidebars = [
					'global'    => Avada()->settings->get( 'portfolio_global_sidebar' ),
					'sidebar_1' => Avada()->settings->get( 'portfolio_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'portfolio_sidebar_2' ),
					'position'  => Avada()->settings->get( 'portfolio_sidebar_position' ),
				];
			} elseif ( is_singular( 'tribe_events' ) || is_singular( 'tribe_organizer' ) || is_singular( 'tribe_venue' ) ) {
				$sidebars = [
					'global'    => Avada()->settings->get( 'ec_global_sidebar' ),
					'sidebar_1' => Avada()->settings->get( 'ec_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'ec_sidebar_2' ),
					'position'  => Avada()->settings->get( 'ec_sidebar_pos' ),
				];

				if ( is_singular( 'tribe_organizer' ) || is_singular( 'tribe_venue' ) ) {
					$sidebars['global'] = 1;
				}
			}
		} elseif ( is_archive() && ! is_search() ) {
			$sidebars = [
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'blog_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'blog_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
			];

			if ( is_post_type_archive( 'avada_portfolio' ) || is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' ) || is_tax( 'portfolio_tags' ) ) {
				$sidebars = [
					'global'    => '1',
					'sidebar_1' => Avada()->settings->get( 'portfolio_archive_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'portfolio_archive_sidebar_2' ),
					'position'  => Avada()->settings->get( 'portfolio_sidebar_position' ),
				];
			}
		} elseif ( is_search() ) {
			$sidebars = [
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'search_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'search_sidebar_2' ),
				'position'  => Avada()->settings->get( 'search_sidebar_position' ),
			];
		} else {
			$sidebars = [
				'global'    => Avada()->settings->get( 'pages_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'pages_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'pages_sidebar_2' ),
				'position'  => fusion_get_option( 'default_sidebar_pos' ),
			];
		}

		if ( Avada_Helper::is_events_archive( $post_id ) && ! is_tag() ) {
			$sidebars = [
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'ec_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ec_sidebar_2' ),
				'position'  => Avada()->settings->get( 'ec_sidebar_pos' ),
			];
		}

		// Remove sidebars from the certain woocommerce pages.
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_cart() || is_checkout() || is_account_page() || ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) {
				$sidebars = [];
			}
		}

		// Add sticky sidebar Theme Option to the array.
		$sidebars['sticky'] = Avada()->settings->get( 'sidebar_sticky' );

		return $sidebars;
	}

	/**
	 * Get the sidebars.
	 *
	 * @param array $sidebar_options Our sidebar options.
	 * @return array
	 */
	public function get_sidebar_settings( $sidebar_options = [] ) {

		$post_id = Avada()->fusion_library->get_page_id();

		// Post options.
		$sidebar_1                    = get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true );
		$sidebar_2                    = get_post_meta( $post_id, 'sbg_selected_sidebar_2_replacement', true );
		$sidebar_position_post_option = strtolower( get_post_meta( $post_id, 'pyre_sidebar_position', true ) );
		$sidebar_sticky               = get_post_meta( $post_id, 'pyre_sidebar_sticky', true );
		$sidebar_position_metadata    = metadata_exists( 'post', $post_id, 'pyre_sidebar_position' );

		$sidebar_1 = (array) $sidebar_1;
		$sidebar_2 = (array) $sidebar_2;

		$sidebar_1[0] = maybe_unserialize( $sidebar_1[0] );
		$sidebar_1[0] = is_array( $sidebar_1[0] ) ? $sidebar_1[0][0] : $sidebar_1[0];
		$sidebar_2[0] = maybe_unserialize( $sidebar_2[0] );
		$sidebar_2[0] = is_array( $sidebar_2[0] ) ? $sidebar_2[0][0] : $sidebar_2[0];

		if ( is_array( $sidebar_1 ) && '0' === $sidebar_1[0] ) {
			$sidebar_1 = [ 'Blog Sidebar' ];
		}

		if ( is_array( $sidebar_2 ) && '0' === $sidebar_2[0] ) {
			$sidebar_2 = [ 'Blog Sidebar' ];
		}

		// Theme option sidebar position.
		$sidebar_position_theme_option = array_key_exists( 'position', $sidebar_options ) ? strtolower( $sidebar_options['position'] ) : '';

		// Set default sidebar position.
		$sidebar_position = $sidebar_position_post_option;

		// Get sidebars and position from theme options if it's being forced globally.
		if ( array_key_exists( 'global', $sidebar_options ) && $sidebar_options['global'] ) {
			$sidebar_1 = [ ( 'None' !== $sidebar_options['sidebar_1'] ) ? $sidebar_options['sidebar_1'] : '' ];
			$sidebar_2 = [ ( 'None' !== $sidebar_options['sidebar_2'] ) ? $sidebar_options['sidebar_2'] : '' ];

			$sidebar_position = $sidebar_position_theme_option;
		} else {
			if ( isset( $sidebar_1[0] ) && 'default_sidebar' === $sidebar_1[0] ) {
				$sidebar_1 = [ ( 'None' !== $sidebar_options['sidebar_1'] ) ? $sidebar_options['sidebar_1'] : '' ];
			}

			if ( isset( $sidebar_2[0] ) && 'default_sidebar' === $sidebar_2[0] ) {
				$sidebar_2 = [ ( 'None' !== $sidebar_options['sidebar_2'] ) ? $sidebar_options['sidebar_2'] : '' ];
			}
		}

		// If sidebar position is default OR no entry in database exists.
		if ( 'default' === $sidebar_position || ! $sidebar_position_metadata ) {
			$sidebar_position = $sidebar_position_theme_option;
		}

		// Reverse sidebar position if double sidebars are used and position is right.
		if ( Avada()->template->double_sidebars() && 'right' === $sidebar_position ) {
			$sidebar_1_placeholder = $sidebar_1;
			$sidebar_2_placeholder = $sidebar_2;

			// Reverse the sidebars.
			$sidebar_1 = $sidebar_2_placeholder;
			$sidebar_2 = $sidebar_1_placeholder;
		}

		// Set the sticky sidebar option.
		if ( 'default' === $sidebar_sticky || empty( $sidebar_sticky ) ) {
			$sidebar_sticky = $sidebar_options['sticky'];
		}

		$return = [
			'position' => $sidebar_position,
			'sticky'   => $sidebar_sticky,
		];

		if ( $sidebar_1 ) {
			$return['sidebar_1'] = $sidebar_1[0];
		}

		if ( $sidebar_2 ) {
			$return['sidebar_2'] = $sidebar_2[0];
		}

		// Add sidebar 1 margin, if double sidebars are used.
		if ( Avada()->template->double_sidebars() ) {
			$half_margin = 'calc(' . str_replace( 'calc', '', Avada()->settings->get( 'sidebars_gutter' ) ) . ' / 2)';

			$sidebar_2_1_width = Fusion_Sanitize::size( Avada()->settings->get( 'sidebar_2_1_width' ) );
			if ( false === strpos( $sidebar_2_1_width, 'px' ) && false === strpos( $sidebar_2_1_width, '%' ) ) {
				$sidebar_2_1_width = ( 100 > intval( $sidebar_2_1_width ) ) ? intval( $sidebar_2_1_width ) . '%' : intval( $sidebar_2_1_width ) . 'px';
			}

			$sidebar_2_2_width = Fusion_Sanitize::size( Avada()->settings->get( 'sidebar_2_2_width' ) );
			if ( false === strpos( $sidebar_2_2_width, 'px' ) && false === strpos( $sidebar_2_2_width, '%' ) ) {
				$sidebar_2_2_width = ( 100 > intval( $sidebar_2_2_width ) ) ? intval( $sidebar_2_2_width ) . '%' : intval( $sidebar_2_2_width ) . 'px';
			}

			$sidebar_2_1_margin = Fusion_Sanitize::add_css_values( [ '-100%', $half_margin, $sidebar_2_2_width ] );
			$sidebar_2_2_margin = $half_margin;

			$return['sidebar_1_data'] = [
				'width'  => $sidebar_2_1_width,
				'margin' => $sidebar_2_1_margin,
			];

			$return['sidebar_2_data'] = [
				'width'  => $sidebar_2_2_width,
				'margin' => $sidebar_2_2_margin,
			];
		}

		return $return;
	}

	/**
	 * Apply inline styling and classes to the layout structure when no sidebars are used.
	 *
	 * @since 5.3
	 * @access public
	 * @return void
	 */
	public function add_no_sidebar_layout_styling() {

		// Check for sidebar location and apply styling to the content or sidebar div.
		if ( ! Avada()->template->has_sidebar() && ! ( ( is_page_template( 'side-navigation.php' ) && 0 !== get_queried_object_id() ) || is_singular( 'tribe_events' ) ) ) {
			add_filter( 'fusion_content_style', [ $this, 'full_width_content_style' ] );

			if ( is_archive() || is_home() ) {
				add_filter( 'fusion_content_class', [ $this, 'full_width_content_class' ] );
			}
		}
	}

	/**
	 * Apply inline styling and classes to the layout structure when sidebars are used.
	 *
	 * @param array $sidebars The sidebars array.
	 * @return void
	 */
	public function add_sidebar_layout_styling( $sidebars ) {

		// Add sidebar class.
		add_filter( 'fusion_sidebar_1_class', [ $this, 'sidebar_class' ] );
		add_filter( 'fusion_sidebar_2_class', [ $this, 'sidebar_class' ] );

		add_filter( 'fusion_sidebar_1_class', [ $this, 'sidebar_1_name_class' ] );
		add_filter( 'fusion_sidebar_2_class', [ $this, 'sidebar_2_name_class' ] );

		// Add sidebar sticky class.
		add_filter( 'fusion_sidebar_1_class', [ $this, 'sidebar_sticky_class' ] );
		add_filter( 'fusion_sidebar_2_class', [ $this, 'sidebar_sticky_class' ] );

		// Check for sidebar location and apply styling to the content or sidebar div.
		if ( ! Avada()->template->has_sidebar() && ! ( ( is_page_template( 'side-navigation.php' ) && 0 !== get_queried_object_id() ) || is_singular( 'tribe_events' ) ) ) {
			add_filter( 'fusion_content_style', [ $this, 'full_width_content_style' ] );

			if ( is_archive() || is_home() ) {
				add_filter( 'fusion_content_class', [ $this, 'full_width_content_class' ] );
			}
		} elseif ( 'left' === $sidebars['position'] ) {
			add_filter( 'fusion_content_style', [ $this, 'float_right_style' ] );
			add_filter( 'fusion_sidebar_1_style', [ $this, 'float_left_style' ] );
			add_filter( 'fusion_sidebar_1_class', [ $this, 'side_nav_left_class' ] );
		} elseif ( 'right' === $sidebars['position'] ) {
			add_filter( 'fusion_content_style', [ $this, 'float_left_style' ] );
			add_filter( 'fusion_sidebar_1_style', [ $this, 'float_right_style' ] );
			add_filter( 'fusion_sidebar_1_class', [ $this, 'side_nav_right_class' ] );
		}

		/**
		 * Page has a single sidebar.
		if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {}
		*/

		// Page has double sidebars.
		if ( Avada()->template->double_sidebars() ) {
			add_filter( 'fusion_content_style', [ $this, 'float_left_style' ] );
			add_filter( 'fusion_sidebar_1_style', [ $this, 'float_left_style' ] );
			add_filter( 'fusion_sidebar_2_style', [ $this, 'float_left_style' ] );

			if ( 'right' === $sidebars['position'] ) {
				add_filter( 'fusion_sidebar_2_class', [ $this, 'side_nav_right_class' ] );
			}
		}

	}

	/**
	 * Changes the responsive sidebar order, if right positioning and dounble sidebars are used..
	 *
	 * @access public
	 * @since 5.7.2
	 * @param array $sidebar_order The ordered array of sidebars.
	 * @return array The changed ordered sidebar array.
	 */
	public function correct_responsive_sidebar_order( $sidebar_order ) {
		if ( isset( $this->sidebars['sidebar_2_data'] ) && 'right' === $this->sidebars['position'] ) {
			foreach ( $sidebar_order as $key => $element ) {
				if ( 'sidebar' === $element ) {
					$sidebar_order[ $key ] = 'sidebar-2';
				} elseif ( 'sidebar-2' === $element ) {
					$sidebar_order[ $key ] = 'sidebar';
				}
			}
		}

		return $sidebar_order;
	}

	/**
	 * Append single sidebar to a page.
	 *
	 * @return void
	 */
	public function append_sidebar_single() {
		get_template_part( 'templates/sidebar', '1' );
	}

	/**
	 * Append double sidebar to a page.
	 *
	 * @return void
	 */
	public function append_sidebar_double() {
		get_template_part( 'templates/sidebar', '1' );
		get_template_part( 'templates/sidebar', '2' );
	}

	/**
	 * Filter to add inline styling.
	 *
	 * @param  string $filter The filter to apply.
	 * @return void
	 */
	public function add_style( $filter ) {
		echo 'style="' . esc_attr( $this->join( $filter ) ) . '"';
	}

	/**
	 * Filter to add class.
	 *
	 * @param  string $filter The filter to apply.
	 * @return void
	 */
	public function add_class( $filter ) {
		echo 'class="' . esc_attr( $this->join( $filter ) ) . '"';
	}

	/**
	 * Filter to add data.
	 *
	 * @since 5.2
	 * @param  string $filter The filter to apply.
	 * @return void
	 */
	public function add_data( $filter ) {
		if ( array_key_exists( $filter, $this->sidebars ) ) {

			foreach ( $this->sidebars[ $filter ] as $key => $value ) {
				echo ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	/**
	 * Full width page inline styling.
	 *
	 * @param  array $styles An array of styles.
	 * @return array
	 */
	public function full_width_content_style( $styles ) {
		$styles[] = 'width: 100%;';
		return $styles;
	}

	/**
	 * Full width class.
	 *
	 * @param  array $classes An array of CSS classes.
	 * @return array
	 */
	public function full_width_content_class( $classes ) {
		$classes[] = 'full-width';
		return $classes;
	}

	/**
	 * Float right styling.
	 *
	 * @param  array $styles An array of styles.
	 * @return array
	 */
	public function float_right_style( $styles ) {
		$styles[] = 'float: right;';
		return $styles;
	}

	/**
	 * Float left styling.
	 *
	 * @param  array $styles An array of styles.
	 * @return array
	 */
	public function float_left_style( $styles ) {
		$styles[] = 'float: left;';
		return $styles;
	}

	/**
	 * Add sidebar class to the sidebars.
	 *
	 * @param  array $classes An array of CSS classes.
	 * @return array
	 */
	public function sidebar_class( $classes ) {
		$classes[] = 'sidebar fusion-widget-area fusion-content-widget-area';
		return $classes;
	}

	/**
	 * Add sidebar name as class for sidebar 1
	 *
	 * @since 4.1
	 * @param array $classes Classes to apply to the sidebar.
	 * @return array $classes Classes to apply to the sidebar including sidebar name.
	 */
	public function sidebar_1_name_class( $classes ) {
		$sidebar_position = 'right';
		$sidebar_name     = ' fusion-default-sidebar';

		if ( 'right' !== $this->sidebars['position'] || ( isset( $this->sidebars['sidebar_2'] ) && '' !== $this->sidebars['sidebar_2'] ) ) {
			$sidebar_position = 'left';
		}

		if ( isset( $this->sidebars['sidebar_1'] ) ) {
			$sidebar_name = ' fusion-' . strtolower( sidebar_generator::name_to_class( $this->sidebars['sidebar_1'] ) );
		}

		$classes[] = 'fusion-sidebar-' . $sidebar_position . $sidebar_name;

		return $classes;
	}

	/**
	 * Add sidebar name as class for sidebar 2
	 *
	 * @since 4.1
	 * @param array $classes Classes to apply to the sidebar.
	 * @return array $classes Classes to apply to the sidebar including sidebar name
	 */
	public function sidebar_2_name_class( $classes ) {
		$classes[] = 'fusion-sidebar-right fusion-' . strtolower( sidebar_generator::name_to_class( $this->sidebars['sidebar_2'] ) );
		return $classes;
	}

	/**
	 * Add sidebar sticky class to the sidebars.
	 *
	 * @since 5.2
	 * @param array $classes Classes to apply to the sidebar.
	 * @return array $classes Classes to apply to the sidebar including sidebar sticky class.
	 */
	public function sidebar_sticky_class( $classes ) {
		$classes_string = implode( ' ', $classes );

		// If sticky param is either not defined yet or set to none, there is nothing to do.
		if ( ! isset( $this->sidebars['sticky'] ) || ! $this->sidebars['sticky'] || 'none' === $this->sidebars['sticky'] ) {
			return $classes;
		} elseif ( 'both' === $this->sidebars['sticky'] ) {
			$classes[] = 'fusion-sticky-sidebar';
		} elseif ( false !== strpos( $classes_string, 'fusion-sidebar-left' ) && 'left' === $this->sidebars['position'] && 'sidebar_one' === $this->sidebars['sticky'] ) {
			$classes[] = 'fusion-sticky-sidebar';
		} elseif ( false !== strpos( $classes_string, 'fusion-sidebar-right' ) && 'right' === $this->sidebars['position'] && 'sidebar_one' === $this->sidebars['sticky'] ) {
			$classes[] = 'fusion-sticky-sidebar';
		} elseif ( false !== strpos( $classes_string, 'fusion-sidebar-left' ) && 'right' === $this->sidebars['position'] && 'sidebar_two' === $this->sidebars['sticky'] ) {
			$classes[] = 'fusion-sticky-sidebar';
		} elseif ( false !== strpos( $classes_string, 'fusion-sidebar-right' ) && 'left' === $this->sidebars['position'] && 'sidebar_two' === $this->sidebars['sticky'] ) {
			$classes[] = 'fusion-sticky-sidebar';
		}

		return $classes;
	}

	/**
	 * Add side nav right class when sidebar position is right
	 *
	 * @param array $classes An array of CSS classes.
	 * @return array
	 */
	public function side_nav_right_class( $classes ) {
		if ( is_page_template( 'side-navigation.php' ) ) {
			$classes[] = 'side-nav-right';
		}
		return $classes;
	}

	/**
	 * Add side nav left class when sidebar position is left.
	 *
	 * @param  array $classes An array of CSS classes.
	 * @return array
	 */
	public function side_nav_left_class( $classes ) {
		if ( is_page_template( 'side-navigation.php' ) ) {
			$classes[] = 'side-nav-left';
		}
		return $classes;
	}

	/**
	 * Get column width of the current page.
	 *
	 * @param integer|string $site_width     A custom site width.
	 * @return integer
	 */
	public function get_content_width( $site_width = 0 ) {
		global $fusion_fwc_type;

		/**
		 * The content width.
		 */
		$options      = get_option( Avada::get_option_name() );
		$c_page_id    = Avada()->fusion_library->get_page_id();
		$page_padding = 0;

		if ( ! $site_width ) {
			$site_width = ( isset( $options['site_width'] ) ) ? $options['site_width'] : '1100px';

			if ( $this->is_current_wrapper_hundred_percent() ) {

				$site_width = '100%';

				// Get 100% Width Left/Right Padding.
				$page_padding = fusion_get_option( 'hundredp_padding' );
				$page_padding = ! $page_padding ? '0' : $page_padding;

				// Section shortcode padding.
				if ( isset( $fusion_fwc_type ) && ! empty( $fusion_fwc_type ) ) {
					if ( Fusion_Sanitize::get_unit( $fusion_fwc_type['padding']['left'] ) === Fusion_Sanitize::get_unit( $fusion_fwc_type['padding']['right'] ) ) {
						$page_padding = ( Fusion_Sanitize::number( $fusion_fwc_type['padding']['left'] ) + Fusion_Sanitize::number( $fusion_fwc_type['padding']['right'] ) ) / 2 . Fusion_Sanitize::get_unit( $fusion_fwc_type['padding']['left'] );
					}
				}

				if ( false !== strpos( $page_padding, '%' ) ) {
					// 100% Width Left/Right Padding is using %.
					$page_padding = Avada_Helper::percent_to_pixels( $page_padding );
				} elseif ( false !== strpos( $page_padding, 'rem' ) ) {
					// 100% Width Left/Right Padding is using rems.
					// Default browser font-size is 16px.
					$page_padding = Fusion_Sanitize::number( $page_padding ) * 16;
				} elseif ( false !== strpos( $page_padding, 'em' ) ) {
					// 100% Width Left/Right Padding is using ems.
					$page_padding = Avada_Helper::ems_to_pixels( $page_padding );
				}
			}
		}

		if ( intval( $site_width ) ) {
			// Site width is using %.
			if ( false !== strpos( $site_width, '%' ) ) {
				$site_width = Avada_Helper::percent_to_pixels( $site_width );
			} elseif ( false !== strpos( $site_width, 'rem' ) ) {
				// Site width is using rems.
				$site_width = Fusion_Sanitize::number( $site_width ) * 16;
			} elseif ( false !== strpos( $site_width, 'em' ) ) {
				// Site width is using ems.
				$site_width = Avada_Helper::ems_to_pixels( $site_width );
			}

			// Subtract side header width from remaining content width.
			if ( 'boxed' === fusion_get_option( 'layout' ) && 'top' !== fusion_get_option( 'header_position' ) ) {
				$site_width = intval( $site_width ) - intval( Avada()->settings->get( 'side_header_width' ) );
			}
		} else {
			// Fallback to 1100px.
			$site_width = 1100;
		}

		$site_width = intval( $site_width ) - 2 * intval( $page_padding );

		/**
		 * Sidebars width.
		 */
		$sidebar_1_width = 0;
		$sidebar_2_width = 0;
		if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {
			if ( 'tribe_events' === get_post_type( $c_page_id ) ) {
				$sidebar_1_width = Avada()->settings->get( 'ec_sidebar_width' );
			} else {
				$sidebar_1_width = Avada()->settings->get( 'sidebar_width' );
			}
		} elseif ( Avada()->template->double_sidebars() ) {
			if ( 'tribe_events' === get_post_type( $c_page_id ) ) {
				$sidebar_1_width = Avada()->settings->get( 'ec_sidebar_2_1_width' );
				$sidebar_2_width = Avada()->settings->get( 'ec_sidebar_2_2_width' );
			} else {
				$sidebar_1_width = Avada()->settings->get( 'sidebar_2_1_width' );
				$sidebar_2_width = Avada()->settings->get( 'sidebar_2_2_width' );
			}
		} elseif ( ! Avada()->template->has_sidebar() && ( is_page_template( 'side-navigation.php' ) || is_singular( 'tribe_events' ) ) ) {
			if ( 'tribe_events' === get_post_type( $c_page_id ) ) {
				$sidebar_1_width = Avada()->settings->get( 'ec_sidebar_width' );
			} else {
				$sidebar_1_width = Avada()->settings->get( 'sidebar_width' );
			}
		}

		$body_font_size      = 16;
		$real_body_font_size = Avada()->settings->get( 'body_typography', 'font-size' );
		if ( 'px' === Fusion_Sanitize::get_unit( $real_body_font_size ) ) {
			$body_font_size = (int) $real_body_font_size;
		}

		if ( $sidebar_1_width ) {
			$sidebar_1_width = Fusion_Sanitize::units_to_px( $sidebar_1_width, $body_font_size, $site_width );
		}

		if ( $sidebar_2_width ) {
			$sidebar_2_width = Fusion_Sanitize::units_to_px( $sidebar_2_width, $body_font_size, $site_width );
		}

		$columns = 1;
		if ( $site_width && $sidebar_1_width && $sidebar_2_width ) {
			$columns = 3;
		} elseif ( $site_width && $sidebar_1_width ) {
			$columns = 2;
		}

		$gutter = ( 1 < $columns ) ? 80 : 0;

		// If we're not using calc() and we've got more than 1 columns, get the gutter from theme-options.
		if ( $gutter && false === strpos( Avada()->settings->get( 'sidebar_gutter' ), 'calc' ) ) {

			// Only single sidebar user single sidebar gutter.
			if ( 2 === $columns ) {
				$gutter = Fusion_Sanitize::units_to_px( Avada()->settings->get( 'sidebar_gutter' ), $body_font_size, $site_width );
			} elseif ( 3 === $columns ) {
				$gutter = Fusion_Sanitize::units_to_px( Avada()->settings->get( 'dual_sidebar_gutter' ), $body_font_size, $site_width );
			}
		}

		$gutter = (int) $gutter;

		// If dual sidebar, we need to multiply gutter by 2.
		if ( 3 === $columns ) {
			$gutter = $gutter * 2;
		}

		$sidebar_1_width = (int) $sidebar_1_width;
		$sidebar_2_width = (int) $sidebar_2_width;

		self::$content_width = $site_width - $sidebar_1_width - $sidebar_2_width - $gutter;

		return self::$content_width;
	}

	/**
	 * Checks is the current page is a 100% width page.
	 *
	 * @param bool          $value   The value from the filter.
	 * @param integer|false $page_id A custom page ID.
	 * @return bool
	 */
	public function is_hundred_percent_template( $value = false, $page_id = false ) {
		if ( ! $page_id ) {
			$page_id = fusion_library()->get_page_id();
		}

		$page_template = '';

		if ( Avada_Helper::is_woocommerce() ) {
			$custom_fields = get_post_custom_values( '_wp_page_template', $page_id );
			$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
		}

		if ( 'tribe_events' === get_post_type( $page_id ) && '100-width.php' === tribe_get_option( 'tribeEventsTemplate', 'default' ) ) {
			$page_template = '100-width.php';
		}

		if (
			'100%' === fusion_library()->get_option( 'site_width' ) ||
			( is_page_template( '100-width.php' ) && $page_id ) ||
			is_page_template( 'blank.php' ) ||
			( '100-width.php' === $page_template && $page_id ) ||
			( fusion_get_option( 'portfolio_width_100', 'portfolio_width_100' ) && is_singular( 'avada_portfolio' ) ) ||
			( fusion_get_option( 'blog_width_100', 'portfolio_width_100' ) && is_singular( 'post' ) ) || 
			( fusion_get_option( 'product_width_100', 'portfolio_width_100' ) && is_singular( 'product' ) ) ||
			( 'yes' === fusion_get_page_option( 'portfolio_width_100', $page_id ) && ! is_singular( [ 'post', 'avada_portfolio' ] ) )
		) {
			return true;
		}
		return false;
	}

	/**
	 * Join the elements
	 *
	 * @param null|string $filter_id      The ID of our filter.
	 * @param string      $sanitize       The function used for sanitization.
	 * @param string      $join_separator What we'll be using to join the items.
	 *
	 * @return string
	 */
	public function join( $filter_id = null, $sanitize = 'esc_attr', $join_separator = ' ' ) {

		// Get the elements using a filter.
		$elements = apply_filters( 'fusion_' . $filter_id, [] );

		// Make sure each element is properly sanitized.
		$elements = array_map( $sanitize, $elements );

		// Make sure there are no duplicate items.
		$elements = array_unique( $elements );

		// Combine the elements of the array and return the combined string.
		return join( $join_separator, $elements );

	}

	/**
	 * Determine if the current wrapper is 100%-wide or not.
	 *
	 * @access public
	 * @return bool
	 */
	public function is_current_wrapper_hundred_percent() {
		if ( $this->is_hundred_percent_template() ) {
			global $fusion_fwc_type;

			if ( ! isset( $fusion_fwc_type ) || ( isset( $fusion_fwc_type ) && is_array( $fusion_fwc_type ) && ( empty( $fusion_fwc_type ) || 'fullwidth' === $fusion_fwc_type['content'] ) ) ) {
				return true;
			}
		}
		return false;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
