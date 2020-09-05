<?php
/**
 * Add an element to fusion-builder.
 *
 * @package fusion-builder
 * @since 1.0
 */

if ( fusion_is_element_enabled( 'fusion_fontawesome' ) ) {

	if ( ! class_exists( 'FusionSC_FontAwesome' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @since 1.0
		 */
		class FusionSC_FontAwesome extends Fusion_Element {

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
				add_filter( 'fusion_attr_fontawesome-shortcode', [ $this, 'attr' ] );
				add_shortcode( 'fusion_fontawesome', [ $this, 'render' ] );

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
					'hide_on_mobile'      => fusion_builder_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
					'alignment'           => '',
					'circle'              => 'yes',
					'circlecolor'         => $fusion_settings->get( 'icon_circle_color' ),
					'circlebordercolor'   => $fusion_settings->get( 'icon_border_color' ),
					'flip'                => '',
					'icon'                => '',
					'iconcolor'           => $fusion_settings->get( 'icon_color' ),
					'margin_bottom'       => '',
					'margin_left'         => '',
					'margin_right'        => '',
					'margin_top'          => '',
					'rotate'              => '',
					'size'                => 'medium',
					'spin'                => 'no',
					'animation_type'      => '',
					'animation_direction' => 'down',
					'animation_speed'     => '0.1',
					'animation_offset'    => $fusion_settings->get( 'animation_offset' ),
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
					'icon_circle_color' => 'circlecolor',
					'icon_border_color' => 'circlebordercolor',
					'icon_color'        => 'iconcolor',
					'animation_offset'  => 'animation_offset',
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

				$defaults = FusionBuilder::set_shortcode_defaults( self::get_element_defaults(), $args, 'fusion_fontawesome' );

				extract( $defaults );

				// Dertmine line-height and margin from font size.
				$defaults['font_size']            = FusionBuilder::validate_shortcode_attr_value( self::convert_deprecated_sizes( $defaults['size'] ), '' );
				$defaults['circle_yes_font_size'] = $defaults['font_size'] * 0.88;
				$defaults['line_height']          = $defaults['font_size'] * 1.76;

				// Check if an old icon shortcode is used, where no margin option is present, or if all margins were left empty.
				$defaults['legacy_icon'] = false;
				if ( ! isset( $args['margin_left'] ) || ( '' === $margin_top && '' === $margin_right && '' === $margin_bottom && '' === $margin_left ) ) {
					$defaults['legacy_icon'] = true;
				}

				$this->args = $defaults;

				$html = '<i ' . FusionBuilder::attributes( 'fontawesome-shortcode' ) . '>' . do_shortcode( $content ) . '</i>';

				if ( $alignment ) {
					$html = '<div class="fusion-fa-align-' . $alignment . '">' . $html . '</div>';
				}

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
						'class' => 'fontawesome-icon ' . FusionBuilder::font_awesome_name_handler( $this->args['icon'] ) . ' circle-' . $this->args['circle'],
					]
				);

				$attr['style'] = '';

				if ( 'yes' === $this->args['circle'] ) {

					if ( $this->args['circlebordercolor'] ) {
						$attr['style'] .= 'border-color:' . $this->args['circlebordercolor'] . ';';
					}

					if ( $this->args['circlecolor'] ) {
						$attr['style'] .= 'background-color:' . $this->args['circlecolor'] . ';';
					}

					$attr['style'] .= 'font-size:' . $this->args['circle_yes_font_size'] . 'px;';

					$attr['style'] .= 'line-height:' . $this->args['line_height'] . 'px;height:' . $this->args['line_height'] . 'px;width:' . $this->args['line_height'] . 'px;';

				} else {
					$attr['style'] .= 'font-size:' . $this->args['font_size'] . 'px;';
				}

				// Legacy icon, where no margin option was present: use the old default ,argin calcs.
				if ( $this->args['legacy_icon'] ) {
					$icon_margin = $this->args['font_size'] * 0.5;

					if ( 'left' === $this->args['alignment'] ) {
						$icon_margin_position = 'right';
					} elseif ( 'right' === $this->args['alignment'] ) {
						$icon_margin_position = 'left';
					} else {
						$icon_margin_position = ( is_rtl() ) ? 'left' : 'right';
					}

					if ( 'center' !== $this->args['alignment'] ) {
						$attr['style'] .= 'margin-' . $icon_margin_position . ':' . $icon_margin . 'px;';
					}
				} else {

					// New icon with dedicated margin option.
					if ( $this->args['margin_top'] ) {
						$attr['style'] .= 'margin-top:' . $this->args['margin_top'] . ';';
					}

					if ( $this->args['margin_right'] ) {
						$attr['style'] .= 'margin-right:' . $this->args['margin_right'] . ';';
					}

					if ( $this->args['margin_bottom'] ) {
						$attr['style'] .= 'margin-bottom:' . $this->args['margin_bottom'] . ';';
					}

					if ( $this->args['margin_left'] ) {
						$attr['style'] .= 'margin-left:' . $this->args['margin_left'] . ';';
					}
				}

				if ( $this->args['iconcolor'] ) {
					$attr['style'] .= 'color:' . $this->args['iconcolor'] . ';';
				}

				if ( $this->args['rotate'] ) {
					$attr['class'] .= ' fa-rotate-' . $this->args['rotate'];
				}

				if ( 'yes' === $this->args['spin'] ) {
					$attr['class'] .= ' fa-spin';
				}

				if ( $this->args['flip'] ) {
					$attr['class'] .= ' fa-flip-' . $this->args['flip'];
				}

				if ( $this->args['animation_type'] ) {
					$animations = FusionBuilder::animations(
						[
							'type'      => $this->args['animation_type'],
							'direction' => $this->args['animation_direction'],
							'speed'     => $this->args['animation_speed'],
							'offset'    => $this->args['animation_offset'],
						]
					);

					$attr = array_merge( $attr, $animations );

					$attr['class'] .= ' ' . $attr['animation_class'];
					unset( $attr['animation_class'] );
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
			 * Converts deprecated image sizes to their new names.
			 *
			 * @access public
			 * @since 1.0
			 * @param  string $size The name of the old image-size.
			 * @return string       The name of the new image-size.
			 */
			public function convert_deprecated_sizes( $size ) {
				switch ( $size ) {
					case 'small':
						$size = '10px';
						break;
					case 'medium':
						$size = '18px';
						break;
					case 'large':
						$size = '40px';
						break;
					default:
						break;
				}

				return $size;
			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections Icon settings.
			 */
			public function add_options() {

				return [
					'fontawesome_shortcode_section' => [
						'label'       => esc_html__( 'Font Awesome Icon', 'fusion-builder' ),
						'description' => '',
						'id'          => 'fontawesome_shortcode_section',
						'type'        => 'accordion',
						'icon'        => 'fusiona-flag',
						'fields'      => [
							'icon_circle_color' => [
								'label'       => esc_html__( 'Icon Circle Background Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the circle background.', 'fusion-builder' ),
								'id'          => 'icon_circle_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
								'css_vars'    => [
									[
										'name'     => '--icon_circle_color',
										'callback' => [ 'sanitize_color' ],
									],
								],
							],
							'icon_border_color' => [
								'label'       => esc_html__( 'Icon Circle Border Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the border color of the circle background.', 'fusion-builder' ),
								'id'          => 'icon_border_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
								'css_vars'    => [
									[
										'name'     => '--icon_border_color',
										'callback' => [ 'sanitize_color' ],
									],
								],
							],
							'icon_color'        => [
								'label'       => esc_html__( 'Icon Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the icon.', 'fusion-builder' ),
								'id'          => 'icon_color',
								'default'     => '#ffffff',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
								'css_vars'    => [
									[
										'name'     => '--icon_color',
										'callback' => [ 'sanitize_color' ],
									],
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

				Fusion_Dynamic_JS::enqueue_script( 'fusion-animations' );
			}
		}
	}

	new FusionSC_FontAwesome();

}

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fusion_element_font_awesome() {

	global $fusion_settings;

	fusion_builder_map(
		fusion_builder_frontend_data(
			'FusionSC_FontAwesome',
			[
				'name'       => esc_attr__( 'Font Awesome Icon', 'fusion-builder' ),
				'shortcode'  => 'fusion_fontawesome',
				'icon'       => 'fusiona-flag',
				'preview'    => FUSION_BUILDER_PLUGIN_DIR . 'inc/templates/previews/fusion-font-awesome-preview.php',
				'preview_id' => 'fusion-builder-block-module-font-awesome-preview-template',
				'help_url'   => 'https://theme-fusion.com/documentation/fusion-builder/elements/font-awesome-icon-element/',
				'params'     => [
					[
						'type'        => 'iconpicker',
						'heading'     => esc_attr__( 'Select Icon', 'fusion-builder' ),
						'param_name'  => 'icon',
						'value'       => 'fa-font-awesome-flag fab',
						'description' => esc_attr__( 'Click an icon to select, click again to deselect.', 'fusion-builder' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Size of Icon', 'fusion-builder' ),
						'description' => esc_attr__( 'Set the size of the icon. In pixels (px), ex: 13px.', 'fusion-builder' ),
						'param_name'  => 'size',
						'value'       => '',
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Flip Icon', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose to flip the icon.', 'fusion-builder' ),
						'param_name'  => 'flip',
						'value'       => [
							''           => esc_attr__( 'None', 'fusion-builder' ),
							'horizontal' => esc_attr__( 'Horizontal', 'fusion-builder' ),
							'vertical'   => esc_attr__( 'Vertical', 'fusion-builder' ),
						],
						'default'     => '',
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Rotate Icon', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose to rotate the icon.', 'fusion-builder' ),
						'param_name'  => 'rotate',
						'value'       => [
							''    => esc_attr__( 'None', 'fusion-builder' ),
							'90'  => '90',
							'180' => '180',
							'270' => '270',
						],
						'default'     => '',
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Spinning Icon', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose to let the icon spin.', 'fusion-builder' ),
						'param_name'  => 'spin',
						'value'       => [
							'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
							'no'  => esc_attr__( 'No', 'fusion-builder' ),
						],
						'default'     => 'no',
					],
					[
						'type'             => 'dimension',
						'remove_from_atts' => true,
						'heading'          => esc_attr__( 'Margin', 'fusion-builder' ),
						'description'      => __( 'Spacing around the font awesome icon. In px, em or %, e.g. 10px. <strong>Note:</strong> Leave empty for automatic margin calculation, based on alignment and icon size.', 'fusion-builder' ),
						'param_name'       => 'margin',
						'group'            => esc_attr__( 'Design', 'fusion-builder' ),
						'value'            => [
							'margin_top'    => '',
							'margin_right'  => '',
							'margin_bottom' => '',
							'margin_left'   => '',
						],
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Icon in Circle', 'fusion-builder' ),
						'description' => esc_attr__( 'Choose to display the icon in a circle.', 'fusion-builder' ),
						'param_name'  => 'circle',
						'value'       => [
							'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
							'no'  => esc_attr__( 'No', 'fusion-builder' ),
						],
						'default'     => 'yes',
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Icon Color', 'fusion-builder' ),
						'description' => esc_attr__( 'Controls the color of the icon. ', 'fusion-builder' ),
						'param_name'  => 'iconcolor',
						'value'       => '',
						'default'     => $fusion_settings->get( 'icon_color' ),
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Icon Circle Background Color', 'fusion-builder' ),
						'description' => esc_attr__( 'Controls the color of the circle. ', 'fusion-builder' ),
						'param_name'  => 'circlecolor',
						'value'       => '',
						'default'     => $fusion_settings->get( 'icon_circle_color' ),
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'circle',
								'value'    => 'yes',
								'operator' => '==',
							],
						],
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Icon Circle Border Color', 'fusion-builder' ),
						'description' => esc_attr__( 'Controls the color of the circle border. ', 'fusion-builder' ),
						'param_name'  => 'circlebordercolor',
						'value'       => '',
						'default'     => $fusion_settings->get( 'icon_border_color' ),
						'group'       => esc_attr__( 'Design', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'circle',
								'value'    => 'yes',
								'operator' => '==',
							],
						],
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Alignment', 'fusion-builder' ),
						'description' => esc_attr__( "Select the icon's alignment.", 'fusion-builder' ),
						'param_name'  => 'alignment',
						'value'       => [
							''       => esc_attr__( 'Text Flow', 'fusion-builder' ),
							'center' => esc_attr__( 'Center', 'fusion-builder' ),
							'left'   => esc_attr__( 'Left', 'fusion-builder' ),
							'right'  => esc_attr__( 'Right', 'fusion-builder' ),
						],
						'default'     => '',
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
					[
						'type'        => 'select',
						'heading'     => esc_attr__( 'Animation Type', 'fusion-builder' ),
						'description' => esc_attr__( 'Select the type of animation to use on the element.', 'fusion-builder' ),
						'param_name'  => 'animation_type',
						'value'       => fusion_builder_available_animations(),
						'default'     => '',
						'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
						'preview'     => [
							'selector' => '.fontawesome-icon',
							'type'     => 'animation',
						],
					],
					[
						'type'        => 'radio_button_set',
						'heading'     => esc_attr__( 'Direction of Animation', 'fusion-builder' ),
						'description' => esc_attr__( 'Select the incoming direction for the animation.', 'fusion-builder' ),
						'param_name'  => 'animation_direction',
						'value'       => [
							'down'   => esc_attr__( 'Top', 'fusion-builder' ),
							'right'  => esc_attr__( 'Right', 'fusion-builder' ),
							'up'     => esc_attr__( 'Bottom', 'fusion-builder' ),
							'left'   => esc_attr__( 'Left', 'fusion-builder' ),
							'static' => esc_attr__( 'Static', 'fusion-builder' ),
						],
						'default'     => 'down',
						'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							],
						],
						'preview'     => [
							'selector' => '.fa',
							'type'     => 'animation',
						],
					],
					[
						'type'        => 'range',
						'heading'     => esc_attr__( 'Speed of Animation', 'fusion-builder' ),
						'description' => esc_attr__( 'Type in speed of animation in seconds (0.1 - 1).', 'fusion-builder' ),
						'param_name'  => 'animation_speed',
						'min'         => '0.1',
						'max'         => '1',
						'step'        => '0.1',
						'value'       => '0.3',
						'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							],
						],
						'preview'     => [
							'selector' => '.fa',
							'type'     => 'animation',
						],
					],
					[
						'type'        => 'select',
						'heading'     => esc_attr__( 'Offset of Animation', 'fusion-builder' ),
						'description' => esc_attr__( 'Controls when the animation should start.', 'fusion-builder' ),
						'param_name'  => 'animation_offset',
						'value'       => [
							''                => esc_attr__( 'Default', 'fusion-builder' ),
							'top-into-view'   => esc_attr__( 'Top of element hits bottom of viewport', 'fusion-builder' ),
							'top-mid-of-view' => esc_attr__( 'Top of element hits middle of viewport', 'fusion-builder' ),
							'bottom-in-view'  => esc_attr__( 'Bottom of element enters viewport', 'fusion-builder' ),
						],
						'default'     => '',
						'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
						'dependency'  => [
							[
								'element'  => 'animation_type',
								'value'    => '',
								'operator' => '!=',
							],
						],
					],
				],
			]
		)
	);
}
add_action( 'fusion_builder_before_init', 'fusion_element_font_awesome' );
