<?php
/**
 * Register default scripts.
 *
 * @package Fusion-Library
 * @since 2.0.0
 */

/**
 * Registers scripts.
 */
class Fusion_Media_Query_Scripts {

	/**
	 * The media-query assets.
	 *
	 * @static
	 * @access public
	 * @since 5.6
	 * @var array
	 */
	public static $media_query_assets = [];

	/**
	 * The class construction
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_media_query_styles' ], 900 );
		add_filter( 'fusion_dynamic_css_final', [ $this, 'compile_media_query_styles' ], 999 );
		add_action( 'after_setup_theme', [ $this, 'combine_media_query_files' ] );
	}

	/**
	 * Get combine media-query files.
	 *
	 * @access public
	 * @since 5.6
	 * @return void
	 */
	public function combine_media_query_files() {

		if ( ! isset( $_GET['action'] ) || 'fusion-get-styles' !== sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		// Output as CSS file.
		header( 'Content-type: text/css', true );

		$styles = [];
		if ( isset( $_GET['mq'] ) ) { // phpcs:ignore WordPress.Security
			$styles = explode( ',', $_GET['mq'] ); // phpcs:ignore WordPress.Security
		}

		foreach ( $styles as $style ) {
			$style = trim( $style );
			if ( class_exists( 'Avada' ) ) {
				if ( file_exists( Avada::$template_dir_path . "/assets/css/media/{$style}.min.css" ) ) {
					include_once Avada::$template_dir_path . "/assets/css/media/{$style}.min.css";
				} elseif ( file_exists( Avada::$template_dir_path . "/assets/css/media/{$style}.css" ) ) {
					include_once Avada::$template_dir_path . "/assets/css/media/{$style}.css";
				}
			}
			if ( defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
				if ( file_exists( FUSION_BUILDER_PLUGIN_DIR . "/assets/css/media/{$style}.min.css" ) ) {
					include_once FUSION_BUILDER_PLUGIN_DIR . "/assets/css/media/{$style}.min.css";
				} elseif ( file_exists( FUSION_BUILDER_PLUGIN_DIR . "/assets/css/media/{$style}.css" ) ) {
					include_once FUSION_BUILDER_PLUGIN_DIR . "/assets/css/media/{$style}.css";
				}
			}
		}
		exit();
	}

	/**
	 * Adds media-query styles to the compiler if needed.
	 *
	 * @access public
	 * @since 5.6
	 * @param string $styles The css styles where we'll be adding our compiled styles.
	 * @return string
	 */
	public function compile_media_query_styles( $styles ) {

		// No reason to proceed any further if we're including the files inside the compiler.
		if ( '1' === Fusion_Settings::get_instance()->get( 'media_queries_async' ) ) {
			return $styles;
		}
		foreach ( self::$media_query_assets as $asset ) {

			// The file-path.
			$path = ( defined( 'FUSION_BUILDER_PLUGIN_URL' ) && defined( 'FUSION_BUILDER_PLUGIN_DIR' ) && false !== strpos( $asset[1], FUSION_BUILDER_PLUGIN_URL ) )
				? str_replace( FUSION_BUILDER_PLUGIN_URL, FUSION_BUILDER_PLUGIN_DIR, $asset[1] )
				: str_replace( get_template_directory_uri(), get_template_directory(), $asset[1] );
			$path = wp_normalize_path( $path );

			// Add the contents of the file to $styles.
			$styles .= '@media ' . $asset[4] . '{';
			if ( file_exists( $path ) ) {
				$styles .= file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			}
			$styles .= '}';
		}
		return $styles;
	}

	/**
	 * Enqueues media-query styles if needed.
	 *
	 * @access public
	 * @since 5.6
	 * @return void
	 */
	public function enqueue_media_query_styles() {
		// No reason to proceed any further if we're including the files inside the compiler.
		if ( '0' === Fusion_Settings::get_instance()->get( 'media_queries_async' ) ) {
			return;
		}
		$media_queries = [];
		foreach ( self::$media_query_assets as $asset ) {
			if ( ! isset( $media_queries[ $asset[4] ] ) ) {
				$media_queries[ $asset[4] ] = [];
			}
			$media_queries[ $asset[4] ][] = $asset;
		}

		foreach ( $media_queries as $media_query ) {
			if ( ! isset( $media_query[1] ) ) {
				// We only have 1 asset for this media-query. Enqueue it.
				wp_enqueue_style( $media_query[0][0], $media_query[0][1], $media_query[0][2], $media_query[0][3], $media_query[0][4] );
				continue;
			}

			$handles = [];
			$paths   = [];
			$deps    = [];
			$ver     = '';
			$query   = '';
			foreach ( $media_query as $asset ) {

				if ( defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
					// If we're in the builder we need to add each file separately.
					if ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() ) {
						wp_enqueue_style(
							$asset[0],
							str_replace( FUSION_BUILDER_PLUGIN_DIR, FUSION_BUILDER_PLUGIN_URL, $asset[1] ),
							array_merge( $deps, $asset[2] ),
							$asset[3],
							$asset[4]
						);
						continue;
					}
				}

				$handles[] = $asset[0];
				$paths[]   = str_replace( [ '.min.min.css', '.min.css', '.css' ], '', str_replace( [ get_template_directory_uri() . '/assets/css/media/', FUSION_BUILDER_PLUGIN_DIR . 'assets/css/media/' ], '', $asset[1] ) );
				$deps      = array_merge( $deps, $asset[2] );
				$ver       = $asset[3];
				$query     = $asset[4];
			}

			if ( empty( $handles ) ) {
				continue;
			}

			$handle = 'fusion-' . str_replace( 'fusion-', '-', implode( '-', $handles ) );
			$handle = str_replace( [ '_', '--' ], '-', $handle );
			$url    = add_query_arg(
				[
					'action' => 'fusion-get-styles',
					'mq'     => implode( ',', array_unique( $paths ) ),
				],
				get_site_url()
			);

			wp_enqueue_style( $handle, $url, $deps, $ver, $query );
		}
	}

