<?php
/**
 * Avada Content importer.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Importer
 * @since      5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


require_once Avada::$template_dir_path . '/includes/importer/avada-import-functions.php';
require_once Avada::$template_dir_path . '/includes/importer/class-avada-demo-import.php';
require_once Avada::$template_dir_path . '/includes/importer/class-avada-demo-remove.php';

new Avada_Demo_Import();

new Avada_Demo_Remove();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
