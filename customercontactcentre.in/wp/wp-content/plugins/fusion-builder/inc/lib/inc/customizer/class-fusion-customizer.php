<?php
/**
 * Customizer parser.
 * This is a bridge between Avada & Kirki.
 *
 * @package Fusion-Library
 * @since 2.0.0
 */

/**
 * The main customizer class.
 */
class Fusion_Customizer {

	/**
	 * All options.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var array
	 */
	protected $options = [];


	/**
	 * The constructor.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function __construct() {

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		global $wp_customize;
		if ( ! $wp_customize ) {
			return;
		}

		// Add options.
		add_action( 'init', [ $this, 'get_options' ] );
		// Add the Kirki config.
		add_action( 'init', [ $this, 'add_kirki_config' ] );
		// Parse options.
		add_action( 'init', [ $this, 'parse' ], 999 );
		// Enqueue extra JS functions we'll be using.
		add_action( 'customize_preview_init', [ $this, 'customize_preview_init' ], 3 );
		add_filter( 'kirki/postmessage/script', [ $this, 'postmessage_script_extra_functions' ], 20 );

		add_action( 'customize_controls_print_styles', [ $this, 'enqueue_styles' ] );

		$this->customizer_search();
	}

	/**
	 * Init Kirki and add config.
	 *
	 * @access private
	 * @since 2.0.0
	 */
	public function add_kirki_config() {
		Kirki::add_config(
			'fusion',
			[
				'option_type' => 'option',
				'option_name' => Fusion_Settings::get_option_name(),
			]
		);
	}

	/**
	 * Get options.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function get_options() {

		$fusion_builder_options = [];
		$avada_options          = [];

		if ( class_exists( 'Avada_Options' ) ) {
			$avada_options = (array) Avada_Options::get_instance();
		}

		if ( ! isset( $avada_options['sections'] ) ) {
			$avada_options['sections'] = [];
		}

		if ( defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
			if ( ! class_exists( 'Fusion_Builder_Options' ) ) {
				require_once FUSION_BUILDER_PLUGIN_DIR . 'inc/class-fusion-builder-options.php';
			}
			unset( $avada_options['sections']['shortcode_styling'] );
			$fusion_builder_options = (array) Fusion_Builder_Options::get_instance();
		}

		if ( ! isset( $fusion_builder_options['sections'] ) ) {
			$fusion_builder_options['sections'] = [];
		}

		$this->options = array_replace_recursive( $avada_options['sections'], $fusion_builder_options['sections'] );

	}

	/**
	 * The main parser
	 *
	 * @access public
	 */
	public function parse() {

		// Start looping through the sections from the $fusion_sections object.
		foreach ( $this->options as $section ) {

			if ( isset( $section['fields'] ) ) {

				// Determine if this is a panel or a section.
				$is_panel = false;
				foreach ( $section['fields'] as $field ) {
					if ( isset( $field['type'] ) ) {
						if ( 'sub-section' === $field['type'] || 'accordion' === $field['type'] ) {
							$is_panel = true;
						}
					}
				}

				// Add section, and if needed panel.
				$this->add_section( $section );
				if ( $is_panel ) {
					$this->add_panel( $section );
				}

				// Start looping through the section's fields.
				foreach ( $section['fields'] as $field ) {
					if ( isset( $field['type'] ) ) {
						if ( 'sub-section' === $field['type'] || 'accordion' === $field['type'] ) {

							if ( ! isset( $field['id'] ) ) {
								continue;
							}

							// Add this as a section inside the already-created panel.
							$field['panel'] = $section['id'];
							$this->add_section( $field );

							// Make sure we have fields defined before proceeding.
							// We'll need to add these fields to the subsection.
							if ( isset( $field['fields'] ) && is_array( $field['fields'] ) ) {
								foreach ( $field['fields'] as $subfield ) {
									$subfield['section'] = $field['id'];
									$this->create_field( $subfield );
								}
							}
						} else {

							// Add the field.
							$field['section'] = $section['id'];
							$this->create_field( $field );
						}
					}
				}
			}
		}
	}

