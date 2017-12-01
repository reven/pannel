<?php
/*
Pannel

0.7 beta
Copyright (C) 2017 Robert Sanchez

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

		See LICENSE.txt for the complete license

25-Nov-2017
*/

/*SETUP*/
/*Initialize constants*/
define ('ROOT', preg_replace ("/(.*)index.php/i","$1", $_SERVER['SCRIPT_NAME']));    // Base url relative to server root.
define ('ORIGIN', url_origin( $_SERVER )); // Server info below the ROOT

define ('DEBUG_VIS', FALSE);     // TRUE/FALSE Cambiar a TRUE para obtener debug info
define ('DEBUG_LVL', "2");			// 1 = Normal; 2 = Mostrar variables
if (DEBUG_VIS == 1) {
	$debug =	debug_init();
}

/* kickstart loop */
include_once ("engine/controller.php");
include_once ("engine/auth.php");
include_once ("engine/textinterpreter.php");


/* Flujo */
if ($_POST) {
	require ("engine/post.php");
}elseif ($_GET) {
	require ("engine/get.php");
}

// Obtener la URI y extrapolar los términos de la petición
$uri = explode (ROOT,$_SERVER['REQUEST_URI']);
$terms = explode ("/",$uri[1]);
$page_link = ROOT.$terms[0]."/"; //Usar esta preferentemente en lugar de $uri.

// Recogida de info de debug
if (DEBUG_VIS == 1) { // No se si las declaracions de abajo funcionaran.
	debug_add ("uri: " . print_r ($uri, TRUE) . "\n");
	debug_add ("Enlace a página: $page_link\n");
	debug_add ("matriz \$terms de url: " .print_r ($terms, TRUE));
	debug_add ("SESION: ".session_id());
	debug_add ("\nNombre: " . $_SESSION['nombre']."\n");
	debug_add ("matriz de session: ".print_r ($_SESSION, TRUE)."\n");
	debug_add ("REFERER: ". (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"No referer" )."\n");
/* fin debug */
}

draw_page($terms[0]);


/*fin flujo */

/*funciones ------------------------------------------ */

/*
draw_page()
Recupera la cabecera, llama a do_content() para el
contenido y recupera el footer.
*/
function draw_page($page){
	global $terms, $page_link;
	include ("template/header.php");
	do_content($page);
	include ("template/footer.php");
}

/*
do_content()
Determina qué plantilla usar.
*/
function do_content($page){ //Esta función que elije las plantillas es mejorable...
	global $terms, $page_link;
	if ($page==""){
		include ("template/default.php");
	}elseif ($page=="index"){
		include ("template/indice.php");
	}elseif ($page=="nueva"){
		include ("template/new.php");
	}elseif ($page=="help"){
		include ("template/help.php");
	}elseif ($page=="debug"){
		include ("template/debug.php"); // eliminar mas adelante
	}else{
		include ("template/page.php");
	}
}


/*
debug_init()
Inicializa la salida de debug
*/
function debug_init(){
	return ("<div onclick=\"\$(this).fadeOut('slow')\" id=\"debug\" class=\"debug\"><p class=\"debug_title\"><b>haz click para esconder</b></p><pre>DEBUG:\n");
}


/*
debug_add($str)
Recoge contenido para $debug
*/
function debug_add($add){
	global $debug;
	$debug .= $add;
}


/*
debug_out()
Imprime todo lo recogido en la variable $debug
*/
function debug_out(){
	global $debug;
	//debug out
	$debug .= "\n</pre></div>";
	echo $debug;
}

/*
url_origin()
Devuelve el origen del script, añadiendo protocolo y puerto si necesario.
*/
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


/*      /\_/\
       ( o o )
         >·<
*/
?>
