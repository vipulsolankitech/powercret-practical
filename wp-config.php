<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'practical' );

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
define( 'AUTH_KEY',         'R.>}8;vC5>%t@zW$0J+X`UEv695$r8+_2OS+.D)0:}o`$F,AVxa6NW!{7Eh6KJC}' );
define( 'SECURE_AUTH_KEY',  'p=j_:E:@_X88z4tghbI*5V+p=X`ev_v3Q8])A*O!.tUVTHGjXJs62Ml}#F7U@6]<' );
define( 'LOGGED_IN_KEY',    'K(a~C96)5M~szh_yX]c&P!u~4WayI!v/wY4Dt|Fa^LGFTg+4iYp,@4 BptU^gx(g' );
define( 'NONCE_KEY',        '2CS3i$x^Kt,SGcX<j ka/,++_So;wM^H59dV|l2lYohwU<soXY#zN3A{2B@~18W7' );
define( 'AUTH_SALT',        ';;k9CA:F7BE ]BaBZa{c><OXdnuUl:Sh.*==(*9s{V)j:Hm^GZMbnkE&AE4gGMu ' );
define( 'SECURE_AUTH_SALT', 'rG>+?y{%WOXJwIINn+0,<~R/z>Cz$kKx~FZ=;h>yi?k/al9VSkIw`GF(il!<l6.g' );
define( 'LOGGED_IN_SALT',   '!P}/ozwkSBaLN[ajFwwi)-fOjrn>]@b|.5)ut)_}l@@/H>4peD?Z4Y-gn_K[`>4m' );
define( 'NONCE_SALT',       'l$7Jk@09$+X|l~d%BX[jN]tfr1PG_t]LpKwtX%.0<):) sq::gD0gUE#$u2!?zk6' );
define('MY_PLUGIN_OPENAI_API_KEY', 'Open-ai-api-here');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'vp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
