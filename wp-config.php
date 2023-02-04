<?php
/** Enable W3 Total Cache */
define( 'WP_CACHE', true ); // Added by W3 Total Cache




/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "pmdev_wp_ob2hd" );

/** Database username */
define( 'DB_USER', "pmdev_wp_qlk5n" );

/** Database password */
define( 'DB_PASSWORD', "i7~TNdiidP9*DTm6" );

/** Database hostname */
define( 'DB_HOST', "localhost:3306" );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'kb7dssgygyoxhigza16hwptmotuqu46sv0lnexroeglno9mbk41szi25mvvgvq1h' );
define( 'SECURE_AUTH_KEY',  'ilxo3t6ktvplzqbpwcj2ihmutpkyuk6jtekkdqcxueaq3beebrjso7pxyimibv0x' );
define( 'LOGGED_IN_KEY',    'i0wdjbrljyqzueilwcgfo8xxu5sg3mm7t3zjuwpvb97wsoeoigziwytenqxezeiu' );
define( 'NONCE_KEY',        '53wono8c9a2apjej44j1zvskiooqemylvtrbjgmxh9v6wdqhp1lctxbxyyjmdrru' );
define( 'AUTH_SALT',        'mngu5ok6un7bcu4bbqsu2btxckn3kwr5h3urirt91nhzna6bdsrnsdjfwkclgbpf' );
define( 'SECURE_AUTH_SALT', 'khvs2q249bimvcdvvt9zidoeewrscn1jlmrwvwjngx5cusuyuej69doymqxw3att' );
define( 'LOGGED_IN_SALT',   'cgsjb4a4zhaezprfbtkxnjioiuuujmhheiojgyfg9vssmmpdaereywoc3ahnwnej' );
define( 'NONCE_SALT',       'vhw56pikz2btkkqt12oqtgxrckipesqz9cl52axtgvxmgjqmmllez7nf368nlkws' );


define( 'AS3CF_SETTINGS', serialize( array(
    'provider'     => 'gcp',
    'key-file-path' => '/home/pmdev/etc/google-cloud.json',
) ) );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wppm_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define('WP_MEMORY_LIMIT', '16000M');

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
define('W3TC_DYNAMIC_SECURITY', 'pmfragcache');
/* Add any custom values between this line and the "stop editing" line. */



define( 'DUPLICATOR_AUTH_KEY', '%[/HDSjc{LtZI5a!cZQE?&OT45XLxg/k?#sMj2bg-< la}D+*vTar<Ik50B]$p-U' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
define('CONCATENATE_SCRIPTS', false); 
require_once ABSPATH . 'wp-settings.php';