	/**
	 * Adds a panel.
	 *
	 * @access private
	 * @since 2.0.0
	 * @param array $panel The panel arguments.
	 */
	private function add_panel( $panel ) {

		$panel['title'] = $panel['label'];
		unset( $panel['label'] );
		unset( $panel['icon'] );
		Kirki::add_panel( $panel['id'], $panel );

	}

	/**
	 * Adds a section.
	 *
	 * @access private
	 * @since 2.0.0
	 * @param array $section The section arguments.
	 */
	private function add_section( $section ) {

		$section['title'] = $section['label'];
		unset( $section['label'] );
		unset( $section['icon'] );
		Kirki::add_section( $section['id'], $section );

	}

	/**
	 * Creates a field.
	 *
	 * @access private
	 * @since 2.0.0
	 * @param array $field The field arguments.
	 */
	private function create_field( $field ) {

		// An array of options we don't want in the customizer.
		$skip_options = [
			'colors_important_note_info',
			'scheme_type',
			'color_scheme',
			'custom_color_scheme_options',
		];

		if ( ! isset( $field['id'] ) || ! isset( $field['type'] ) ) {
			return;
		}

		if ( in_array( $field['id'], $skip_options, true ) ) {
			return;
		}
		$field['settings'] = $field['id'];
		unset( $field['id'] );

		// Disable dependencies in the customizer.
		$field['required'] = [];

		if ( isset( $field['required'] ) && ! empty( $field['required'] ) ) {
			$required_class    = ( isset( $field['class'] ) ) ? (string) $field['class'] : false;
			$field['required'] = $this->parse_required_arg( $field['required'], $required_class );
		}

		if ( ! isset( $field['transport'] ) ) {
			$field['transport'] = 'refresh';
		}

		if ( ! isset( $field['output'] ) ) {
			$field['output'] = [];
		}
		$field['output'] = apply_filters( "fusion_options_{$field['settings']}_output", $field['output'] );

		$force_refresh = false;
		if ( isset( $field['output_fields_trigger_change'] ) && ! empty( $field['output_fields_trigger_change'] ) ) {
			$force_refresh = true;
		}

		if ( ! $force_refresh ) {

			// We need this because a lot of styles are added conditionally
			// when 3rd-party plugins are installed.
			foreach ( $field['output'] as $output_key => $output_args ) {
				if ( empty( $output_args ) ) {
					unset( $field['output'][ $output_key ] );
					continue;
				}
				if (
					// Force-refresh if we're changing attributes.
					( isset( $output_args['function'] ) && 'attr' === $output_args['function'] ) ||
					// Force-refresh if we're using js-callbacks (WIP, for now this only works in fusion-panel).
					( isset( $output_args['js_callback'] ) && ! empty( $output_args['js_callback'] ) )
				) {
					$force_refresh = true;
					continue;
				}
			}
			if ( ! empty( $field['output'] ) ) {
				// Set transport to auto.
				$field['transport'] = 'auto';
			}
		}

		if ( isset( $field['partial_refresh'] ) ) {
			$field['transport'] = 'postMessage';
		}

		if ( $force_refresh ) {
			// Force transport method to refresh.
			$field['transport'] = 'refresh';
		}

		switch ( $field['type'] ) {
			case 'color':
			case 'color-alpha':
			case 'radio-buttonset':
			case 'dimension':
			case 'spacing':
			case 'slider':
			case 'select':
			case 'text':
			case 'textarea':
			case 'radio':
			case 'radio-image':
				Kirki::add_field( 'fusion', $field );
				break;

			case 'switch':
			case 'toggle':
				$field['sanitize_callback'] = 'fusion_customizer_sanitize_bool_string';
				Kirki::add_field( 'fusion', $field );
				break;

			case 'info':
				$field['type']    = 'custom';
				$field['default'] = '<div class="fusion-customizer-heading">' . $field['label'] . '</div>';
				$field['label']   = '';
				Kirki::add_field( 'fusion', $field );
				break;
			case 'typography':
				$field['choices']['font-backup']               = true;
				$field['choices']['disable-multiple-variants'] = true;
				Kirki::add_field( 'fusion', $field );
				break;

			case 'preset':
				break;

			case 'custom':
			case 'raw':
				$field['default']     = isset( $field['description'] ) ? $field['description'] : '';
				$field['description'] = '';
				Kirki::add_field( 'fusion', $field );
				break;

			case 'media':
				$field['type']               = 'image';
				$field['choices']['save_as'] = 'array';
				Kirki::add_field( 'fusion', $field );
				break;

			case 'repeater':
				$field['sanitize_callback'] = 'fusion_customizer_sanitize_repeater';
				Kirki::add_field( 'fusion', $field );
				break;

			case 'dimensions':
				$field['type']    = 'spacing';
				$field['choices'] = [
					'labels' => [
						'width'  => esc_html__( 'Width', 'fusion-builder' ),
						'height' => esc_html__( 'Height', 'fusion-builder' ),
					],
				];
				Kirki::add_field( 'fusion', $field );
				break;

			case 'code':
				$field['choices']['theme'] = 'kirki-dark';
				Kirki::add_field( 'fusion', $field );
				break;
		}
	}

