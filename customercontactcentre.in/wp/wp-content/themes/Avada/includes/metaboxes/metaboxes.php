<?php
/**
 * The metaboxes class.
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

/**
 * The Metaboxes class.
 */
class PyreThemeFrameworkMetaboxes {

	/**
	 * An instance of this object.
	 *
	 * @static
	 * @access public
	 * @since 6.0
	 * @var PyreThemeFrameworkMetaboxes
	 */
	public static $instance;

	/**
	 * The settings.
	 *
	 * @access public
	 * @var array
	 */
	public $data;

	/**
	 * The class constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		self::$instance = $this;
		$this->data     = Avada()->settings->get_all();

		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 11 );
		add_action( 'save_post', [ $this, 'save_meta_boxes' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_script_loader' ], 99 );

	}

	/**
	 * Load backend scripts.
	 *
	 * @access public
	 */
	public function admin_script_loader() {

		$screen = get_current_screen();
		if ( isset( $screen->post_type ) && in_array( $screen->post_type, apply_filters( 'avada_hide_page_options', [] ) ) ) {
			return;
		}
		$theme_info = wp_get_theme();

		wp_enqueue_script(
			'jquery.biscuit',
			Avada::$template_dir_url . '/assets/admin/js/jquery.biscuit.js',
			[ 'jquery' ],
			$theme_info->get( 'Version' ),
			false
		);
		wp_register_script(
			'avada_upload',
			Avada::$template_dir_url . '/assets/admin/js/upload.js',
			[ 'jquery' ],
			$theme_info->get( 'Version' ),
			false
		);
		wp_enqueue_script( 'avada_upload' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-button' );

		// Select field assets.
		wp_dequeue_script( 'tribe-events-select2' );

		wp_enqueue_style(
			'select2-css',
			Avada::$template_dir_url . '/assets/admin/css/select2.css',
			[],
			'4.0.3',
			'all'
		);
		wp_enqueue_script(
			'selectwoo-js',
			Avada::$template_dir_url . '/assets/admin/js/selectWoo.full.min.js',
			[ 'jquery' ],
			'1.0.2',
			false
		);

		// Range field assets.
		wp_enqueue_style(
			'avadaredux-nouislider-css',
			FUSION_LIBRARY_URL . '/inc/redux/framework/FusionReduxCore/inc/fields/slider/vendor/nouislider/fusionredux.jquery.nouislider.css',
			[],
			'5.0.0',
			'all'
		);

		wp_enqueue_script(
			'avadaredux-nouislider-js',
			Avada::$template_dir_url . '/assets/admin/js/jquery.nouislider.min.js',
			[ 'jquery' ],
			'5.0.0',
			true
		);
		wp_enqueue_script(
			'wnumb-js',
			Avada::$template_dir_url . '/assets/admin/js/wNumb.js',
			[ 'jquery' ],
			'1.0.2',
			true
		);

		// Color fields.
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script(
			'wp-color-picker-alpha',
			Avada::$template_dir_url . '/assets/admin/js/wp-color-picker-alpha.js',
			[ 'wp-color-picker' ],
			$theme_info->get( 'Version' ),
			false
		);

		// General JS for fields.
		wp_enqueue_script(
			'avada-fusion-options',
			Avada::$template_dir_url . '/assets/admin/js/avada-fusion-options.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			$theme_info->get( 'Version' ),
			false
		);

	}

	/**
	 * Gets the tabs for post type.
	 *
	 * @access public
	 * @param string $posttype post type.
	 * @since 6.0
	 */
	public static function get_pagetype_tab( $posttype = false ) {
		$pagetype_data = [
			'page'            => [ 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ],
			'post'            => [ 'post', 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ],
			'avada_faq'       => [ 'post', 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ],
			'avada_portfolio' => [ 'portfolio_post', 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ],
			'product'         => [ 'page', 'header', 'footer', 'sidebars', 'sliders', 'background', 'pagetitlebar' ],
			'tribe_events'    => [ 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ],
		];
		if ( ! isset( $posttype ) || ! $posttype ) {
			$posttype = get_post_type();
		}
		if ( isset( $pagetype_data[ $posttype ] ) ) {
			return $pagetype_data[ $posttype ];
		}
		return [ 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ];
	}

	/**
	 * Gets the options for page type.
	 *
	 * @access public
	 * @since 6.0
	 * @return array
	 */
	public function get_options() {
		if ( ! isset( $pagetype ) ) {
			$pagetype = get_post_type();
		}

		$tabs     = $this::get_pagetype_tab( $pagetype );
		$sections = [];

		if ( is_array( $tabs ) ) {
			foreach ( $tabs as $tab_name ) {
				$path = Avada::$template_dir_path . '/includes/metaboxes/tabs/tab_' . $tab_name . '.php';
				require_once wp_normalize_path( $path );
				if ( function_exists( 'avada_page_options_tab_' . $tab_name ) ) {
					$sections = call_user_func( 'avada_page_options_tab_' . $tab_name, $sections );
				}
			}
		}

		return $sections;
	}

	/**
	 * Adds the metaboxes.
	 *
	 * @access public
	 */
	public function add_meta_boxes() {

		$post_types = get_post_types(
			[
				'public' => true,
			]
		);

		$disallowed = [ 'page', 'post', 'attachment', 'avada_portfolio', 'themefusion_elastic', 'product', 'wpsc-product', 'slide', 'tribe_events' ];

		$disallowed = array_merge( $disallowed, apply_filters( 'avada_hide_page_options', [] ) );
		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $disallowed ) ) {
				continue;
			}
			$this->add_meta_box( 'post_options', 'Avada Options', $post_type );
		}

		$this->add_meta_box( 'post_options', 'Fusion Page Options', 'avada_faq' );
		$this->add_meta_box( 'post_options', 'Fusion Page Options', 'post' );
		$this->add_meta_box( 'page_options', 'Fusion Page Options', 'page' );
		$this->add_meta_box( 'portfolio_options', 'Fusion Page Options', 'avada_portfolio' );
		$this->add_meta_box( 'es_options', 'Elastic Slide Options', 'themefusion_elastic' );
		$this->add_meta_box( 'woocommerce_options', 'Fusion Page Options', 'product' );
		$this->add_meta_box( 'slide_options', 'Slide Options', 'slide' );
		$this->add_meta_box( 'events_calendar_options', 'Events Calendar Options', 'tribe_events' );

	}

	/**
	 * Adds a metabox.
	 *
	 * @access public
	 * @param string $id        The metabox ID.
	 * @param string $label     The metabox label.
	 * @param string $post_type The post-type.
	 */
	public function add_meta_box( $id, $label, $post_type ) {
		add_meta_box( 'pyre_' . $id, $label, [ $this, $id ], $post_type, 'advanced', 'high' );
	}

	/**
	 * Saves the metaboxes.
	 *
	 * @access public
	 * @param string|int $post_id The post ID.
	 */
	public function save_meta_boxes( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$fusion_meta = array_intersect_key( $_POST, array_flip( preg_grep( '/^pyre_/', array_keys( $_POST ) ) ) ); // phpcs:ignore WordPress.Security.NonceVerification

		foreach ( $fusion_meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

	}

	/**
	 * Handle rendering options for pages.
	 *
	 * @access public
	 */
	public function page_options() {
		$this->render_option_tabs( $this::get_pagetype_tab( 'page' ) );
	}

	/**
	 * Handle rendering options for posts.
	 *
	 * @access public
	 */
	public function post_options() {
		$this->render_option_tabs( $this::get_pagetype_tab( 'post' ) );
	}

	/**
	 * Handle rendering options for portfolios.
	 *
	 * @access public
	 */
	public function portfolio_options() {
		$this->render_option_tabs( $this::get_pagetype_tab( 'avada_portfolio' ) );
	}

	/**
	 * Handle rendering options for woocommerce.
	 *
	 * @access public
	 */
	public function woocommerce_options() {
		$this->render_option_tabs( $this::get_pagetype_tab( 'product' ), 'product' );
	}

	/**
	 * Handle rendering options for ES.
	 *
	 * @access public
	 */
	public function es_options() {
		include 'options/options_es.php';
	}

	/**
	 * Handle rendering options for slides.
	 *
	 * @access public
	 */
	public function slide_options() {
		include 'options/options_slide.php';
	}

	/**
	 * Handle rendering options for events.
	 *
	 * @access public
	 */
	public function events_calendar_options() {
		$this->render_option_tabs( $this::get_pagetype_tab( 'tribe_events' ) );
	}

	/**
	 * Render fields within tab.
	 *
	 * @access public
	 * @param array $tab_data The tab map.
	 * @since 6.0
	 */
	public function render_tab_fields( $tab_data ) {
		if ( ! is_array( $tab_data ) ) {
			return;
		}
		foreach ( $tab_data['fields'] as $field ) {
			// Defaults.
			$field['id']          = isset( $field['id'] ) ? $field['id'] : '';
			$field['label']       = isset( $field['label'] ) ? $field['label'] : '';
			$field['choices']     = isset( $field['choices'] ) ? $field['choices'] : [];
			$field['description'] = isset( $field['description'] ) ? $field['description'] : '';
			$field['default']     = isset( $field['default'] ) ? $field['default'] : '';
			$field['dependency']  = isset( $field['dependency'] ) ? $field['dependency'] : [];

			switch ( $field['type'] ) {
				case 'radio-buttonset':
					$this->radio_buttonset( $field['id'], $field['label'], $field['choices'], $field['description'], $field['default'], $field['dependency'] );
					break;
				case 'color-alpha':
					$this->color( $field['id'], $field['label'], $field['description'], true, $field['dependency'], $field['default'] );
					break;
				case 'color':
					$this->color( $field['id'], $field['label'], $field['description'], false, $field['dependency'], $field['default'] );
					break;
				case 'media':
				case 'media_url':
					$this->upload( $field['id'], $field['label'], $field['description'], $field['dependency'] );
					break;
				case 'select':
					$this->select( $field['id'], $field['label'], $field['choices'], $field['description'], $field['dependency'] );
					break;
				case 'dimensions':
					$this->dimension( $field['value'], $field['label'], $field['description'], $field['dependency'] );
					break;
				case 'text':
					$this->text( $field['id'], $field['label'], $field['description'], $field['dependency'] );
					break;
				case 'textarea':
					$this->textarea( $field['id'], $field['label'], $field['description'], $field['default'], $field['dependency'] );
					break;
				case 'custom':
					$this->raw( $field['id'], $field['label'], $field['description'], $field['dependency'] );
					break;
				case 'hidden':
					$this->hidden( $field['id'], $field['default'] );
					break;
				case 'slider':
					$this->range( $field['id'], $field['label'], $field['description'], $field['choices']['min'], $field['choices']['max'], $field['choices']['step'], $field['default'], '', $field['dependency'] );
					break;
				case 'sortable':
					$this->sortable( $field['id'], $field['label'], $field['choices'], $field['description'], $field['dependency'], $field['default'] );
					break;
			}
		}
	}

	/**
	 * Handle rendering options.
	 *
	 * @access public
	 * @param array  $requested_tabs The requested tabs.
	 * @param string $post_type      The post-type.
	 */
	public function render_option_tabs( $requested_tabs, $post_type = 'default' ) {
		$screen = get_current_screen();

		$tabs_names = [
			'sliders'        => esc_html__( 'Sliders', 'Avada' ),
			'page'           => esc_html__( 'Page', 'Avada' ),
			'post'           => ( 'avada_faq' === $screen->post_type ) ? esc_html__( 'FAQ', 'Avada' ) : esc_html__( 'Post', 'Avada' ),
			'header'         => esc_html__( 'Header', 'Avada' ),
			'footer'         => esc_html__( 'Footer', 'Avada' ),
			'sidebars'       => esc_html__( 'Sidebars', 'Avada' ),
			'background'     => esc_html__( 'Background', 'Avada' ),
			'pagetitlebar'   => esc_html__( 'Page Title Bar', 'Avada' ),
			'portfolio_post' => esc_html__( 'Portfolio', 'Avada' ),
			'product'        => esc_html__( 'Product', 'Avada' ),
		];

		$tabs = [
			'requested_tabs' => $requested_tabs,
			'tabs_names'     => $tabs_names,
			'tabs_path'      => [],
		];

		$tabs = apply_filters( 'avada_metabox_tabs', $tabs, $post_type );
		?>

		<ul class="pyre_metabox_tabs">

			<?php foreach ( $tabs['requested_tabs'] as $key => $tab_name ) : ?>
				<?php $class_active = ( 0 === $key ) ? 'active' : ''; ?>
				<?php if ( 'page' === $tab_name && 'product' === $post_type ) : ?>
					<li class="<?php echo esc_attr( $class_active ); ?>"><a href="<?php echo esc_attr( $tab_name ); ?>"><?php echo esc_attr( $tabs['tabs_names'][ $post_type ] ); ?></a></li>
				<?php else : ?>
					<li class="<?php echo esc_attr( $class_active ); ?>"><a href="<?php echo esc_attr( $tab_name ); ?>"><?php echo esc_attr( $tabs['tabs_names'][ $tab_name ] ); ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>

		</ul>

		<div class="pyre_metabox">

			<?php foreach ( $tabs['requested_tabs'] as $key => $tab_name ) : ?>
				<div class="pyre_metabox_tab" id="pyre_tab_<?php echo esc_attr( $tab_name ); ?>">
				<?php
				$path = ! empty( $tabs['tabs_path'][ $tab_name ] ) ? $tabs['tabs_path'][ $tab_name ] : dirname( __FILE__ ) . '/tabs/tab_' . $tab_name . '.php';
				require_once wp_normalize_path( $path );
				if ( function_exists( 'avada_page_options_tab_' . $tab_name ) ) {
					$tab_data = call_user_func( 'avada_page_options_tab_' . $tab_name, [] );
					$this->render_tab_fields( $tab_data[ $tab_name ] );
				}
				?>
				</div>
			<?php endforeach; ?>

		</div>
		<div class="clear"></div>
		<?php

	}

	/**
	 * Text controls.
	 *
	 * @access public
	 * @param string $id         The ID.
	 * @param string $label      The label.
	 * @param string $desc       The description.
	 * @param array  $dependency The dependencies array.
	 */
	public function text( $id, $label, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $label ); ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<input type="text" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, 'pyre_' . $id, true ) ); ?>" />
			</div>
		</div>
		<?php

	}

	/**
	 * Select controls.
	 *
	 * @access public
	 * @param string $id         The ID.
	 * @param string $label      The label.
	 * @param array  $options    The options array.
	 * @param string $desc       The description.
	 * @param array  $dependency The dependencies array.
	 */
	public function select( $id, $label, $options, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $label ); ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<select id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" style="width:100%">
					<?php foreach ( $options as $key => $option ) : ?>
						<?php $selected = ( get_post_meta( $post->ID, 'pyre_' . $id, true ) == $key ) ? 'selected="selected"' : ''; // phpcs:ignore WordPress.PHP.StrictComparisons ?>
						<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $option ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php

	}

	/**
	 * Color picker field.
	 *
	 * @access public
	 * @since 5.0.0
	 * @param string  $id         ID of input field.
	 * @param string  $label      Label of field.
	 * @param string  $desc       Description of field.
	 * @param boolean $alpha      Whether or not to show alpha.
	 * @param array   $dependency The dependencies array.
	 * @param string  $default    Default value from TO.
	 */
	public function color( $id, $label, $desc = '', $alpha = false, $dependency = [], $default = '' ) {
		global $post;
		$styling_class = ( $alpha ) ? 'colorpickeralpha' : 'colorpicker';

		if ( $default ) {
			if ( ! $alpha && ( 'transparent' === $default || ! is_string( $default ) ) ) {
				$default = '#ffffff';
			}
			$desc .= '  <span class="pyre-default-reset"><a href="#" id="default-' . $id . '" class="fusion-range-default fusion-hide-from-atts" type="radio" name="' . $id . '" value="" data-default="' . $default . '">' . esc_attr( 'Reset to default.', 'Avada' ) . '</a><span>' . esc_attr( 'Using default value.', 'Avada' ) . '</span></span>';
		}
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field avada-color <?php echo esc_attr( $styling_class ); ?>">
				<input id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" class="fusion-builder-color-picker-hex color-picker" type="text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'pyre_' . $id, true ) ); ?>" <?php echo ( $alpha ) ? 'data-alpha="true"' : ''; ?> <?php echo ( $default ) ? 'data-default="' . esc_attr( $default ) . '"' : ''; ?> />
			</div>
		</div>
		<?php

	}

	/**
	 * Range field.
	 *
	 * @since 5.0.0
	 * @param string           $id         ID of input field.
	 * @param string           $label      Label of field.
	 * @param string           $desc       The description.
	 * @param string|int|float $min        The minimum value.
	 * @param string|int|float $max        The maximum value.
	 * @param string|int|float $step       The steps value.
	 * @param string|int|float $default    The default value.
	 * @param string|int|float $value      The value.
	 * @param array            $dependency The dependencies array.
	 */
	public function range( $id, $label, $desc = '', $min, $max, $step, $default, $value, $dependency = [] ) {
		global $post;
		if ( isset( $default ) && '' !== $default ) {
			$desc .= '  <span class="pyre-default-reset"><a href="#" id="default-' . $id . '" class="fusion-range-default fusion-hide-from-atts" type="radio" name="' . $id . '" value="" data-default="' . $default . '">' . esc_attr( 'Reset to default.', 'Avada' ) . '</a><span>' . esc_attr( 'Using default value.', 'Avada' ) . '</span></span>';
		}
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $label ); ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field avada-range">
				<?php
					$default_status = ( ( $default ) ? 'fusion-with-default' : '' );
					$is_checked     = ( '' == get_post_meta( $post->ID, 'pyre_' . $id, true ) ); // phpcs:ignore WordPress.PHP.StrictComparisons
					$regular_id     = ( ( '' != get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ? $id : 'slider' . $id ); // phpcs:ignore WordPress.PHP.StrictComparisons
					$display_value  = ( ( '' == get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ? $default : get_post_meta( $post->ID, 'pyre_' . $id, true ) ); // phpcs:ignore WordPress.PHP.StrictComparisons
				?>
				<input
					type="text"
					name="<?php echo esc_attr( $id ); ?>"
					id="<?php echo esc_attr( $regular_id ); ?>"
					value="<?php echo esc_attr( $display_value ); ?>"
					class="fusion-slider-input <?php echo esc_attr( $default_status ); ?> <?php echo ( isset( $default ) && '' !== $default ) ? 'fusion-hide-from-atts' : ''; ?>" />
				<div
					class="fusion-slider-container"
					data-id="<?php echo esc_attr( $id ); ?>"
					data-min="<?php echo esc_attr( $min ); ?>"
					data-max="<?php echo esc_attr( $max ); ?>"
					data-step="<?php echo esc_attr( $step ); ?>">
				</div>
				<?php if ( isset( $default ) && '' !== $default ) : ?>
					<input
						type="hidden"
						id="pyre_<?php echo esc_attr( $id ); ?>"
						name="pyre_<?php echo esc_attr( $id ); ?>"
						value="<?php echo esc_attr( get_post_meta( $post->ID, 'pyre_' . $id, true ) ); ?>"
						class="fusion-hidden-value" />
				<?php endif; ?>

			</div>
		</div>
		<?php

	}

	/**
	 * Radio button set field.
	 *
	 * @since 5.0.0
	 * @param string           $id         ID of input field.
	 * @param string           $label      Label of field.
	 * @param array            $options    Options to select from.
	 * @param string           $desc       Description of field.
	 * @param string|int|float $default    The default value.
	 * @param array            $dependency The dependencies array.
	 */
	public function radio_buttonset( $id, $label, $options, $desc = '', $default = '', $dependency = [] ) {
		global $post;
		$options_reset = $options;

		reset( $options_reset );

		if ( '' === $default ) {
			$default = key( $options_reset );
		}

		$value = ( '' == get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ? $default : get_post_meta( $post->ID, 'pyre_' . $id, true ); // phpcs:ignore WordPress.PHP.StrictComparisons
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field avada-buttonset">
				<div class="fusion-form-radio-button-set ui-buttonset">
					<input type="hidden" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" class="button-set-value" />
					<?php foreach ( $options as $key => $option ) : ?>
						<?php $selected = ( $key == $value ) ? ' ui-state-active' : ''; // phpcs:ignore WordPress.PHP.StrictComparisons ?>
						<a href="#" class="ui-button buttonset-item<?php echo esc_attr( $selected ); ?>" data-value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $option ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * Dimensions field.
	 *
	 * @since 5.0.0
	 * @param array  $ids        IDs of input fields.
	 * @param string $label      Label of field.
	 * @param string $desc       Description of field.
	 * @param array  $dependency The dependencies array.
	 */
	public function dimension( $ids, $label, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php $ids = ( ! isset( $ids[0] ) && is_array( $ids ) ) ? array_keys( $ids ) : $ids; ?>
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $ids[0] ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field avada-dimension">
				<?php foreach ( $ids as $field_id ) : ?>
					<?php
					$icon_class = 'fusiona-expand width';
					if ( false !== strpos( $field_id, 'height' ) ) {
						$icon_class = 'fusiona-expand  height';
					}
					if ( false !== strpos( $field_id, 'top' ) ) {
						$icon_class = 'dashicons dashicons-arrow-up-alt';
					}
					if ( false !== strpos( $field_id, 'right' ) ) {
						$icon_class = 'dashicons dashicons-arrow-right-alt';
					}
					if ( false !== strpos( $field_id, 'bottom' ) ) {
						$icon_class = 'dashicons dashicons-arrow-down-alt';
					}
					if ( false !== strpos( $field_id, 'left' ) ) {
						$icon_class = 'dashicons dashicons-arrow-left-alt';
					}
					?>
					<div class="fusion-builder-dimension">
						<span class="add-on"><i class="<?php echo esc_attr( $icon_class ); ?>"></i></span>
						<input type="text" name="pyre_<?php echo esc_attr( $field_id ); ?>" id="pyre_<?php echo esc_attr( $field_id ); ?>"" value="<?php echo esc_attr( get_post_meta( $post->ID, 'pyre_' . $field_id, true ) ); ?>"" />
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php

	}

	/**
	 * Multiselect field.
	 *
	 * @param array  $id         IDs of input fields.
	 * @param string $label      Label of field.
	 * @param array  $options    The options to choose from.
	 * @param string $desc       Description of field.
	 * @param array  $dependency The dependencies array.
	 */
	public function multiple( $id, $label, $options, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<select multiple="multiple" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>[]">
					<?php foreach ( $options as $key => $option ) : ?>
						<?php $selected = ( is_array( get_post_meta( $post->ID, 'pyre_' . $id, true ) ) && in_array( $key, get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ) ? 'selected="selected"' : ''; ?>
						<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $option ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php

	}

	/**
	 * Textarea field.
	 *
	 * @param array  $id         IDs of input fields.
	 * @param string $label      Label of field.
	 * @param string $desc       Description of field.
	 * @param string $default    The default value.
	 * @param array  $dependency The dependencies array.
	 */
	public function textarea( $id, $label, $desc = '', $default = '', $dependency = [] ) {
		global $post;
		$db_value = get_post_meta( $post->ID, 'pyre_' . $id, true );
		$value    = ( metadata_exists( 'post', $post->ID, 'pyre_' . $id ) ) ? $db_value : $default;
		$rows     = 10;
		if ( 'heading' === $id || 'caption' === $id ) {
			$rows = 5;
		} elseif ( 'page_title_custom_text' === $id || 'page_title_custom_subheader' === $id ) {
			$rows = 1;
		}
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<textarea cols="120" rows="<?php echo (int) $rows; ?>" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
			</div>
		</div>
		<?php

	}

	/**
	 * Upload field.
	 *
	 * @param array  $id         IDs of input fields.
	 * @param string $label      Label of field.
	 * @param string $desc       Description of field.
	 * @param array  $dependency The dependencies array.
	 */
	public function upload( $id, $label, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<div class="pyre_upload">
					<?php $image_url = get_post_meta( $post->ID, 'pyre_' . $id, true ); ?>
					<input name="pyre_<?php echo esc_attr( $id ); ?>" class="upload_field" id="pyre_<?php echo esc_attr( $id ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>" />
					<?php
					$image_id = get_post_meta( $post->ID, 'pyre_' . $id . '_id', true );

					if ( ! $image_id && $image_url ) {
						$image_id = Fusion_Images::get_attachment_id_from_url( $image_url );
					}
					?>
					<input name="pyre_<?php echo esc_attr( $id ); ?>_id" class="upload_field_id" id="pyre_<?php echo esc_attr( $id ); ?>_id" type="hidden" value="<?php echo esc_attr( $image_id ); ?>" />
					<input class="fusion_upload_button button" type="button" value="<?php esc_attr_e( 'Browse', 'Avada' ); ?>" />
				</div>
			</div>
		</div>
		<?php

	}
	/**
	 * Hidden input.
	 *
	 * @since 5.0.0
	 * @param string $id    id of input field.
	 * @param string $value value of input field.
	 */
	public function hidden( $id, $value ) {
		global $post;
		?>
		<input type="hidden" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php

	}

	/**
	 * Sortable controls.
	 *
	 * @since 5.7
	 * @access public
	 * @param string       $id         The ID.
	 * @param string       $label      The label.
	 * @param array        $options    The options array.
	 * @param string       $desc       The description.
	 * @param array        $dependency The dependencies array.
	 * @param string|array $default    The default value.
	 */
	public function sortable( $id, $label, $options, $desc = '', $dependency = [], $default = '' ) {
		global $post;
		$sort_order_saved = get_post_meta( $post->ID, 'pyre_' . $id, true );
		$sort_order_saved = ( ! $sort_order_saved ) ? '' : $sort_order_saved;
		$sort_order       = ( empty( $sort_order_saved ) ) ? $default : $sort_order_saved;
		$sort_order       = ( is_array( $sort_order ) ) ? $sort_order : explode( ',', $sort_order );
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $label ); ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<ul class="fusion-sortable-options" id="pyre_<?php echo esc_attr( $id ); ?>">
					<?php foreach ( $sort_order as $item ) : ?>
						<?php $item = trim( $item ); ?>
						<?php if ( isset( $options[ $item ] ) ) : ?>
							<div class="fusion-sortable-option" data-value="<?php echo esc_attr( $item ); ?>">
								<span><?php echo esc_html( $options[ $item ] ); ?></span>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<input class="sort-order" type="hidden" id="pyre_<?php echo esc_attr( $id ); ?>" name="pyre_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $sort_order_saved ); ?>">
			</div>
		</div>
		<?php

	}

	/**
	 * Dependency markup.
	 *
	 * @since 5.0.0
	 * @param array $dependency dependence options.
	 * @return string $data_dependence markup
	 */
	private function dependency( $dependency = [] ) {

		// Disable dependencies if 'dependencies_status' is set to 0.
		if ( '0' === Avada()->settings->get( 'dependencies_status' ) ) {
			return '';
		}

		$data_dependency = '';
		if ( 0 < count( $dependency ) ) {
			$data_dependency .= '<div class="avada-dependency">';
			foreach ( $dependency as $dependence ) {
				$data_dependency .= '<span class="hidden" data-value="' . $dependence['value'] . '" data-field="' . $dependence['field'] . '" data-comparison="' . $dependence['comparison'] . '"></span>';
			}
			$data_dependency .= '</div>';
		}
		return $data_dependency;
	}

	/**
	 * Raw field.
	 *
	 * @since 5.3.0
	 * @param array  $id         IDs of input fields.
	 * @param string $label      Label of field.
	 * @param string $desc       Description of field.
	 * @param array  $dependency The dependencies array.
	 */
	public function raw( $id, $label, $desc = '', $dependency = [] ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<?php // No need to sanitize this, we already know what's in here. ?>
			<?php echo $this->dependency( $dependency ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="pyre_desc_raw">
				<label for="pyre_<?php echo esc_attr( $id ); ?>"><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>

		</div>
		<?php

	}
}

global $pagenow;

if ( is_admin() && ( ( in_array( $pagenow, [ 'post-new.php', 'post.php' ] ) ) || ! isset( $pagenow ) || apply_filters( 'fusion_page_options_init', false ) ) ) {
	if ( ! PyreThemeFrameworkMetaboxes::$instance ) {
		$metaboxes = new PyreThemeFrameworkMetaboxes();
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
