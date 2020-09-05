<?php
/**
 * A class which is used to add various options to all widgets.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * A class which is used to add various options to all widgets.
 */
class Avada_Widget_Style {

	/**
	 * Widget options we're going to add.
	 *
	 * @since 5.3.0
	 * @access private
	 * @var array
	 */
	private $widget_options;

	/**
	 * Construct the object.
	 *
	 * @since 5.3.0
	 * @access public
	 */
	public function __construct() {

		$this->init_options();

		// If ajax request coming from front-end we dont want these added.
		if ( ! isset( $_POST ) || ! isset( $_POST['action'] ) || 'fusion_get_widget_data' !== $_POST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			add_filter( 'in_widget_form', [ $this, 'add_widget_styling_options' ], 10, 3 );
			add_filter( 'widget_update_callback', [ $this, 'save_widget_styling_options' ], 10, 4 );
		}
		add_filter( 'dynamic_sidebar_params', [ $this, 'add_widget_styles' ] );
	}

	/**
	 * Init all options that we're going to add.
	 *
	 * @since 5.3.0
	 * @access private
	 * @return void
	 */
	private function init_options() {

		$this->widget_options = [
			'fusion_padding_color'  => [
				'key'          => 'fusion_padding_color',
				'title'        => esc_html__( 'Padding', 'Avada' ),
				'description'  => esc_html__( 'Controls the padding for this widget container. Enter value including any valid CSS unit, ex: 10px.', 'Avada' ),
				'css_property' => 'padding',
				'type'         => 'text',
			],
			'fusion_bg_color'       => [
				'key'          => 'fusion_bg_color',
				'title'        => esc_html__( 'Background Color', 'Avada' ),
				'description'  => esc_html__( 'Controls the background color for this widget container.', 'Avada' ),
				'css_property' => 'background-color',
				'type'         => 'colorpickeralpha',
			],
			'fusion_bg_radius_size' => [
				'key'          => 'fusion_bg_radius_size',
				'title'        => esc_html__( 'Background Radius', 'Avada' ),
				'description'  => esc_html__( 'Controls the background radius for this widget container.', 'Avada' ),
				'css_property' => 'border-radius',
				'type'         => 'text',
			],
			'fusion_border_size'    => [
				'key'          => 'fusion_border_size',
				'title'        => esc_html__( 'Border Size', 'Avada' ),
				'description'  => esc_html__( 'Controls the border size for this widget container.', 'Avada' ),
				'css_property' => 'border-width',
				'type'         => 'range',
				'min'          => 0,
				'max'          => 50,
				'step'         => 1,
				'default'      => 0,
			],
			'fusion_border_style'   => [
				'key'          => 'fusion_border_style',
				'title'        => esc_html__( 'Border Style', 'Avada' ),
				'description'  => esc_html__( 'Controls the border style for this widget container.', 'Avada' ),
				'css_property' => 'border-style',
				'type'         => 'select',
				'choices'      => [
					''       => esc_html__( 'None', 'Avada' ),
					'solid'  => esc_html__( 'Solid', 'Avada' ),
					'dotted' => esc_html__( 'Dotted', 'Avada' ),
					'dashed' => esc_html__( 'Dashed', 'Avada' ),
				],
			],
			'fusion_border_color'   => [
				'key'          => 'fusion_border_color',
				'title'        => esc_html__( 'Border Color', 'Avada' ),
				'description'  => esc_html__( 'Controls the border color for this widget container.', 'Avada' ),
				'css_property' => 'border-color',
				'type'         => 'colorpickeralpha',
			],
			'fusion_align'          => [
				'key'          => 'fusion_align',
				'title'        => esc_html__( 'Content Align', 'Avada' ),
				'description'  => esc_html__( 'Controls content alignment for this widget container. Inherit means it will inherit alignment from its parent element.', 'Avada' ),
				'css_property' => 'text-align',
				'type'         => 'select',
				'choices'      => [
					''       => esc_html__( 'Inherit', 'Avada' ),
					'left'   => esc_html__( 'Left', 'Avada' ),
					'right'  => esc_html__( 'Right', 'Avada' ),
					'center' => esc_html__( 'Center', 'Avada' ),
				],
			],
			'fusion_align_mobile'   => [
				'key'          => 'fusion_align_mobile',
				'title'        => esc_html__( 'Mobile Content Align', 'Avada' ),
				'description'  => esc_html__( 'Controls mobile content alignment for this widget container. Inherit means it will inherit alignment from its parent element.', 'Avada' ),
				'css_property' => 'text-align',
				'type'         => 'select',
				'choices'      => [
					''       => esc_html__( 'Inherit', 'Avada' ),
					'left'   => esc_html__( 'Left', 'Avada' ),
					'right'  => esc_html__( 'Right', 'Avada' ),
					'center' => esc_html__( 'Center', 'Avada' ),
				],
			],
		];
	}

