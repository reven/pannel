<?php
/*
 Nuuve
Pannel

0.6 beta
Reven
25-Nov-2017

/*SETUP*/
/*Initialize constants*/
define ('ROOT', "/pannel/");    // user definable, relative to server root. Deberíamos cargar esto de la localización del script.
define ('DEBUG_VIS', TRUE);     // TRUE/FALSE Cambiar a TRUE para obtener debug info
define ('DEBUG_LVL', "2");			// 1 = Normal; 2 = Mostrar variables
if (DEBUG_VIS == 1) {
	$debug="";
	debug_init();
}

/* kickstart loop */
include_once ("engine/controller.php");
//include_once ("engine/auth.php");
include_once ("engine/textinterpreter.php");


/*Flujo */
if ($_POST) {
	require ("engine/post.php");
}elseif ($_GET) {
	require ("engine/get.php");
}

$uri = preg_replace("/\/pannel\/(.*)/i","$1",$_SERVER['REQUEST_URI']); // testar esta uri por si hay inyección de código? ################ No sé por qué hice esto. Y no es portable a otros directorios. No usa la config de ruta (ROOT)!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$terms = explode ("/",$uri);
$page_link = ROOT.$terms[0]."/"; //Usar esta preferentemente en lugar de $uri.

// Recogida de info de debug
if (DEBUG_VIS == 1) { // No se si las declaracions de abajo funcionaran.
	debug_add ("uri: $uri \n");
	debug_add ("Enlace a página: $page_link\n");
	debug_add ("matriz \$terms de url: " .print_r ($terms, TRUE));
	debug_add ("SESION: ".session_id());
	debug_add ("\nNombre: " . $_SESSION['nombre']."\n");
	debug_add ("matriz de session: ".print_r ($_SESSION, TRUE)."\n");
	debug_add ("REFERER: ".$_SERVER['HTTP_REFERER']."\n");
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
	global $terms;
	include ("template/header.php");
	do_content($page);
	include ("template/footer.php");
}

/*
do_content()
Determina qué plantilla usar.
*/
function do_content($page){ //Esta función que elije las plantillas es mejorable...
	global $page_link, $terms;
	if ($page==""){
		include ("template/default.php");
	}elseif ($page=="index"){
		include ("template/indice.php");
	}elseif ($page=="nueva"){
		include ("template/new.php");
	}elseif ($page=="help"){
		include ("template/help.php");
	}else{
		include ("template/page.php");
	}
}


/*
debug_init()
Inicializa la salida de debug
*/
function debug_init(){
	global $debug;
	$debug="<div onclick=\"\$(this).switchOff()\" id=\"debug\" class=\"debug\"><p class=\"debug_title\"><b>⍙ haz click para esconder</b></p><pre>DEBUG:\n";
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


/*      /\_/\
       ( o o )
         >·<
*/
?>