	/**
	 * Get a media-query using its key.
	 *
	 * @static`
	 * @access public
	 * @since 2.0
	 * @param string $key The media-query key.
	 * @return string     The media-query.
	 */
	public static function get_media_query_from_key( $key ) {

		// Get the side_header_breakpoint.
		$side_header_breakpoint = 800;
		if ( class_exists( 'Avada' ) ) {
			$side_header_breakpoint = Avada()->settings->get( 'side_header_break_point' );
		}
		if ( ! $side_header_breakpoint ) {
			$side_header_breakpoint = 800;
		}

		// Responsive mode.
		$side_header_width = 0;
		if ( class_exists( 'Avada' ) ) {
			$side_header_width = ( 'top' === fusion_get_option( 'header_position' ) ) ? 0 : (int) Avada()->settings->get( 'side_header_width' );
		}

		// Grid System.
		$main_break_point = 1000;
		if ( class_exists( 'Avada' ) ) {
			$main_break_point = (int) Avada()->settings->get( 'grid_main_break_point' );
		}
		$breakpoint_range = ( 640 < $main_break_point ) ? $main_break_point - 640 : 360;

		// Get content_break_point.
		$content_break_point = 800;
		if ( class_exists( 'Avada' ) ) {
			$content_break_point = (int) Avada()->settings->get( 'content_break_point' );
		}

		// Get sidebar_break_point.
		$sidebar_break_point = 800;
		if ( class_exists( 'Avada' ) ) {
			$sidebar_break_point = (int) Avada()->settings->get( 'sidebar_break_point' );
		}

		// Columns.
		$breakpoint_interval = (int) ( $breakpoint_range / 5 );

		$six_columns_breakpoint   = $main_break_point + $side_header_width;
		$five_columns_breakpoint  = $six_columns_breakpoint - $breakpoint_interval;
		$four_columns_breakpoint  = $five_columns_breakpoint - $breakpoint_interval;
		$three_columns_breakpoint = $four_columns_breakpoint - $breakpoint_interval;
		$two_columns_breakpoint   = $three_columns_breakpoint - $breakpoint_interval;
		$one_column_breakpoint    = $two_columns_breakpoint - $breakpoint_interval;

		switch ( $key ) {
			case 'fusion-max-1c':
				return self::get_media_query(
					[
						'max-width' => $one_column_breakpoint . 'px',
					]
				);
			case 'fusion-max-2c':
				return self::get_media_query(
					[
						'max-width' => $two_columns_breakpoint . 'px',
					]
				);
			case 'fusion-min-2c-max-3c':
				return self::get_media_query(
					[
						'min-width' => $two_columns_breakpoint . 'px',
						'max-width' => $three_columns_breakpoint . 'px',
					]
				);
			case 'fusion-min-3c-max-4c':
				return self::get_media_query(
					[
						'min-width' => $three_columns_breakpoint . 'px',
						'max-width' => $four_columns_breakpoint . 'px',
					]
				);
			case 'fusion-min-4c-max-5c':
				return self::get_media_query(
					[
						'min-width' => $four_columns_breakpoint . 'px',
						'max-width' => $five_columns_breakpoint . 'px',
					]
				);
			case 'fusion-min-5c-max-6c':
				return self::get_media_query(
					[
						'min-width' => $five_columns_breakpoint . 'px',
						'max-width' => $six_columns_breakpoint . 'px',
					]
				);
			case 'fusion-min-shbp':
				return self::get_media_query(
					[
						'min-width' => $side_header_breakpoint . 'px',
					]
				);
			case 'fusion-max-shbp':
				return self::get_media_query(
					[
						'max-width' => $side_header_breakpoint . 'px',
					]
				);
			case 'fusion-max-sh-shbp':
				return self::get_media_query(
					[
						'max-width' => ( $side_header_width + $side_header_breakpoint ) . 'px',
					]
				);
			case 'fusion-min-768-max-1024':
				return self::get_media_query(
					[
						'min-device-width' => '768px',
						'max-device-width' => '1024px',
					]
				);
			case 'fusion-min-768-max-1024-p':
				return self::get_media_query(
					[
						'min-device-width' => '768px',
						'max-device-width' => '1024px',
						'orientation'      => 'portrait',
					]
				);
			case 'fusion-min-768-max-1024-l':
				return self::get_media_query(
					[
						'min-device-width' => '768px',
						'max-device-width' => '1024px',
						'orientation'      => 'landscape',
					]
				);
			case 'fusion-max-sh-cbp':
				return self::get_media_query(
					[
						'max-width' => ( $side_header_width + $content_break_point ) . 'px',
					]
				);
			case 'fusion-max-sh-sbp':
				return self::get_media_query(
					[
						'max-width' => ( $side_header_width + $sidebar_break_point ) . 'px',
					]
				);
			case 'fusion-max-sh-640':
				return self::get_media_query(
					[
						'max-width' => $side_header_width + 640 . 'px',
					]
				);
			case 'fusion-max-shbp-18':
				return self::get_media_query(
					[
						'max-width' => $side_header_breakpoint - 18 . 'px',
					]
				);
			case 'fusion-max-shbp-32':
				return self::get_media_query(
					[
						'max-width' => $side_header_breakpoint - 32 . 'px',
					]
				);
			case 'fusion-min-sh-cbp':
				return self::get_media_query(
					[
						'min-width' => ( $side_header_width + $content_break_point ) . 'px',
					]
				);
			case 'fusion-max-640':
				return self::get_media_query(
					[
						'max-device-width' => '640px',
					]
				);
			case 'fusion-max-768':
				return self::get_media_query(
					[
						'max-width' => '768px',
					]
				);
			case 'fusion-max-782':
				return self::get_media_query(
					[
						'max-width' => '782px',
					]
				);
		}
	}