	/**
	 * Add widget options to form
	 *
	 * @since 5.3.0
	 * @access public
	 * @param object      $widget   WP_Widget object, passed by reference.
	 * @param null|string $return   Return null if new fields are added.
	 * @param array       $instance An array of the widget's settings.
	 */
	public function add_widget_styling_options( $widget, $return, $instance ) {
		$this->start_widget_options();
		?>
		<?php foreach ( $this->widget_options as $option ) : ?>
			<?php
			$field_css_classes   = 'option-field fusion-builder-option-container';
			$wrapper_css_classes = 'fusion-widget-' . $option['key'];

			if ( 'colorpickeralpha' === $option['type'] ) {
				$field_css_classes .= ' pyre_field avada-color colorpickeralpha';
			}

			$value                 = isset( $instance[ $option['key'] ] ) ? $instance[ $option['key'] ] : '';
			$option['description'] = isset( $option['description'] ) ? $option['description'] : '';

			if ( 'range' === $option['type'] ) {
				$wrapper_css_classes .= ' avada-range';
				$value                = '' !== $value ? (int) $value : $option['default'];
			}
			?>
			<li class="fusion-builder-option <?php echo esc_attr( $wrapper_css_classes ); ?>">
				<div class="option-details">
					<h3><?php echo esc_html( $option['title'] ); ?></h3>
					<p class="description"><?php echo esc_html( $option['description'] ); ?>
					<?php
					if ( 'range' === $option['type'] && isset( $option['default'] ) && '' !== $option['default'] ) {
						echo '<span class="pyre-default-reset" style="display:none;"><a href="#" id="default-' . esc_attr( $widget->get_field_id( $option['key'] ) ) . '" class="fusion-range-default fusion-hide-from-atts" type="radio" name="' . esc_attr( $widget->get_field_id( $option['key'] ) ) . '" value="" data-default="' . esc_attr( $option['default'] ) . '">' . esc_attr( 'Reset to default.', 'Avada' ) . '</a><span>' . esc_attr( 'Using default value.', 'Avada' ) . '</span></span>';
					}
					?>
					</p>
				</div>

				<div class="<?php echo esc_attr( $field_css_classes ); ?>">
				<?php if ( 'select' === $option['type'] ) : ?>
					<select id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
					>
					<?php foreach ( $option['choices'] as $val => $title ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php selected( esc_attr( $val ), esc_attr( $value ) ); ?>><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
					</select>
				<?php elseif ( 'colorpickeralpha' === $option['type'] ) : ?>
					<input type="text" id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							class="fusion-builder-color-picker-hex color-picker"
							data-alpha="true"
							name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
						/>
					<?php elseif ( 'range' === $option['type'] ) : ?>
						<input
							type="text"
							name="range-<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
							id="range-<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
							class="fusion-slider-input <?php echo ( isset( $option['default'] ) && '' !== $option['default'] ) ? 'fusion-hide-from-atts' : ''; ?>" />
						<div
							class="fusion-slider-container"
							data-id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
							data-min="<?php echo esc_attr( $option['min'] ); ?>"
							data-max="<?php echo esc_attr( $option['max'] ); ?>"
							data-step="<?php echo esc_attr( $option['step'] ); ?>">
						</div>
						<?php if ( isset( $option['default'] ) && '' !== $option['default'] ) : ?>
						<input type="hidden"
							id="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
							name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
							class="fusion-hidden-value" />
						<?php endif; ?>
				<?php else : ?>
					<input type="text" id="<?php echo esc_attr( $widget->get_field_id( $option['key'] ) ); ?>"
						name="<?php echo esc_attr( $widget->get_field_name( $option['key'] ) ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
					/>
				<?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		<?php
		$this->end_widget_options();
	}

	/**
	 * Open widget options container.
	 */
	private function start_widget_options() {
		?>
		<div class="fusion-menu-options-container">
			<a class="button button-primary button-large fusion-menu-option-trigger" href="#">
				<?php esc_html_e( 'Avada Widget Options', 'Avada' ); ?>
			</a>
			<div class="fusion_builder_modal_overlay" style="display:none"></div>
			<div class="fusion-options-holder fusion-builder-modal-settings-container" style="display:none">
				<div class="fusion-builder-modal-container fusion_builder_module_settings">
					<div class="fusion-builder-modal-top-container fusion-widget-settings-top">
						<h2>
							<?php esc_attr_e( 'Avada Widget Options', 'Avada' ); ?>
							<div class="fusion-modal-description">
								<?php esc_html_e( 'These options apply to the widget container, not the actual widget.', 'Avada' ); ?>
							</div>
						</h2>
						<div class="fusion-builder-modal-close fusiona-plus2"></div>
					</div>
					<div class="fusion-builder-modal-bottom-container">
						<a href="#" class="fusion-builder-modal-save" ><span><?php esc_attr_e( 'Save', 'Avada' ); ?></span></a>
						<a href="#" class="fusion-builder-modal-close" ><span><?php esc_attr_e( 'Cancel', 'Avada' ); ?></span></a>
					</div>
					<div class="fusion-builder-main-settings fusion-builder-main-settings-full">
						<ul class="fusion-builder-module-settings">
							<?php
	}

	/**
	 * Close widget options container.
	 */
	public function end_widget_options() {
		?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save widget options.
	 *
	 * @since 5.3.0
	 * @access public
	 * @param array     $instance     An array of current widget's settings.
	 * @param array     $new_instance An array of new widget's settings.
	 * @param array     $old_instance An array of old widget's settings.
	 * @param WP_Widget $widget       The current widget instance.
	 * @return mixed
	 */
	public function save_widget_styling_options( $instance, $new_instance, $old_instance, $widget ) {

		foreach ( $this->widget_options as $option ) {
			$instance[ $option['key'] ] = ! empty( $new_instance[ $option['key'] ] ) ? sanitize_text_field( $new_instance[ $option['key'] ] ) : '';
		}

		return $instance;
	}

	/**
	 * Prints widget styles based on options.
	 * Desktop styles are printed using style element attribute.
	 * Mobile styles are printed inline.
	 *
	 * @since 5.3.0
	 * @access public
	 * @param array $params Widget params.
	 * @return mixed
	 */
	public function add_widget_styles( $params ) {

		global $wp_registered_widgets;

		if ( ! isset( $params[0] ) ) {
			return $params;
		}

		$sidebar_id = $params[0]['id']; // Get the id for the current sidebar we're processing.
		$widget_id  = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[ $widget_id ];
		$widget_num = $widget_obj['params'][0]['number'];
		$widget_opt = $this->get_widget_opt( $widget_obj );

		$style        = '';
		$style_mobile = '';

		// If calendar and no alignment set, set to default.
		if ( isset( $widget_opt[ $widget_num ] ) && ! isset( $widget_opt[ $widget_num ]['fusion_align'] ) && false !== strpos( $widget_id, 'calendar' ) ) {
			$widget_opt[ $widget_num ]['fusion_align'] = '';
		}

		foreach ( $this->widget_options as $option ) {
			if ( isset( $widget_opt[ $widget_num ][ $option['key'] ] ) ) {

				if ( 'fusion_align' === $option['key'] && false !== strpos( $widget_id, 'calendar' ) ) {
					$alignment                  = ( '' === $widget_opt[ $widget_num ][ $option['key'] ] ) ? 'default' : $widget_opt[ $widget_num ][ $option['key'] ];
					$params[0]['before_widget'] = str_replace( 'class="', 'class="fusion-widget-align-' . $alignment . ' ', $params[0]['before_widget'] );
				}

				if ( 'fusion_align' === $option['key'] || 'fusion_align_mobile' === $option['key'] ) {

					if ( 'fusion_align_mobile' === $option['key'] && '' === $widget_opt[ $widget_num ][ $option['key'] ] && '' !== $widget_opt[ $widget_num ]['fusion_align'] ) {

						if ( false !== strpos( $sidebar_id, 'avada-footer-widget-' ) && Avada()->settings->get( 'footer_widgets_center_content' ) ) {
							$widget_opt[ $widget_num ][ $option['key'] ] = 'center';
						} else {
							$widget_opt[ $widget_num ][ $option['key'] ] = 'initial';
						}
					}

					if ( '' !== $widget_opt[ $widget_num ][ $option['key'] ] ) {
						$alignment = ( '' === $widget_opt[ $widget_num ][ $option['key'] ] ) ? 'default' : $widget_opt[ $widget_num ][ $option['key'] ];
						$css_class = ( 'fusion_align' === $option['key'] ? 'fusion-widget-align-' : 'fusion-widget-mobile-align-' ) . $alignment;

						$params[0]['before_widget'] = str_replace( 'class="', 'class="' . esc_attr( $css_class ) . ' ', $params[0]['before_widget'] );
					}
				}

				if ( '' !== $widget_opt[ $widget_num ][ $option['key'] ] ) {
					if ( false === strpos( $option['key'], 'mobile' ) ) {
						if ( 'border-width' === $option['css_property'] ) {
							$widget_opt[ $widget_num ][ $option['key'] ] = (int) $widget_opt[ $widget_num ][ $option['key'] ] . 'px';
						}
						$style .= $option['css_property'] . ': ' . $widget_opt[ $widget_num ][ $option['key'] ] . ';';

						if ( 'border-radius' === $option['css_property'] ) {
							$style .= 'overflow:hidden;';
						}
					} else {
						$style_mobile .= '#' . $widget_id . '{' . $option['css_property'] . ':' . $widget_opt[ $widget_num ][ $option['key'] ] . ' !important;}';
					}
				}
			}
		}

		// Set border color to transparent and border size to 0px it those field were left empty, but border style isn't.
		if ( isset( $widget_opt[ $widget_num ]['fusion_border_style'] ) && '' !== $widget_opt[ $widget_num ]['fusion_border_style'] ) {
			if ( ! isset( $widget_opt[ $widget_num ]['fusion_border_color'] ) || '' === $widget_opt[ $widget_num ]['fusion_border_color'] ) {
				$style .= 'border-color:transparent;';
			}

			if ( ! isset( $widget_opt[ $widget_num ]['fusion_border_size'] ) || '' === $widget_opt[ $widget_num ]['fusion_border_size'] ) {
				$style .= 'border-width:0px;';
			}
		}

		if ( ! empty( $style ) ) {
			$params[0]['before_widget'] = str_replace( '>', ' style="' . esc_attr( $style ) . '">', $params[0]['before_widget'] );
		}

		if ( ! empty( $style_mobile ) ) {
			$params[0]['before_widget'] = '<style type="text/css" data-id="' . $widget_id . '">@media (max-width: ' . Avada()->settings->get( 'content_break_point' ) . 'px){' . $style_mobile . '}</style>' . $params[0]['before_widget'];
		}

		return $params;
	}

	/**
	 * Get widget options.
	 *
	 * @since 5.3.0
	 * @access private
	 * @param object $widget WP_Widget object.
	 * @return mixed|void
	 */
	private function get_widget_opt( $widget ) {
		$widget_opt = get_option( $widget['callback'][0]->option_name );

		return $widget_opt;
	}

}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
