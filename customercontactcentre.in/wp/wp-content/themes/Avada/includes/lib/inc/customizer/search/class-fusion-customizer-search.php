<?php
/**
 * Adds search functionality to easier locate fields.
 *
 * @package     Kirki
 * @category    Modules
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds script for Search.
 */
class Fusion_Customizer_Search {

	/**
	 * The object instance.
	 *
	 * @static
	 * @access private
	 * @since 3.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * An array containing field identifieds and their labels/descriptions.
	 *
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private $search_content = [];

	/**
	 * The class constructor
	 *
	 * @access protected
	 * @since 3.0.0
	 */
	protected function __construct() {
		// Add the custom section.
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		// Enqueue styles and scripts.
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_controls_print_footer_scripts' ] );
	}

	/**
	 * Gets an instance of this object.
	 * Prevents duplicate instances which avoid artefacts and improves performance.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return object
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Parses fields and adds their labels and descriptions to the
	 * object's $search_content property.
	 *
	 * @access private
	 * @since 3.0.0
	 */
	private function parse_fields() {

		$fields = Kirki::$fields;
		foreach ( $fields as $field ) {
			if ( ! isset( $field['settings'] ) || ! isset( $field['type'] ) ) {
				continue;
			}
			if ( 'custom' === $field['type'] || 'raw' === $field['type'] ) {
				continue;
			}
			$id                     = str_replace( '[', '-', str_replace( ']', '', $field['settings'] ) );
			$this->search_content[] = [
				'id'          => $id,
				'settings'    => $field['settings'],
				'label'       => ( isset( $field['label'] ) && ! empty( $field['label'] ) ) ? esc_html( $field['label'] ) : '',
				'description' => ( isset( $field['description'] ) && ! empty( $field['description'] ) ) ? esc_html( $field['description'] ) : '',
			];
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function customize_controls_print_footer_scripts() {
		global $fusion_library_latest_version;

		$this->parse_fields();

		$vars = [
			'fields' => $this->search_content,
			'button' => '<a class="fusion-customize-controls-search" href="#"><span class="screen-reader-text">' . esc_html__( 'Search', 'Avada' ) . '</span></a>',
			'form'   => '<div class="fusion-search-form-wrapper hidden"><input type="text" id="fusion-search"></div><div class="fusion-search-results"></div>',
		];

		wp_enqueue_script( 'fuse', trailingslashit( FUSION_LIBRARY_URL ) . 'inc/customizer/search/fuse.min.js', [ 'jquery' ], $fusion_library_latest_version, false );
		wp_enqueue_script( 'fusion-search', trailingslashit( FUSION_LIBRARY_URL ) . 'inc/customizer/search/search.js', [ 'jquery', 'fuse' ], $fusion_library_latest_version, false );
		wp_localize_script( 'fusion-search', 'fusionFieldsSearch', $vars );
		wp_enqueue_style( 'fusion-search', trailingslashit( FUSION_LIBRARY_URL ) . 'inc/customizer/search/search.css', [], $fusion_library_latest_version );

	}

	/**
	 * Adds the section to the customizer.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param object $wp_customize The customizer object.
	 */
	public function customize_register( $wp_customize ) {

		// Include the custom search section.
		if ( ! class_exists( 'Fusion_Customizer_Search_Section' ) ) {
			include_once 'class-fusion-customizer-search-section.php';
		}

		// Add section.
		$wp_customize->add_section(
			new Fusion_Customizer_Search_Section(
				$wp_customize,
				'fusion_search_section',
				[
					'title'    => esc_html__( 'Search', 'Avada' ),
					'priority' => 0,
				]
			)
		);

		// Add control.
		$wp_customize->add_control(
			'fusion_search',
			[
				'label'       => '',
				'type'        => 'text',
				'input_attrs' => [
					'placeholder' => esc_html__( 'Search Controls', 'Avada' ),
				],
				'section'     => 'fusion_search_section',
				'settings'    => [],
			]
		);
	}
}
