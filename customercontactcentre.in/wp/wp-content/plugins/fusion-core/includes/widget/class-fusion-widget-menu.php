<?php
/**
 * Widget Class.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Fusion Core
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Widget class.
 */
class Fusion_Widget_Menu extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		$widget_ops  = [
			'classname'   => 'menu',
			'description' => __( 'Adds a horizontal navigation', 'fusion-core' ),
		];
		$control_ops = [
			'id_base' => 'menu-widget',
		];
		parent::__construct( 'menu-widget', __( 'Avada: Horizontal Menu' ), $widget_ops, $control_ops );

	}

	/**
	 * Echoes the widget content.
	 *
	 * @access public
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput

		// Get menu.
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		echo '<style type="text/css">';

		$text_align = strtolower( $instance['alignment'] );
		echo '#' . esc_attr( $this->id ) . '{text-align:' . esc_attr( $text_align ) . ';}';
		echo '#' . esc_attr( $this->id ) . '>.fusion-widget-menu li{display:inline-block;}';

		$color     = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_color'] ) : $instance['menu_link_color'];
		$font_size = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::size( $instance['font_size'] ) : $instance['font_size'];
		echo '#' . esc_attr( $this->id ) . ' ul li a{display:inline-block;padding:0;border:0;color:' . esc_attr( $color ) . ';font-size:' . esc_attr( $font_size ) . ';}';

		$color         = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_color'] ) : $instance['menu_link_color'];
		$padding_right = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::size( $instance['menu_padding'] ) : $instance['menu_padding'];
		$padding_left  = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::size( $instance['menu_padding'] ) : $instance['menu_padding'];
		$font_size     = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::size( $instance['font_size'] ) : $instance['font_size'];
		echo '#' . esc_attr( $this->id ) . ' ul li a:after{content:"' . esc_attr( $instance['sep_text'] ) . '";color:' . esc_attr( $color ) . ';padding-right:' . esc_attr( $padding_right ) . ';padding-left:' . esc_attr( $padding_left ) . ';font-size:' . esc_attr( $font_size ) . ';}';

		$color = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_hover_color'] ) : $instance['menu_link_hover_color'];
		echo '#' . esc_attr( $this->id ) . ' ul li a:hover,#' . esc_attr( $this->id ) . ' ul .menu-item.current-menu-item a{color:' . esc_attr( $color ) . ';}';

		echo '#' . esc_attr( $this->id ) . ' ul li:last-child a:after{display:none;}';

		$background_color = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_hover_color'] ) : $instance['menu_link_hover_color'];
		$color            = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_color'] ) : $instance['menu_link_color'];
		echo '#' . esc_attr( $this->id ) . ' ul li .fusion-widget-cart-number{margin:0 7px;background-color:' . esc_attr( $background_color ) . ';color:' . esc_attr( $color ) . ';}';

		$color = FusionCore_Plugin::$fusion_library_exists ? Fusion_Sanitize::color( $instance['menu_link_hover_color'] ) : $instance['menu_link_hover_color'];
		echo '#' . esc_attr( $this->id ) . ' ul li.fusion-active-cart-icon .fusion-widget-cart-icon:after{color:' . esc_attr( $color ) . ';}';

		echo '</style>';

		$nav_menu_args = [
			'fallback_cb'     => '',
			'menu'            => $nav_menu,
			'depth'           => -1,
			'container'       => false,
			'container_class' => 'fusion-widget-menu',
			'item_spacing'    => 'discard',
		];

		$aria_label = __( 'Secondary navigation', 'fusion-core' );
		if ( isset( $instance['title'] ) ) {
			/* translators: The widget name. */
			$aria_label = sprintf( __( 'Secondary Navigation: %s', 'fusion-core' ), $instance['title'] );
		}

		echo '<nav class="fusion-widget-menu" aria-label="' . esc_attr( $aria_label ) . '">';
		wp_nav_menu( $nav_menu_args );
		echo '</nav>';

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput

	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * This function should check that `$new_instance` is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @access public
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['nav_menu']              = isset( $new_instance['nav_menu'] ) ? $new_instance['nav_menu'] : '';
		$instance['alignment']             = isset( $new_instance['alignment'] ) ? $new_instance['alignment'] : '';
		$instance['menu_padding']          = isset( $new_instance['menu_padding'] ) ? $new_instance['menu_padding'] : '';
		$instance['menu_link_color']       = isset( $new_instance['menu_link_color'] ) ? $new_instance['menu_link_color'] : '';
		$instance['menu_link_hover_color'] = isset( $new_instance['menu_link_hover_color'] ) ? $new_instance['menu_link_hover_color'] : '';
		$instance['sep_text']              = isset( $new_instance['sep_text'] ) ? $new_instance['sep_text'] : '';
		$instance['font_size']             = isset( $new_instance['font_size'] ) ? $new_instance['font_size'] : '';

		return $instance;

	}

	/**
	 * Outputs the settings update form.
	 *
	 * @access public
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$defaults = [
			'nav_menu'              => '',
			'alignment'             => 'Left',
			'menu_padding'          => '25px',
			'menu_link_color'       => '#ccc',
			'menu_link_hover_color' => '#fff',
			'sep_text'              => '|',
			'font_size'             => '14px',
		];
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get menus.
		$menus    = wp_get_nav_menus();
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_attr_e( 'Select Menu:', 'fusion-core' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>" class="widefat" style="width:100%;">
				<option value="0">&mdash; <?php esc_attr_e( 'Select', 'fusion-core' ); ?> &mdash;</option>
				<?php foreach ( $menus as $menu ) : ?>
					<option value="<?php echo esc_attr( $menu->slug ); ?>" <?php selected( $nav_menu, $menu->slug ); ?>>
						<?php echo esc_html( $menu->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>"><?php esc_attr_e( 'Alignment:', 'fusion-core' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alignment' ) ); ?>" class="widefat" style="width:100%;">
				<option value="Left" <?php echo ( 'Left' === $instance['alignment'] ) ? 'selected="selected"' : ''; ?>><?php esc_attr_e( 'Left', 'fusion-core' ); ?></option>
				<option value="Center" <?php echo ( 'Center' === $instance['alignment'] ) ? 'selected="selected"' : ''; ?>><?php esc_attr_e( 'Center', 'fusion-core' ); ?></option>
				<option value="Right" <?php echo ( 'Right' === $instance['alignment'] ) ? 'selected="selected"' : ''; ?>><?php esc_attr_e( 'Right', 'fusion-core' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'menu_padding' ) ); ?>"><?php esc_attr_e( 'Menu Padding:', 'fusion-core' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'menu_padding' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'menu_padding' ) ); ?>" value="<?php echo esc_attr( $instance['menu_padding'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'menu_link_color' ) ); ?>"><?php esc_attr_e( 'Menu Link Color:', 'fusion-core' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'menu_Link_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'menu_link_color' ) ); ?>" value="<?php echo esc_attr( $instance['menu_link_color'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'menu_link_hover_color' ) ); ?>"><?php esc_attr_e( 'Menu Link Hover Color:', 'fusion-core' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'menu_link_hover_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'menu_link_hover_color' ) ); ?>" value="<?php echo esc_attr( $instance['menu_link_hover_color'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sep_text' ) ); ?>"><?php esc_attr_e( 'Separator Text:', 'fusion-core' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'sep_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sep_text' ) ); ?>" value="<?php echo esc_attr( $instance['sep_text'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>"><?php esc_attr_e( 'Font Size:', 'fusion-core' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'font_size' ) ); ?>" value="<?php echo esc_attr( $instance['font_size'] ); ?>" />
		</p>
		<?php

	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
