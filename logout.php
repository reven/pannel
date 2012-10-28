<?php
/*Esta es una solución rápida y sencilla que ignora todo el flujo para cerrar sessión.
Pero no da mensaje de éxito, ni la ruta está codificada porque no se cargan opciones.
Hay que arreglarlo o incluirlo en flujo.

*/

session_start();
$_SESSION = array();
session_destroy();
header("Location: http://www.nuuve.com/hq/pannel/");
?>