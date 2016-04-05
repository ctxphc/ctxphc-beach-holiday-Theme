<?php
//The entry below were created by iThemes Security to disable the file editor
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/var/www/ctxphc.com/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define( 'DISALLOW_FILE_EDIT', true );


/**
* The base configurations of the WordPress.
*
* This file has the following configurations: MySQL settings, Table Prefix,
* Secret Keys, WordPress Language, and ABSPATH. You can find more information
* by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
* wp-config.php} Codex page. You can get the MySQL settings from your web host.
*
* This file is used by the wp-config.php creation script during the
* installation. You don't have to use the web site, you can just copy this file
* to "wp-config.php" and fill in the values.
*
* @package WordPress
*/

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/* define('DB_NAME', 'ctxphc_wp_db'); */   /* test DB */
define('DB_NAME', 'ctxphcco_wp_db');    /* production DB */

/** MySQL database username */
define('DB_USER', 'ctxphcco_admin');

/** MySQL database password */
define('DB_PASSWORD', 'P@rr0theads');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Added to assist in finding an error in my sql queries */
define( 'SAVEQUERIES', true );
/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
define('AUTH_KEY',         'Qid03Z cS>-]-ga$[+axOzMIsUE+/%DhI5xE~9zn+cQ)/xqApU=;XH`]%ta lhU0');
define('SECURE_AUTH_KEY',  'p-=Li&uGt@7)Z$^/,m4?_w{aWVY+J+>ja:cMMczWeBZJ9H_gx1e_4j4nmLfK1?0V');
define('LOGGED_IN_KEY',    ' 9/zqdER3r/Q)?-EaD<q)~XY5?-}xBZ.<Z*+)|Ug{~7@[7kE0a9q2Sov4KYD.da:');
define('NONCE_KEY',        'RXvjHjHl?<eXD_VvLQ_}%P<sg03NuBa~VskaXT:ThobH*vwMe(#bC|1;#QO!Mk+e');
define('AUTH_SALT',        '1S8>~0$j2[^d4Fy~&6HC>t+)_&5>/zIAvO8y 0f~+syllfg0@$lO7p8L(DR0Rbr:');
define('SECURE_AUTH_SALT', '8|uVTR&83quN8c|RioXAS1o4LX*0so}%j,]-A6@/X` Z/hYYU|(x#`Z~Y1<m~*/t');
define('LOGGED_IN_SALT',   'tzMus;T`nlP<V;L+v6J*Y7qvIO<D}&}>#^tz*|c,&9Q-.f[l#|W4b--tj}+XkeF@');
define('NONCE_SALT',       'S|4uN34/y%a>bQy7d=h? J+kH -?a<u(n!!bCU$g$f~tqDS O^-473Fu-Cd!oFo|');

/**#@-*/

/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each a unique
* prefix. Only numbers, letters, and underscores please!
*/
$table_prefix  = 'ctxphc_';

/**
* WordPress Localized Language, defaults to English.
*
* Change this to localize WordPress. A corresponding MO file for the chosen
* language must be installed to wp-content/languages. For example, install
* de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
* language support.
*/
define('WPLANG', '');

/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*/
define('WP_DEBUG', false);

define( 'WP_MAX_MEMORY_LIMIT' , '512M' );

/**
*
*
*
*
*
*/

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
