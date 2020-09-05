<?php
/**
 * Adds Page Options import / export feature.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Adds Page Options import / export feature.
 */
class Avada_Page_Options {

	/**
	 * WP Filesystem object.
	 *
	 * @access private
	 * @since 5.3
	 * @var object
	 */
	private $wp_filesystem;

	/**
	 * Page Options Export directory path.
	 *
	 * @access private
	 * @since 5.3
	 * @var string
	 */
	private $po_dir_path;

	/**
	 * Page Options Export URL.
	 *
	 * @access private
	 * @since 5.3
	 * @var string
	 */
	private $po_dir_url;

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @since 5.3
	 */
	public function __construct() {

		$this->wp_filesystem = Fusion_Helper::init_filesystem();

		$upload_dir        = wp_upload_dir();
		$this->po_dir_path = wp_normalize_path( trailingslashit( $upload_dir['basedir'] ) . 'fusion-page-options-export/' );
		$this->po_dir_url  = trailingslashit( $upload_dir['baseurl'] ) . 'fusion-page-options-export/';

		add_filter( 'avada_metabox_tabs', [ $this, 'add_options_tab' ], 10, 2 );
		add_action( 'init', [ $this, 'export_options' ] );
		add_action( 'wp_ajax_fusion_page_options_import', [ $this, 'ajax_import_options' ] );
		add_action( 'wp_ajax_fusion_page_options_save', [ $this, 'ajax_save_options_dataset' ] );
		add_action( 'wp_ajax_fusion_page_options_delete', [ $this, 'ajax_delete_options_dataset' ] );
		add_action( 'wp_ajax_fusion_page_options_import_saved', [ $this, 'ajax_import_options_saved' ] );
	}

	/**
	 * Adds Page Options Tab
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $tabs      The requested tabs.
	 * @param string $post_type Post type.
	 * @return array
	 */
	public function add_options_tab( $tabs, $post_type ) {

		$tab_key  = 'avada_page_options';
		$tab_name = esc_html__( 'Import / Export', 'Avada' );

		$tabs['requested_tabs'][]       = $tab_key;
		$tabs['tabs_names'][ $tab_key ] = $tab_name;
		$tabs['tabs_path'][ $tab_key ]  = Avada::$template_dir_path . '/includes/metaboxes/tabs/tab_' . $tab_key . '.php';

		return $tabs;
	}

	/**
	 * AJAX callback function. Used to export Page Options.
	 *
	 * @access public
	 * @since 5.3
	 * @return void
	 */
	public function export_options() {

		if ( ! isset( $_GET['action'] ) || 'download-avada-po' !== $_GET['action'] ) { // phpcs:ignore WordPress.Security
			return;
		}

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			wp_die();
		}

		$post_id = 0;
		if ( isset( $_GET['post_id'] ) ) {
			$post_id = absint( $_GET['post_id'] );
		}

		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/txt' );
		header( 'Content-Disposition: attachment; filename="avada-options-' . $post_id . '-' . date( 'd-m-Y' ) . '.json"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );

		echo wp_json_encode( $this->get_avada_post_custom_fields( $post_id ) );
		wp_die();
	}

	/**
	 * Gets all Avada's custom fields for specified post.
	 *
	 * @access private
	 * @since 5.3
	 * @param int $post_id Post ID.
	 * @return array
	 */
	private function get_avada_post_custom_fields( $post_id ) {

		$post_custom_fields  = get_post_custom( $post_id );
		$avada_custom_fields = [];

		foreach ( $post_custom_fields as $key => $value ) {
			if ( 0 === strpos( $key, 'pyre_' ) || 0 === strpos( $key, 'sbg_' ) ) {
				$avada_custom_fields[ $key ] = isset( $value[0] ) ? maybe_unserialize( $value[0] ) : '';
			}
		}

		return $avada_custom_fields;
	}

