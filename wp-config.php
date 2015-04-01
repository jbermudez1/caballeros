<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'caballeros');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'caballeros');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'caballeros123');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'gM+tl|au5HTUt~J.%[:R2/,6cLE+K7+nxgRC|CQ$6ogm}|EyHA}The|f+ ?I[UEq');
define('SECURE_AUTH_KEY', '1/1f67JMd[T3;oY^op@[lcRXjmy|S8DM17d-VW2dG,rFFP]YI7D^-8wHP.OA^8|U');
define('LOGGED_IN_KEY', 'J(_HOQR^lf=45M6}ZBC,w6#Jx0-CnUVA[fI4=Bo2};Q%biR?};.)J?eQ2,gFSSI(');
define('NONCE_KEY', '+=%_Gp/|{5?9WCRC%?.8w1*w|(9=a|cLXVE)z8s&&KD18I]PpVDpu9>+-YC#@i|l');
define('AUTH_SALT', '%NV!8Q0Q06?$s[Mvj1wJBwP|B_{~y3$)n^s=+>yVhNI<zc_V!pRrMEz3*A.t7.Rx');
define('SECURE_AUTH_SALT', '<ZgwS[RK7a|!E5aeM}<N(/I04uZE&P^:Uqg_?P5m7nv*M8+m_8+.=_1QDi:VYZG)');
define('LOGGED_IN_SALT', '%Zs{]K*Dk4^O{#b 6!G,.-zk{6I^Sk1[7%fu-]D$cBl+#V:@(9AHy`Y,sU<f}Z 2');
define('NONCE_SALT', '+C9aBhkt[6k lNPv|?:X.a(>dtc<)C{`Yw$B|%<hQ8+=>1r7BIFEO##~ZhiQ0#(-');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

