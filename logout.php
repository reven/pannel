<?php
/*Esta es una solución rápida y sencilla que ignora todo el flujo para cerrar sessión.
Pero no da mensaje de éxito, ni la ruta está codificada porque no se cargan opciones.
Hay que arreglarlo o incluirlo en flujo.

*/

// Parse URL to get origin and root
define ('ORIGIN', url_origin( $_SERVER ));
define ('ROOT', preg_replace ("#(.*)/logout\.php(.*)#i","$1", $_SERVER[REQUEST_URI] ) . "/");

session_start();
$_SESSION = array();
session_destroy();
header("Location: " . ORIGIN . ROOT);

// obtiene origen cualificado para una redirección
function url_origin($s, $use_forwarded_host=FALSE){
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
?>
