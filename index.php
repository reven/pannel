<?php
/*  
 Nuuve
Pannel      

0.4.7 beta
Reven
24-Feb-2010

/*  DEBUG  ####IMPORTANTE: Para produccion quitar llamdas a debug */


/*Initialize*/
$root = "/hq/pannel/";
include ("engine/controller.php");
include ("engine/auth.php"); //Pendiente de implementar. De momento usamos este hack:

include ("engine/textinterpreter.php");


/*Flujo */
if ($_POST) {
	include ("engine/post.php");
}elseif ($_GET) {
	include ("engine/get.php");
}

$uri = preg_replace("/\/hq\/pannel\/(.*)/i","$1",$_SERVER['REQUEST_URI']); // testar esta uri por si hay inyección de código? ################
$terms = explode ("/",$uri);
$page_link = $root.$terms[0]."/"; //Usar esta preferentemente en lugar de $uri.

$debug="<div onclick=\"\$(this).switchOff()\" id=\"debug\" class=\"debug\"><p style=\"color:#f00;\"><b>haz click para esconder</b></p><pre>DEBUG:\nuri: $uri \n";
$debug.="Enlace a página: $page_link\n"; 
$debug.=print_r ($terms, TRUE);
$debug.="SESION: ".session_id();
$debug.="\nNombre: $_SESSION[nombre]\n";
$debug.=print_r ($_SESSION, TRUE);
$debug.="REFERER: ".$_SERVER['HTTP_REFERER'];
$debug.="\n</pre>";
/* fin debug */

draw_page($terms[0]);


/*fin flujo */

/*funciones ------------------------------------------ */

/*
draw_page()
Recupera la cabecera, llama a do_content() para el
contenido y recupera el footer.
*/
function draw_page($page){
	global $terms, $root;
	include ("template/header.php");
	do_content($page);
	include ("template/footer.php");
	// debug_out();
}

/*
do_content()
Determina qué plantilla usar.
*/
function do_content($page){ //Esta función que elije las plantillas es mejorable...
	global $debug, $root, $page_link, $terms;
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
debug_out()
Imprime todo lo recogido en la variable $debug
*/
function debug_out(){
	global $debug;
	//debug out
	$debug .= "</div>";
	echo $debug;
}
/*      /\_/\
       ( o o )
         >·<
*/
?>
