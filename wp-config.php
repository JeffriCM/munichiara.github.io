<?php
define( 'WP_MEMORY_LIMIT', '3000M' );
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'munichiara' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '{KDSN?12gk0[l6DvE[KGG*J)#0e8)3{b~s%[k7H^Ai_AtIyg,Fd8c?xkI~ab`QwT' );
define( 'SECURE_AUTH_KEY',  'O3 eiJ;6?[JAzn%PAxc*v<Otk#+^cN!zY@JVyec/L^ofjHF`d@Sf>Ld#c>16%?~k' );
define( 'LOGGED_IN_KEY',    'D!rDI+*9+?egH,/q(?8zU>`M08A.z]E%~I7;:/[2Dk$f|#Yn<;*o3qmvH(i-4yH0' );
define( 'NONCE_KEY',        'Jqv$pFo 7sbXOug4+6cznQv,RJM/5uJ(?$@4kRl?(h3Du-2Ir=u;L_}0yUrWa73T' );
define( 'AUTH_SALT',        'Fp-Z;.y>rWxtv4`]KA(0OA}MGQ;?e/H(0rmeRw;>$]Z:v>M8:0RmJ}zHmQBnY*DH' );
define( 'SECURE_AUTH_SALT', '8Z8sdD(Q0zHr>nDG+IAy[iV/V{IN=!yr9`FDelt.+eafk:J<rz`/7u)dt@**:(tw' );
define( 'LOGGED_IN_SALT',   'Ut4<j8ASe&?U`fD/8Pf~[$sk4ln*Rx-@54i^nQWOB@wxrxk1)n1J^f_#gyiL7[6=' );
define( 'NONCE_SALT',       'txC~L@U,IL&.Qt7B,G&T{PF15fjP7zhBzE#)3(. Y^&_@*c@GF+F/K>%s2k[0<6}' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
define( 'WP_MEMORY_LIMIT', '3000M' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
