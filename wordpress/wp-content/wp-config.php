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

define('DB_NAME', 'qcleadco_wp6');



/** MySQL database username */

define('DB_USER', 'qcleadco_wp6');



/** MySQL database password */

define('DB_PASSWORD', 'S.V5bQX3ZabGAeKXJVo05');



/** MySQL hostname */

define('DB_HOST', 'localhost');



/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8');



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

define('AUTH_KEY',         '8r30atrnD5qAZsnB8GYWcrF0hFqfZfXkGbeBH3AOjZHwRzmai2f3ZqH626KWh2Mi');

define('SECURE_AUTH_KEY',  'KGtdUSaw5pWnaQ2ZBxi8WOImiXWFdaQPUKn5j96UyqFAmOpiHc2dlZVpH6O9pfTj');

define('LOGGED_IN_KEY',    'gFF0alUqLJnQSROuQTBikmEdKCdDXzIJv5Gzd8j51iNOwzu0qGUrZxrare1225AG');

define('NONCE_KEY',        'DELVe44qr7XOR39rKYyybeuQRgkSjUYPrxKA068wFo879svTUkTGM1DVPiY36LFM');

define('AUTH_SALT',        '7l74Uo0ykHty0SQWP0Om9MkQUo4v69HcJFG9JSOMzu6sbRbTKdIU7zi2FeSiU8iY');

define('SECURE_AUTH_SALT', 'OtF63wRWZAZsSQHZ1raxtfXUQe5qDu7FsQ62hkspWdWcfLHelpEA6b0CdgpxLHbF');

define('LOGGED_IN_SALT',   'wosYn3LRT3MzLqJYGZ1oKeksLDjHEhG0OhnPaV8NiSdOWVwKxP7TJ17rQ240ivcw');

define('NONCE_SALT',       'oeCAPlCxoWYtj44UenOVQSsV2sHdxmEIfPutp4ZKb2rfP7p0rQAlE8Bsitio2wUT');



/**

 * Other customizations.

 */

define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');



/**

 * Turn off automatic updates since these are managed upstream.

 */

define('AUTOMATIC_UPDATER_DISABLED', true);





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

@ini_set( 'upload_max_filesize' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set( 'memory_limit', '256M' );
@ini_set( 'max_execution_time', '300' );
@ini_set( 'max_input_time', '300' );

/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');