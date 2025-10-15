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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_MEMORY_LIMIT', '8092M');
define( 'DB_NAME', 'icenure' );
/** MySQL database username */
define( 'DB_USER', 'iceadmin' );
/** MySQL database password */
define( 'DB_PASSWORD', 'Itc6nfrjd7' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
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
define('AUTH_KEY',         'ZHJEYx-v>VI^OT{Ppf)FRAq=udi1CIe.,>vGOsd(Vq-NrV@%$F(gP0IQ(|R~M}EU');
define('SECURE_AUTH_KEY',  '=rS}o6-sdb!,vSbm6//C,Q@t!3:pUDz<IQ.W-pP*w%-`+v+mE~qH,9[/XIFU@u^n');
define('LOGGED_IN_KEY',    'sya[)f(g9Ge%Bj^W4/aONpolGi_ZJlQ682|0}~H(}Og0+9LE8:+]|CPP1s5-0A`$');
define('NONCE_KEY',        'OA4qm5T)^=xpFB60f8A5ll48^>G.1tV[akPq|y{Kj}1zG?-BUJZ|!nw+%*J-G?gz');
define('AUTH_SALT',        '~OHH[_)#kj2xUoP9_=+a^<ZuJA@J_jb/*`Wafd1#&7t39+2o|4jKrO,v}~0-z%kH');
define('SECURE_AUTH_SALT', 'k36-%-A(!wvF-qxG:dx,]ww2UbpM<&ag+-%cFD%3$=U~~H6+#<G[T!P}Dz$Rq/IR');
define('LOGGED_IN_SALT',   'SM@`]G2A*w~+Jdo<lg(opm,-+st#.8&%r_:AFR[p_{vCs}8;yEK-(|NW}S-:vkug');
define('NONCE_SALT',       'u>&,;@G8dVs-UIh,9+`PO[E#;2[gtEID[eX- +1QezgrFxLfOwM{BF<Ps}a!hHPu');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'nure_';
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
//define('WP_DEBUG', true);
//define('WP_DEBUG_DISPLAY', true);
//define('WP_DEBUG_LOG', true);

define('WP_DEBUG', false);
@ini_set('display_errors', 0);
error_reporting(22527);

define('WP_POST_REVISIONS', false);
define('WP_MAX_MEMORY_LIMIT', '8092M');
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';