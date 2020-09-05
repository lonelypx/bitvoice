<?php
/**
 * Add an element to fusion-builder.
 *
 * @package fusion-builder
 * @since 1.0
 */

if ( fusion_is_element_enabled( 'fusion_title' ) ) {

	if ( ! class_exists( 'FusionSC_Title' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @since 1.0
		 */
		class FusionSC_Title extends Fusion_Element {

			/**
			 * Title counter.
			 *
			 * @access protected
			 * @since 1.9
			 * @var integer
			 */
			protected $title_counter = 1;

			/**
			 * An array of the shortcode arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 */
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_title-shortcode', [ $this, 'attr' ] );
				add_filter( 'fusion_attr_title-shortcode-heading', [ $this, 'heading_attr' ] );
				add_filter( 'fusion_attr_title-shortcode-sep', [ $this, 'sep_attr' ] );

				add_shortcode( 'fusion_title', [ $this, 'render' ] );

			}

			/**
			 * Gets the default values.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function get_element_defaults() {

				global $fusion_settings;

				return [
					'hide_on_mobile'       => fusion_builder_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
					'content_align'        => 'left',
					'font_size'            => '',
					'letter_spacing'       => '',
					'line_height'          => '',
					'margin_bottom'        => $fusion_settings->get( 'title_margin', 'bottom' ),
					'margin_bottom_mobile' => $fusion_settings->get( 'title_margin_mobile', 'bottom' ),
					'margin_top'           => $fusion_settings->get( 'title_margin', 'top' ),
					'margin_top_mobile'    => $fusion_settings->get( 'title_margin_mobile', 'top' ),
					'sep_color'            => $fusion_settings->get( 'title_border_color' ),
					'size'                 => 1,
					'style_tag'            => '',
					'style_type'           => $fusion_settings->get( 'title_style_type' ),
					'text_color'           => '',
				];
			}

			/**
			 * Maps settings to param variables.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function settings_to_params() {
				return [
					'title_margin[top]'           => 'margin_top',
					'title_margin[bottom]'        => 'margin_bottom',
					'title_margin_mobile[top]'    => 'margin_top_mobile',
					'title_margin_mobile[bottom]' => 'margin_bottom_mobile',
					'title_border_color'          => 'sep_color',
					'title_style_type'            => 'style_type',
				];
			}

			/**
			 * Used to set any other variables for use on front-end editor template.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function get_element_extras() {
				$fusion_settings = fusion_get_fusion_settings();
				return [
					'content_break_point' => $fusion_settings->get( 'content_break_point' ),
				];
			}

			/**
			 * Maps settings to extra variables.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function settings_to_extras() {

				return [
					'content_break_point' => 'content_break_point',
				];
			}

			/**
			 * Render the shortcode
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render( $args, $content = '' ) {

				global $fusion_settings;

				$defaults = FusionBuilder::set_shortcode_defaults( self::get_element_defaults(), $args, 'fusion_title' );
				$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'fusion_title', $args );

				$defaults['margin_top']           = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_top'], 'px' );
				$defaults['margin_bottom']        = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_bottom'], 'px' );
				$defaults['margin_top_mobile']    = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_top_mobile'], 'px' );
				$defaults['margin_bottom_mobile'] = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_bottom_mobile'], 'px' );

				extract( $defaults );

				$this->args = $defaults;

				if ( 1 === count( explode( ' ', $this->args['style_type'] ) ) ) {
					$style_type .= ' solid';
				}

				if ( ! $this->args['style_type'] || 'default' === $this->args['style_type'] ) {
					$this->args['style_type'] = $style_type = $fusion_settings->get( 'title_style_type' );
				}

				// Make sure the title text is not wrapped with an unattributed p tag.
				$content = preg_replace( '!^<p>(.*?)</p>$!i', '$1', trim( $content ) );

				if ( false !== strpos( $style_type, 'underline' ) || false !== strpos( $style_type, 'none' ) ) {

					$html = sprintf(
						'<div %s><h%s %s>%s</h%s></div>',
						FusionBuilder::attributes( 'title-shortcode' ),
						$size,
						FusionBuilder::attributes( 'title-shortcode-heading' ),
						do_shortcode( $content ),
						$size
					);

				} else {

					if ( 'right' === $this->args['content_align'] ) {

						$html = sprintf(
							'<div %s><div %s><div %s></div></div><h%s %s>%s</h%s></div>',
							FusionBuilder::attributes( 'title-shortcode' ),
							FusionBuilder::attributes( 'title-sep-container' ),
							FusionBuilder::attributes( 'title-shortcode-sep' ),
							$size,
							FusionBuilder::attributes( 'title-shortcode-heading' ),
							do_shortcode( $content ),
							$size
						);
					} elseif ( 'center' === $this->args['content_align'] ) {

						$html = sprintf(
							'<div %s><div %s><div %s></div></div><h%s %s>%s</h%s><div %s><div %s></div></div></div>',
							FusionBuilder::attributes( 'title-shortcode' ),
							FusionBuilder::attributes( 'title-sep-container title-sep-container-left' ),
							FusionBuilder::attributes( 'title-shortcode-sep' ),
							$size,
							FusionBuilder::attributes( 'title-shortcode-heading' ),
							do_shortcode( $content ),
							$size,
							FusionBuilder::attributes( 'title-sep-container title-sep-container-right' ),
							FusionBuilder::attributes( 'title-shortcode-sep' )
						);

					} else {

						$html = sprintf(
							'<div %s><h%s %s>%s</h%s><div %s><div %s></div></div></div>',
							FusionBuilder::attributes( 'title-shortcode' ),
							$size,
							FusionBuilder::attributes( 'title-shortcode-heading' ),
							do_shortcode( $content ),
							$size,
							FusionBuilder::attributes( 'title-sep-container' ),
							FusionBuilder::attributes( 'title-shortcode-sep' )
						);
					}
				}

				$style = '';
				if ( ! ( '' === $this->args['margin_top_mobile'] && '' === $this->args['margin_bottom_mobile'] ) && ! ( '0px' === $this->args['margin_top_mobile'] && '20px' === $this->args['margin_bottom_mobile'] ) ) {
					$style  = '<style type="text/css">';
					$style .= '@media only screen and (max-width:' . $fusion_settings->get( 'content_break_point' ) . 'px) {';
					$style .= '.fusion-title.fusion-title-' . $this->title_counter . '{margin-top:' . $defaults['margin_top_mobile'] . '!important;margin-bottom:' . $defaults['margin_bottom_mobile'] . '!important;}';
					$style .= '}';
					$style .= '</style>';
				}

				$html = $style . $html;

				$this->title_counter++;

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function attr() {

				$attr = fusion_builder_visibility_atts(
					$this->args['hide_on_mobile'],
					[
						'class' => 'fusion-title title fusion-title-' . $this->title_counter,
						'style' => '',
					]
				);

				if ( false !== strpos( $this->args['style_type'], 'underline' ) ) {
					$styles = explode( ' ', $this->args['style_type'] );

					foreach ( $styles as $style ) {
						$attr['class'] .= ' sep-' . $style;
					}

					if ( $this->args['sep_color'] ) {
						$attr['style'] = 'border-bottom-color:' . $this->args['sep_color'] . ';';
					}
				} elseif ( false !== strpos( $this->args['style_type'], 'none' ) ) {
					$attr['class'] .= ' fusion-sep-none';
				}

				if ( 'center' === $this->args['content_align'] ) {
					$attr['class'] .= ' fusion-title-center';
				}

				$title_size = 'two';
				if ( '1' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'one';
				} elseif ( '2' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'two';
				} elseif ( '3' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'three';
				} elseif ( '4' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'four';
				} elseif ( '5' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'five';
				} elseif ( '6' == $this->args['size'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					$title_size = 'six';
				}

				$attr['class'] .= ' fusion-title-size-' . $title_size;

				if ( $this->args['font_size'] ) {
					$attr['style'] .= 'font-size:' . fusion_library()->sanitize->get_value_with_unit( $this->args['font_size'] ) . ';';
				}

				if ( $this->args['margin_top'] ) {
					$attr['style'] .= 'margin-top:' . $this->args['margin_top'] . ';';
				}

				if ( $this->args['margin_bottom'] ) {
					$attr['style'] .= 'margin-bottom:' . $this->args['margin_bottom'] . ';';
				}

				if ( '' === $this->args['margin_top'] && '' === $this->args['margin_bottom'] ) {
					$attr['style'] .= ' margin-top:0px; margin-bottom:0px';
					$attr['class'] .= ' fusion-title-default-margin';
				}

				if ( $this->args['class'] ) {
					$attr['class'] .= ' ' . $this->args['class'];
				}

				if ( $this->args['id'] ) {
					$attr['id'] = $this->args['id'];
				}

				return $attr;

			}

			/**
			 * Builds the heading attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function heading_attr() {

				$attr = [
					'class' => 'title-heading-' . $this->args['content_align'],
					'style' => '',
				];

				if ( '' !== $this->args['margin_top'] || '' !== $this->args['margin_bottom'] ) {
					$attr['style'] .= 'margin:0;';
				}

				if ( $this->args['font_size'] ) {
					$attr['style'] .= 'font-size:1em;';
				}

				if ( $this->args['line_height'] ) {
					$attr['style'] .= 'line-height:' . fusion_library()->sanitize->size( $this->args['line_height'] ) . ';';
				}

				if ( $this->args['letter_spacing'] ) {
					$attr['style'] .= 'letter-spacing:' . fusion_library()->sanitize->get_value_with_unit( $this->args['letter_spacing'] ) . ';';
				}

				if ( $this->args['text_color'] ) {
					$attr['style'] .= 'color:' . fusion_library()->sanitize->color( $this->args['text_color'] ) . ';';
				}

				if ( $this->args['style_tag'] ) {
					$attr['style'] .= $this->args['style_tag'];
				}

				return $attr;

			}

			/**
			 * Builds the separator attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function sep_attr() {

				$attr = [
					'class' => 'title-sep',
				];

				$styles = explode( ' ', $this->args['style_type'] );

				foreach ( $styles as $style ) {
					$attr['class'] .= ' sep-' . $style;
				}

				if ( $this->args['sep_color'] ) {
					$attr['style'] = 'border-color:' . $this->args['sep_color'] . ';';
				}

				return $attr;

			}

			/**
			 * Builds the dynamic styling.
			 *
			 * @access public
			 * @since 1.1
			 * @return array
			 */
			public function add_styling() {

				global $wp_version, $content_media_query, $six_fourty_media_query, $three_twenty_six_fourty_media_query, $ipad_portrait_media_query, $fusion_settings, $dynamic_css_helpers;

				$main_elements = apply_filters( 'fusion_builder_element_classes', [ '.fusion-title' ], '.fusion-title' );
				$top_margin    = fusion_library()->sanitize->size( $fusion_settings->get( 'title_margin_mobile', 'top' ) ) . '!important';
				$bottom_margin = fusion_library()->sanitize->size( $fusion_settings->get( 'title_margin_mobile', 'bottom' ) ) . '!important';

				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $main_elements ) ]['margin-top']          = $top_margin;
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $main_elements ) ]['margin-bottom']       = $bottom_margin;
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $main_elements ) ]['margin-top']    = $top_margin;
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $main_elements ) ]['margin-bottom'] = $bottom_margin;

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, ' .title-sep' ),
					$dynamic_css_helpers->map_selector( $main_elements, '.sep-underline' )
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['border-color'] = fusion_library()->sanitize->color( $fusion_settings->get( 'title_border_color' ) );

				return $css;

			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections Title settings.
			 */
			public function add_options() {

				return [
					'title_shortcode_section' => [
						'label'       => esc_html__( 'Title', 'fusion-builder' ),
						'description' => '',
						'id'          => 'title_shortcode_section',
						'type'        => 'accordion',
						'icon'        => 'fusiona-H',
						'fields'      => [
							'title_style_type'    => [
								'label'       => esc_html__( 'Title Separator', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the type of title separator that will display.', 'fusion-builder' ),
								'id'          => 'title_style_type',
								'default'     => 'double solid',
								'type'        => 'select',
								'transport'   => 'postMessage',
								'choices'     => [
									'single solid'     => esc_html__( 'Single Solid', 'fusion-builder' ),
									'single dashed'    => esc_html__( 'Single Dashed', 'fusion-builder' ),
									'single dotted'    => esc_html__( 'Single Dotted', 'fusion-builder' ),
									'double solid'     => esc_html__( 'Double Solid', 'fusion-builder' ),
									'double dashed'    => esc_html__( 'Double Dashed', 'fusion-builder' ),
									'double dotted'    => esc_html__( 'Double Dotted', 'fusion-builder' ),
									'underline solid'  => esc_html__( 'Underline Solid', 'fusion-builder' ),
									'underline dashed' => esc_html__( 'Underline Dashed', 'fusion-builder' ),
									'underline dotted' => esc_html__( 'Underline Dotted', 'fusion-builder' ),
									'none'             => esc_html__( 'None', 'fusion-builder' ),
								],
							],
							'title_border_color'  => [
								'label'       => esc_html__( 'Title Separator Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the title separators.', 'fusion-builder' ),
								'id'          => 'title_border_color',
								'default'     => '#e0dede',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
								'css_vars'    => [
									[
										'name'     => '--title_border_color',
										'callback' => [ 'sanitize_color' ],
									],
								],
							],
							'title_margin'        => [
								'label'       => esc_html__( 'Title Top/Bottom Margins', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the top/bottom margin of the titles. Leave empty to use corresponding heading margins.', 'fusion-builder' ),
								'id'          => 'title_margin',
								'default'     => [
									'top'    => '0px',
									'bottom' => '31px',
								],
								'transport'   => 'postMessage',
								'type'        => 'spacing',
								'choices'     => [
									'top'    => true,
									'bottom' => true,
								],
							],
							'title_margin_mobile' => [
								'label'       => esc_html__( 'Title Mobile Top/Bottom Margins', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the top/bottom margin of the titles on mobiles. Leave empty together with desktop margins to use corresponding heading margins.', 'fusion-builder' ),
								'id'          => 'title_margin_mobile',
								'transport'   => 'postMessage',
								'default'     => [
									'top'    => '0px',
									'bottom' => '20px',
								],
								'type'        => 'spacing',
								'choices'     => [
									'top'    => true,
									'bottom' => true,
								],
							],
						],
					],
				];
			}

			/**
			 * Sets the necessary scripts.
			 *
			 * @access public
			 * @since 1.1
			 * @return void
			 */
			public function add_scripts() {

				Fusion_Dynamic_JS::enqueue_script(
					'fusion-title',
					FusionBuilder::$js_folder_url . '/general/fusion-title.js',
					FusionBuilder::$js_folder_path . '/general/fusion-title.js',
					[ 'jquery' ],
					'1',
					true
				);
			}
		}
	}

	new FusionSC_Title();

}

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fusion_element_title() {

	global $fusion_settings;

	$is_builder = ( function_exists( 'fusion_is_preview_frame' ) && fusion_is_preview_frame() ) || ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() );
	$to_link    = '';

	if ( $is_builder ) {
		$to_link = '<span class="fusion-panel-shortcut" data-fusion-option="headers_typography_important_note_info">' . esc_html__( 'Theme Option Heading Settings', 'fusion-builder' ) . '</span>';
	} else {
		$to_link = '<a href="' . esc_url( $fusion_settings->get_setting_link( 'headers_typography_important_note_info' ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Theme Option Heading Settings', 'fusion-builder' ) . '</a>';
	}

	fusion_builder_map(
		fusion_builder_frontend_data(
			'FusionSC_Title',
			[
				'name'            => esc_attr__( 'Title', 'fusion-builder' ),
				'shortcode'       => 'fusion_title',
				'icon'            => 'fusiona-H',
				'preview'         => FUSION_BUILDER_PLUGIN_DIR . 'inc/templates/previews/fusion-title-preview.php',
				'preview_id'      => 'fusion-builder-block-module-title-preview-template',
				'allow_generator' => true,
				'inline_editor'   => true,
				'help_url'        => 'https://theme-fusion.com/documentation/fusion-builder/elements/title-element/',
				'params'          => [
					[
						'type'        => 'tinymce',
						'heading'     => esc_attr__( 'Title', 'fusion-builder' ),
						'description' => esc_attr__( 'Insert the title text.', 'fusion-builder' ),
						'param_name'  => 'element_content',
						'value'       => esc_attr__( 'Your Content Goes Here', 'fusion-builder' ),
						'placeholder' => true,
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Title Alignment', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose to align the heading left or right.', 'fusion-builder' ),
						'param_name'  => 'content_align',
						'value'       => [
							'left'   => esc_attr__( 'Left', 'fusion-builder' ),
							'center' => esc_attr__( 'Center', 'fusion-builder' ),
							'right'  => esc_attr__( 'Right', 'fusion-builder' ),
						],
						'default'     => 'left',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'HTML Heading Size', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose the size of the HTML heading that should be used, h1-h6.', 'fusion-builder' ),
						'param_name'  => 'size',
						'value'       => [
							'1' => 'H1',
							'2' => 'H2',
							'3' => 'H3',
							'4' => 'H4',
							'5' => 'H5',
							'6' => 'H6',
						],
						'default'     => '1',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Font Size', 'fusion-builder' ),
						/* translators: URL for the link. */
						'description' => sprintf( esc_html__( 'Controls the font size of the title. Enter value including any valid CSS unit, ex: 20px. Leave empty if the global font size for the corresponding heading size (h1-h6) should be used: %s.', 'fusion-builder' ), $to_link ),
						'param_name'  => 'font_size',
						'value'       => '',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Line Height', 'fusion-builder' ),
						/* translators: URL for the link. */
						'description' => sprintf( esc_html__( 'Controls the line height of the title. Enter value including any valid CSS unit, ex: 28px. Leave empty if the global line height for the corresponding heading size (h1-h6) should be used: %s.', 'fusion-builder' ), $to_link ),
						'param_name'  => 'line_height',
						'value'       => '',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Letter Spacing', 'fusion-builder' ),
						/* translators: URL for the link. */
						'description' => sprintf( esc_html__( 'Controls the letter spacing of the title. Enter value including any valid CSS unit, ex: 2px. Leave empty if the global letter spacing for the corresponding heading size (h1-h6) should be used: %s.', 'fusion-builder' ), $to_link ),
						'param_name'  => 'letter_spacing',
						'value'       => '',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'             => 'dimension',
						'heading'          => esc_attr__( 'Margin', 'fusion-builder' ),
						'description'      => esc_attr__( 'Spacing above and below the title. In px, em or %, e.g. 10px.', 'fusion-builder' ),
						'param_name'       => 'dimensions',
						'value'            => [
							'margin_top'    => '',
							'margin_bottom' => '',
						],
						'group'            => esc_attr__( 'Design', 'fusion-builder' ),
						'remove_from_atts' => true,
					],
					[
						'type'             => 'dimension',
						'heading'          => esc_attr__( 'Mobile Margin', 'fusion-builder' ),
						'description'      => esc_attr__( 'Spacing above and below the title on mobiles. In px, em or %, e.g. 10px.', 'fusion-builder' ),
						'param_name'       => 'margin_mobile',
						'value'            => [
							'margin_top_mobile'    => '',
							'margin_bottom_mobile' => '',
						],
						'group'            => esc_attr__( 'Design', 'fusion-builder' ),
						'remove_from_atts' => true,
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Font Color', 'fusion-builder' ),
						/* translators: URL for the link. */
						'description' => sprintf( esc_html__( 'Controls the color of the title, ex: #000. Leave empty if the global color for the corresponding heading size (h1-h6) should be used: %s.', 'fusion-builder' ), $to_link ),
						'param_name'  => 'text_color',
						'value'       => '',
						'default'     => $fusion_settings->get( 'content_box_title_color' ),
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'select',
						'heading'     => esc_attr__( 'Separator', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose the kind of the title separator you want to use.', 'fusion-builder' ),
						'param_name'  => 'style_type',
						'value'       => [
							'default'          => esc_attr__( 'Default', 'fusion-builder' ),
							'single solid'     => esc_attr__( 'Single Solid', 'fusion-builder' ),
							'single dashed'    => esc_attr__( 'Single Dashed', 'fusion-builder' ),
							'single dotted'    => esc_attr__( 'Single Dotted', 'fusion-builder' ),
							'double solid'     => esc_attr__( 'Double Solid', 'fusion-builder' ),
							'double dashed'    => esc_attr__( 'Double Dashed', 'fusion-builder' ),
							'double dotted'    => esc_attr__( 'Double Dotted', 'fusion-builder' ),
							'underline solid'  => esc_attr__( 'Underline Solid', 'fusion-builder' ),
							'underline dashed' => esc_attr__( 'Underline Dashed', 'fusion-builder' ),
							'underline dotted' => esc_attr__( 'Underline Dotted', 'fusion-builder' ),
							'none'             => esc_attr__( 'None', 'fusion-builder' ),
						],
						'default'     => 'default',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Separator Color', 'fusion-builder' ),
						'param_name'  => 'sep_color',
						'value'       => '',
						'description' => esc_attr__( 'Controls the separator color. ', 'fusion-builder' ),
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'style_type',
								'value'    => 'none',
								'operator' => '!=',
							],
						],
						'default'     => $fusion_settings->get( 'title_border_color' ),
					],
					[
						'type'        => 'checkbox_button_set',
						'heading'     => esc_attr__( 'Element Visibility', 'fusion-builder' ),
						'param_name'  => 'hide_on_mobile',
						'value'       => fusion_builder_visibility_options( 'full' ),
						'default'     => fusion_builder_default_visibility( 'array' ),
						'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'CSS Class', 'fusion-builder' ),
						'param_name'  => 'class',
						'value'       => '',
						'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'CSS ID', 'fusion-builder' ),
						'param_name'  => 'id',
						'value'       => '',
						'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'fusion-builder' ),
					],
				],
			]
		)
	);
}
add_action( 'fusion_builder_before_init', 'fusion_element_title' );
