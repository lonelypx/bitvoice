<?php
/**
 * The main Fusion library object.
 *
 * @package Fusion-Library
 * @since 1.0.0
 */

/**
 * The main Fusion library object.
 */
class Fusion {

	/**
	 * The one, true instance of the object.
	 *
	 * @static
	 * @access public
	 * @var null|object
	 */
	public static $instance = null;

	/**
	 * The current page ID.
	 *
	 * @access public
	 * @var bool|int
	 */
	public static $c_page_id = false;

	/**
	 * An instance of the Fusion_Images class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Images
	 */
	public $images;

	/**
	 * An instance of the Fusion_Multilingual class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Multilingual
	 */
	public $multilingual;

	/**
	 * An instance of the Fusion_Scripts class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Scripts
	 */
	public $scripts;

	/**
	 * An instance of the Fusion_Panel class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Scripts
	 */
	public $panel;

	/**
	 * An instance of the Fusion_Dynamic_JS class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Dynamic_JS
	 */
	public $dynamic_js;

	/**
	 * An instance of the Fusion_Font_Awesome class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Font_Awesome
	 */
	public $fa;

	/**
	 * Fusion_Social_Sharing.
	 *
	 * @access public
	 * @since 1.9.2
	 * @var object
	 */
	public $social_sharing;

	/**
	 * An instance of the Fusion_Media_Query_Scripts class.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var object Fusion_Media_Query_Scripts
	 */
	public $mq_scripts;

	/**
	 * The class constructor
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'set_page_id' ] );
		add_action( 'plugins_loaded', [ $this, 'multilingual_data' ] );

		if ( ! defined( 'AVADA_VERSION' ) ) {
			$this->images = new Fusion_Images();
		}
		$this->sanitize       = new Fusion_Sanitize();
		$this->scripts        = new Fusion_Scripts();
		$this->dynamic_js     = new Fusion_Dynamic_JS();
		$this->mq_scripts     = new Fusion_Media_Query_Scripts();
		$this->fa             = new Fusion_Font_Awesome();
		$this->social_sharing = new Fusion_Social_Sharing();

		if ( $this->supported_plugins_changed() && class_exists( 'Fusion_Cache' ) ) {
			$fusion_cache = new Fusion_Cache();
			$fusion_cache->reset_all_caches();
		}

		if ( is_admin() ) {
			new Fusion_Privacy();
		}

		add_action( 'admin_body_class', [ $this, 'admin_body_class' ] );

		add_action( 'wp_head', [ $this, 'add_analytics_code' ], 10000 );
	}

	/**
	 * Access the single instance of this class.
	 *
	 * @return Fusion
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Gets the current page ID.
	 *
	 * @return string The current page ID.
	 */
	public function get_page_id() {
		if ( ! self::$c_page_id ) {
			$this->set_page_id();
		}
		return apply_filters( 'fusion-page-id', self::$c_page_id ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
	}

	/**
	 * Sets the current page ID.
	 *
	 * @uses self::c_page_id
	 */
	public function set_page_id() {
		if ( ! self::$c_page_id ) {
			self::$c_page_id = self::c_page_id();
		}
	}

	/**
	 * Gets the current page ID.
	 *
	 * @return bool|int
	 */
	private static function c_page_id() {

		if ( get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) && is_home() ) {
			return get_option( 'page_for_posts' );
		}

		$c_page_id = get_queried_object_id();
		if ( ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() ) || ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) ) {
			$page_id   = isset( $_POST['post_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
			$c_page_id = $page_id ? $page_id : $c_page_id;
		}

		// The WooCommerce shop page.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && is_shop() ) {
			return get_option( 'woocommerce_shop_page_id' );
		}
		// The WooCommerce product_cat taxonomy page.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && ( ! is_shop() && ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) ) {
			return $c_page_id . '-archive'; // So that other POs do not apply to arhives if post ID matches.
		}
		// The homepage.
		if ( 'posts' === get_option( 'show_on_front' ) && is_home() ) {
			return $c_page_id;
		}
		if ( ! is_singular() && is_archive() ) {
			return $c_page_id . '-archive'; // So that other POs do not apply to arhives if post ID matches.
		}
		if ( ! is_singular() ) {
			return false;
		}
		return $c_page_id;
	}
	/**
	 * Gets the value of a theme option.
	 *
	 * @static
	 * @access public
	 * @param string|null               $option  The option.
	 * @param string|false              $subset  The sub-option in case of an array.
	 * @param string|array|null|boolean $default The default fallback value.
	 */
	public function get_option( $option = null, $subset = false, $default = null ) {

		global $fusion_settings;
		if ( ! $fusion_settings ) {
			$fusion_settings = Fusion_Settings::get_instance();
		}
		return $fusion_settings->get( $option, $subset, $default );
	}

	/**
	 * Check if the supported plugins array has changed.
	 * If a supported plugin was activated or deactivated
	 * we should reset all caches.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @return bool True if changed, false if unchanged.
	 */
	protected function supported_plugins_changed() {
		$classes_to_check   = [
			'WPCF7',
			'bbPress',
			'WooCommerce',
			'Tribe__Events__Main',
		];
		$constants_to_check = [
			'LS_PLUGIN_VERSION',
			'RS_PLUGIN_PATH',
		];

		$supported_saved    = get_option( 'fusion_supported_plugins_active', [] );
		$supported_detected = [];
		foreach ( $classes_to_check as $class ) {
			if ( class_exists( $class ) ) {
				$supported_detected[] = $class;
			}
		}
		foreach ( $constants_to_check as $constant ) {
			if ( defined( $constant ) ) {
				$supported_detected[] = $constant;
			}
		}
		if ( $supported_detected !== $supported_saved ) {
			update_option( 'fusion_supported_plugins_active', $supported_detected );
			return true;
		}
		return false;
	}

	/**
	 * Adds classes to the <body> element using admin_body_class filter.
	 *
	 * @access public
	 * @since 1.3.0
	 * @param string $classes The CSS classes.
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		global $wp_version;
		if ( version_compare( $wp_version, '4.9-beta', '<' ) ) {
			$classes .= ' fusion-colorpicker-legacy ';
		}
		return $classes;
	}

	/**
	 * Adds analytics code.
	 *
	 * @access public
	 * @since 1.9.2
	 * @return void
	 */
	public function add_analytics_code() {
		/**
		 * The setting below is not sanitized. In order to be able to take advantage of this,
		 * a user would have to gain access to the database or the filesystem to add a new filter,
		 * in which case this is the least on your worries.
		 */
		echo apply_filters( 'fusion_google_analytics', $this->get_option( 'google_analytics' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Add Multilingual Data.
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function multilingual_data() {
		$this->multilingual = new Fusion_Multilingual();
	}
}