	/**
	 * AJAX callback function. Used to import Page Options.
	 *
	 * @access public
	 * @since 5.3
	 * @return void
	 */
	public function ajax_import_options() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );
		$response = [];

		$post_id = 0;
		if ( isset( $_POST['post_id'] ) ) {
			$post_id = absint( $_POST['post_id'] );
		}

		if ( ! isset( $_FILES['po_file_upload']['name'] ) ) {
			wp_die();
		}

		// Do NOT use wp_usnlash() here as it breaks imports on windows machines.
		$json_file_path = wp_normalize_path( $this->po_dir_path . $_FILES['po_file_upload']['name'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( ! file_exists( $this->po_dir_path ) ) {
			wp_mkdir_p( $this->po_dir_path );
		}

		if ( ! isset( $_FILES['po_file_upload'] ) || ! isset( $_FILES['po_file_upload']['tmp_name'] ) ) {
			wp_die();
		}

		// We're already checking if defined above.
		// Do NOT use wp_usnlash() here as it breaks imports on windows machines.
		if ( ! $this->wp_filesystem->move( wp_normalize_path( $_FILES['po_file_upload']['tmp_name'] ), $json_file_path, true ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			wp_die();
		}

		$content_json = $this->wp_filesystem->get_contents( $json_file_path );

		$custom_fields = json_decode( $content_json, true );
		if ( $custom_fields ) {
			$response['custom_fields'] = $custom_fields;
		}

		$this->wp_filesystem->delete( $json_file_path );

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Updates Avada's custom fields from specified array.
	 *
	 * @access private
	 * @since 5.3
	 * @param int   $post_id Post ID.
	 * @param array $custom_fields Array of custom fields.
	 * @return void
	 */
	private function update_avada_custom_fields( $post_id, $custom_fields ) {

		foreach ( $custom_fields as $key => $value ) {
			if ( 0 === strpos( $key, 'pyre_' ) || 0 === strpos( $key, 'sbg_' ) ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	/**
	 * AJAX callback function. Used to save Page Options.
	 *
	 * @access public
	 * @since 5.9.2
	 * @return void
	 */
	public function ajax_save_options_dataset() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );

		$post_id       = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;
		$options_title = isset( $_GET['options_title'] ) ? sanitize_text_field( wp_unslash( $_GET['options_title'] ) ) : '';

		$custom_fields = $this->get_avada_post_custom_fields( $post_id );

		$new_item = $this->insert_options_dataset( $options_title, $custom_fields );

		echo wp_json_encode(
			[
				'saved_po_dataset_id'    => $new_item['id'],
				'saved_po_dataset_title' => $new_item['title'],
			]
		);
		wp_die();
	}

	/**
	 * AJAX callback function. Used to delete Page Options.
	 *
	 * @access public
	 * @since 5.9.2
	 * @return void
	 */
	public function ajax_delete_options_dataset() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );

		$saved_po_dataset_id = 0;
		if ( isset( $_GET['saved_po_dataset_id'] ) ) {
			$saved_po_dataset_id = sanitize_text_field( wp_unslash( $_GET['saved_po_dataset_id'] ) );
		}

		$this->delete_options_dataset( $saved_po_dataset_id );
		wp_die();
	}

	/**
	 * Creates new post with custom fields.
	 *
	 * @access private
	 * @since 5.9.2
	 * @param string $options_title Name of the options to be saved.
	 * @param array  $custom_fields Array of custom fields to be saved.
	 * @return array                Returns details of the saved dataset: ['id' =>'','title'=>'','data'=>[]].
	 */
	private function insert_options_dataset( $options_title = '', $custom_fields ) {
		$all_options = get_option( 'avada_page_options', [] );

		if ( empty( $options_title ) ) {
			/* translators: Number. */
			$options_title = sprintf( __( 'Custom page options %d ', 'Avada' ), count( $all_options ) + 1 );
		}

		$id       = md5( $options_title . wp_json_encode( $custom_fields ) );
		$new_item = [
			'id'    => $id,
			'title' => $options_title,
			'data'  => $custom_fields,
		];

		$all_options[] = $new_item;
		update_option( 'avada_page_options', $all_options );
		return $new_item;
	}

	/**
	 * Deletes previously saved options-dataset.
	 *
	 * @access private
	 * @since 5.9.2
	 * @param string $id ID of options-dataset which needs to be deleted.
	 * @return void
	 */
	private function delete_options_dataset( $id = '' ) {
		$all_options = get_option( 'avada_page_options', [] );
		foreach ( $all_options as $key => $option ) {
			if ( isset( $option['id'] ) && $id === $option['id'] ) {
				unset( $all_options[ $key ] );
				update_option( 'avada_page_options', $all_options );
				return;
			}
		}
	}

	/**
	 * AJAX callback function. Used to import Page Options from previously saved set.
	 *
	 * @access public
	 * @since 5.3
	 * @return void
	 */
	public function ajax_import_options_saved() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );

		$saved_po_dataset_id = '';
		if ( isset( $_GET['saved_po_dataset_id'] ) ) {
			$saved_po_dataset_id = sanitize_text_field( wp_unslash( $_GET['saved_po_dataset_id'] ) );
		}

		$custom_fields = $this->get_options_by_id( $saved_po_dataset_id );

		echo wp_json_encode(
			[
				'custom_fields' => $custom_fields,
			]
		);
		wp_die();

	}

	/**
	 * Gets page-options by page-options ID.
	 *
	 * @access public
	 * @since 5.9.2
	 * @param string $id The page-options ID.
	 * @return array
	 */
	public function get_options_by_id( $id = '' ) {
		$all_options = get_option( 'avada_page_options', [] );
		foreach ( $all_options as $option ) {
			if ( isset( $option['id'] ) && $id === $option['id'] && isset( $option['data'] ) ) {
				return $option['data'];
			}
		}
		return [];
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