	/**
	 * Calculates media-queries.
	 *
	 * @static
	 * @access public
	 * @since 5.4
	 * @param array  $args      An array of arguments.
	 * @param string $context   Example: 'only screen'.
	 * @param bool   $add_media Whether we should prepend "@media" or not.
	 * @return string
	 */
	public static function get_media_query( $args, $context = 'only screen', $add_media = false ) {

		$master_query_array = [];
		$query_array        = [ $context ];
		$query              = '';
		foreach ( $args as $what => $when ) {
			// If an array then we have multiple media-queries here
			// and we need to process each one separately.
			if ( is_array( $when ) ) {
				$query_array = [ $context ];
				foreach ( $when as $sub_what => $sub_when ) {
					// Make sure pixels are integers.
					$sub_when      = ( false !== strpos( $sub_when, 'px' ) && false === strpos( $sub_when, 'dppx' ) ) ? absint( $sub_when ) . 'px' : $sub_when;
					$query_array[] = "({$sub_what}: $sub_when)";
				}
				$master_query_array[] = implode( ' and ', $query_array );
				continue;
			}
			// Make sure pixels are integers.
			$when          = ( false !== strpos( $when, 'px' ) && false === strpos( $when, 'dppx' ) ) ? absint( $when ) . 'px' : $when;
			$query_array[] = "({$what}: $when)";
		}

		// If we've got multiple queries, then need to be separated using a comma.
		if ( ! empty( $master_query_array ) ) {
			$query = implode( ', ', $master_query_array );
		}
		// If we don't have multiple queries we need to separate arguments with "and".
		$query = ( ! $query ) ? implode( ' and ', $query_array ) : $query;

		if ( $add_media ) {
			return '@media ' . $query;
		}
		return $query;
	}
}
