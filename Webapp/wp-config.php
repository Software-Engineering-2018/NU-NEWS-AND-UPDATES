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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'KBey`xqy-cZU,lD-]Cv><PgL}8@X{GE6=SV!Pieoc3o_hTgzs9DD>P-)b]!v4K0t');
define('SECURE_AUTH_KEY',  '$QD$m5A[9[^&PA[S2[m8Sde%xr0qta9Dh|,E2~6bc}bKdiXCN! Zfc;~YBt1[=83');
define('LOGGED_IN_KEY',    'Wj!ywpC(7m5!U]pd$V`%G!G8pg<zrS8RO9t.F0 f zS>18mDBhc%lf/4qYb[UA{?');
define('NONCE_KEY',        'AyMp^k#c }1osyumhOR7Aq*^HrxtsBr|AB]eN,}W*[4K_G-Np2|UpqG9_87)oat|');
define('AUTH_SALT',        'Ve}S6aW-.{=NY#LV`i&G,$vgJ$G(xJ+}:D(qy/]z~NS=5?b:])vA<mRoIQH!W)##');
define('SECURE_AUTH_SALT', 'D!kIUS_0e<su`.v+${9vT5:iY:f1]ejA38:uJ]+/=a.`HiDOK3f}j7~4yNlt.{=N');
define('LOGGED_IN_SALT',   '>[${0B~iqZkMT.LwWY;d-A2@O;gqmPg?4_5QYF-w[Y*qsq{0rGA2>x(T4mHcB $$');
define('NONCE_SALT',       'Hi5;I %J|wL@e!3p_1m#fh[]l!3^zK^&j_yV;Mj`WWk%zY[+=8NVW1v40 *h@~}|');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
