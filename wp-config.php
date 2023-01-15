<?php
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
define( 'DB_NAME', 'test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'H^2wTT3>s>6getn!z[ilbN%},zrO_`ZdSmY^O(CpibSV{iov({)WW7`21Zz;iE2Y' );
define( 'SECURE_AUTH_KEY',  '|^%sXbF9*G&~2Go}z9*aRRmiDJG[1t*lQ<!?(N(5hdI[>inv-ko#O,K]P)k}i?kX' );
define( 'LOGGED_IN_KEY',    'GH;#+uO<O0P5-p. D!e*&n`H$`P5 }^tCi8&t4<C}/xVo%^>jOX0QpZ]X}}YLvL|' );
define( 'NONCE_KEY',        '6Vp_8{:<LOT,}4[A(_%V(vj$r[aUu!Nkk~^Bwzh%EQZxGPp6k5[SzD2+:S*FQg/n' );
define( 'AUTH_SALT',        'YQpt]Udoz,T0H=!?lc*h6q>hx dzi^w&Z}$lezA(FYOuRYSean,*:_c({|HwS26{' );
define( 'SECURE_AUTH_SALT', 'TZwrzL0/WN@.^XT&}{f[?LII-r97C7b;&QbqIN$7=XITV>k=<Nx  s5q[:iooJs9' );
define( 'LOGGED_IN_SALT',   '2QdBZ.eFS0`C/PuFsKmSU :]8/4P/(RSg556t3?pL?#-U.]ZAVlp@2k%!V6JS#d&' );
define( 'NONCE_SALT',       '=?}DeEGEXH.(_mk9|+~3#XBU9-=culyT)(nS~6XMkz!v)xoK.)xI dIuWguT(t>f' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