	/**
	 * Enqueue script containing extra JS functions.
	 * We'll be using these functions for some postMessage calculations.
	 *
	 * @access public
	 * @since 2.0.0
	 * @param string $script A string we want to append.
	 */
	public function postmessage_script_extra_functions( $script ) {
		$script .= file_get_contents( FUSION_LIBRARY_PATH . '/inc/fusion-app/callbacks.js' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$script .= file_get_contents( FUSION_LIBRARY_PATH . '/inc/customizer/js/extra-functions.js' ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		return $script;
	}

	/**
	 * Enqueue additional scripts/styles in customize_preview_init
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function customize_preview_init() {
		wp_enqueue_script( 'jquery-color' );
	}

	/**
	 * Parses the "required" argument and properly formats it for Kirki.
	 *
	 * @access private
	 * @since 2.0.0
	 * @param array  $args  The contents of the "required" argument.
	 * @param string $class A class defined for some fields to define the conditions and/or relation.
	 * @return array
	 */
	private function parse_required_arg( $args, $class ) {
		$required = [];
		if ( empty( $args ) ) {
			return [];
		}

		switch ( $class ) {
			case 'fusion-or-gutter':
				$required[0] = ( isset( $required[0] ) ) ? $required[0] : [];
				foreach ( $args as $requirement ) {
					$required[0][] = $requirement;
				}
				break;
			case 'fusion-gutter-and-or':
				$required = [
					[
						[ $args[0], $args[1] ],
						$args[2],
					],
				];
				break;
			case 'fusion-gutter-and-or-and':
				$required = [
					[
						[ $args[0], $args[1] ],
						[ $args[2], $args[3] ],
					],
				];
				break;
			case 'fusion-gutter-and-and-or-and':
				$required = [
					[
						[ $args[0], $args[1], $args[2] ],
						[ $args[3], $args[4] ],
					],
				];
				break;
			case 'fusion-gutter-and-and-and-or-and-and':
				$required = [
					[
						[ $args[0], $args[1], $args[2], $args[3] ],
						[ $args[4], $args[5], $args[6] ],
					],
				];
				break;
			case 'fusion-gutter-and-and-or-and-and':
				$required = [
					[
						[ $args[0], $args[1], $args[2] ],
						[ $args[3], $args[4], $args[5] ],
					],
				];
				break;
			default:
				$required = $args;
				if ( $class && $args ) {
					error_log( "Condition not defined for the customizer: {$class}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log( 'Please define it in See Fusion_Customizer::parse_required_arg().' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				}
				break;
		}
		return $required;
	}

	/**
	 * Add custom styles.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function enqueue_styles() {
		global $fusion_library_latest_version;
		wp_register_style( 'fusion-customizer-css', FUSION_LIBRARY_URL . '/inc/customizer/css/customizer-styles.css', [], $fusion_library_latest_version, 'all' );
		wp_enqueue_style( 'fusion-customizer-css' );
	}

	/**
	 * Add search capability to the customizer.
	 *
	 * @access protected
	 * @since 2.0.0
	 */
	protected function customizer_search() {
		if ( ! class_exists( 'Fusion_Customizer_Search' ) ) {
			include_once 'search/class-fusion-customizer-search.php';
		}
		Fusion_Customizer_Search::get_instance();
	}
}
