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
define( 'DB_NAME', 'legacysalon_local' );

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
define( 'AUTH_KEY',         '85rBW>23d~; ,AT=9G?q:&dX{&BRW9v(Ba-fFHFfNC$UZ-w{=YXJ:&Gbe2#<_c{s' );
define( 'SECURE_AUTH_KEY',  't|u1~U$J$E6+PfB4%rolaD<[7Ffg+9<E[ 6$hCd5QVH(Bxf.<3`oT0B|h/E5!p5z' );
define( 'LOGGED_IN_KEY',    '?$$iH!iN8{CRo2~c.)@_C;19i=3(0^[]UBibDKP2Q4pN. I?MxVX;0)txX%s<oi>' );
define( 'NONCE_KEY',        'ex{gT)j9kLl)%HlH{1EvtAK]|2Arvp%[WflVQ(4l;cb}@8ls$ET4*%Od1BJrEM.|' );
define( 'AUTH_SALT',        'dZ6/:V;3qyTJ5)O9q*^)WcAaKjZC|JU]4y.E g;mIm l:nVkAe]my~IS=m# Z&K7' );
define( 'SECURE_AUTH_SALT', ' Q0f tUxgS|XPpyA ]lLi)2{rQ=&N=zfJKCLkj^pTWBvEC:#QA]OS+%>x#<.-YET' );
define( 'LOGGED_IN_SALT',   '7V$F[[x!F[n+Yfrc>p2-FF&$@{%%!l,WW,TUfDp@l_Smf4-7grfNh=ci/jed$Vz+' );
define( 'NONCE_SALT',       '>{I>J7yn>V1RLm$UtyCa](aP+}te{GHq*)k A]O+</I]%Z[>0ENH7vae/m,GAx*>' );

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
