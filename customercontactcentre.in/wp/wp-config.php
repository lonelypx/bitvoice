<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bitvo8by_wp789' );

/** MySQL database username */
define( 'DB_USER', 'bitvo8by_wp789' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Se13!p.pW5' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'rwxoiiq8xlkkdsivmlmgopmkigyultvewnp7pyo9ix2zzqupavm7th37x350skxw' );
define( 'SECURE_AUTH_KEY',  'fc4d8ozdl2uxok40op8nogrfawsera3wqdsx2ogklxxxzlznc7xvxtfc3epw1enu' );
define( 'LOGGED_IN_KEY',    'kbgqgqjrqsvxj6iavxnxowigmressjfn5lhdtjcunzyqkuqt8lbeeaohkuyji9hd' );
define( 'NONCE_KEY',        'cv3a3pccgobwrr8ayvrr4ahxux0ebyn4wmvjzpe1cor1chr2gu9yitijkfnrpnhl' );
define( 'AUTH_SALT',        'lcpoe9zpgwejku36obxnghslf2heomzadgmxkevjxlcw1rudghbyglmg4d0zkizn' );
define( 'SECURE_AUTH_SALT', 'zelavgm6xz2ahhvhmil8gf9k3amodjha17vgsxczmtqfgue08mkwlncjocr6hggb' );
define( 'LOGGED_IN_SALT',   '1fo5ezfxlsmgfjfep4nukgoxd5cfgy5axlwfuxnxqzw0eyzi1xoarrto2v4hfdqy' );
define( 'NONCE_SALT',       'sktqellequxurnnsg0vjezvfdbmf2yhczn4fyay90qiygbx5oiauy0yvot8puerd' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp6b_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
