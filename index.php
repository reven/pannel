<?php
/*  
 Nuuve
Pannel      

0.4alpha
Reven
18-Feb-2010

0.4 18/2/10 Funciona la búsqueda básica- Implementado parcialmente marcado como "importante";
0.3 17/2/10 Funciona creación de nuevas páginas. Formulario y sistema POST. Índice. Enlaces codificados. Más CSS.
0.2 16/2/10 Funciona el indice de páginas y la edición in situ de páginas. Ajax.
0.1 15/2/10 estructura básica. debug.

BUGs
 '-.__.-'           
 /oo |--.--,--,--.  
 \_.-'._i__i__i_.'  
       """""""""
#1 - Texto no pude contener comilla sencilla ( ' ) al crear o editar entrada. Probablemente porque interfiera en query SQL. Escapar caracteres lo arreglará? Hay una función específica para escapar las querys, pero igual jode las comillas o el resto de cosas. Mirar.
#2 - Estado "importante" se pierde al editar.
#3 - Si no se usa el subdominio www, la búsqueda no funciona.

TO-DO
*Búsqueda. Acabar de implementar búsqueda de texto completo. Indice mysql del campo text.
																----------->0.4.5
*Guardar marca "importante" de unas revisiones a otras. Implementar enlace de edición. Implementar al crear página?
*Sistema auth (incluyendo sessiones/cookies) y regsitro usuarios.
*Importantes (campo en bd, espacio formulario, funcionalidad enlace)
*Borrar (revisar versiones? Seguro? DROP all con post_id)
																----------->1.0 -->Usable?
*Limpiar notas y comments.
*Revisiones (enum y luego posibilidad de diff?)
*Entradas de tipo especial: LISTAS (to do). Clase Sortable de scriptaculous para funcionalidad parecida a la de 37sig. 
*Revisar que título no esté en uso al crear nueva o renombrar. Ofrecer fusionar? Qué pasa al hacer esto? Porque seleccionamos entradas por nombre de la BdD.
*Notificación de cambios?
*RSS ??
*Text-interpreter?? Mmm... Almenos básico puede funcionar.
*Limitar scriptaculous.js para que solo cargue módulos necesarios.
*Limpiar archivos no utilizados en /js/
*/
/*  DEBUG
IMPORTANTE: Para produccion quitar llamdas a debug */

/*Initialize*/
include ("engine/controller.php");
include ("engine/textinterpreter.php");
$root = "/hq/pannel/";
// include ("engine/auth.php"); Pendiente de implementar. De momento usamos este hack:
$logged_user="Reven";

//Flujo
/* Báscicamente el flujo es:

 POST -----------SI------------->manejar POST (los AJAX y los formularios)
  |
  | NO
  |
 Parsear parámetros
   |
   |
   |
Página especial -------SI-----------> Buscar plantilla -------Default------> Página bienvenida
   |
   | NO
   |
Parámetros adicionales -----------NO-------->Busca la página a la bd y mostrarla
   |
   | SI
   |
Parsear adicionales (NO IMPLEMENTADO)


*Parámetros -> basados en url
por ejemplo: pannel/       -> Página bienvenida
			 pannel/intro/ -> Muestra la página 'intro' para edición
			 pannel/index/ -> Indice de todas las entradas
			 pannel/nueva/ -> Crear nueva página
			 pannel/XXXXXX/versions/ -> Revisiones, con opción de borrar algunas. [NO IMPLEMENTADO]
			 pannel/XXXXXX/delete/   -> Borrar [NO IMPLEMENTADO]
			 pannel/XXXXXX/important/ -> Marcar como prioritario [NO IMPLEMENTADO]
			etc.
			
Rutina de detección de uri y parámetros.
*/
if ($_POST) {
	include ("engine/post.php");
}

$uri = preg_replace("/\/hq\/pannel\/(.*)/i","$1",$_SERVER['REQUEST_URI']); // testar esta uri por si hay inyección de código? ################
$terms = explode ("/",$uri);
$page_link = $root.$terms[0]."/"; //Usar esta preferentemente en lugar de $uri.

/* debug */
$debug="<div onclick=\"\$(this).switchOff()\" id=\"debug\" class=\"debug\"><p style=\"color:#f00;\"><b>haz click para esconder</b></p><pre>DEBUG:\nuri: $uri \n";
$debug.="Enlace a página: $page_link\n"; 
$debug.=print_r ($terms, TRUE);
$debug.="REFERER: ".$_SERVER['HTTP_REFERER'];
$debug.="\n</pre>";

/* fin debug */

draw_page($terms[0]);
/*fin flujo */

/*funciones -> documentar mínimamente */
function draw_page($page){
	global $terms, $root;
	include ("template/header.php");
	$specials = array ("", "index", "nueva");
	if (in_array($terms[0], $specials)) {
	    special_content($terms[0]);
	}else{
		do_content($page);
	}

	include ("template/footer.php");
	debug_out();
}

function special_content($page){ //Esta función que elije las plantillas es mejorable...
	global $root;
	if ($page==""){
		include ("template/default.php");
	}elseif ($page=="index"){
		include ("template/indice.php");
	}elseif ($page=="nueva"){
		include ("template/new.php");
	}
}

function do_content($page){
	global $debug, $root, $page_link;
	$c = connect();
	mysql_set_charset('utf8',$c);
	$page = urldecode($page);
	$query ="SELECT * FROM posts WHERE title = \"$page\" ORDER BY date DESC LIMIT 1";
	$debug .= "<b>DEBUG: page es $page<br />QUERY es $query</b>";
	$result = query($query,$c);
	if (mysql_num_rows($result)==0){
		echo ("<p class=\"error\">Lo siento, pero parece que no existe esa página. <a href=\"$root\">volver</a>.</p>");
		return FALSE;
	}
	$out = fetch_array($result);
	
	$post_id = $out['post_id'];
	$safe_text = rawurlencode($out['text']); // necesario para pasar el texto cuando editamos el título.
	$mark="marcar como importante";
	if ($out['prioridad']==1) {$impor="<span style=\"color:#f00;\">✔</span> <b>Importante</b> | "; $mark="quitar marca importante";}
	$debug .= "\nResultados mysql <pre>".print_r ($out, TRUE)."</pre>";
	// por aqui debería estar funcion para parametros adicionales: borrar, versiones y prioritario. Que se carge antes de content
	if ($_SERVER['HTTP_REFERER']=="http://www.nuuve.com{$root}nueva/") {
		echo ("<p id=\"yay\" class=\"success\">Página creada</p><script type=\"text/javascript\">Effect.Fade('yay', { duration: 4.0 });</script>");}
	echo "<p class=\"edit_tools\"><a href=\"".$page_link."important/\">$mark</a> · <a href=\"".$page_link."versions/\">ver revisiones</a> · <a href=\"".$page_link."delete/\">borrar</a> · <span class=\"meta\">enlaces activos pero no implementados</span></p>";
	echo "\t<h2 id=\"posttitle\" class=\"editInPlace\">".$page."</h2>\n\t<p class=\"meta\">";
	echo $impor;
	echo "Última modificación por <b>".$out[author]."</b> el ".date("j \d\e M \d\e Y, \a \l\a\s G:i",strtotime ($out['date']))."</p>\n";
	echo "\t<div id=\"text\" class=\"editInPlace\">".$out['text']."</div>\n";
	//Quizás este HEREDOC se puede sacar a un include. Igual todo el bloque de html.
	echo <<<SCRIPTS
	<script type="text/javascript">
		
		function warning_url(url)
			{
			var thing = '<p class="error">El título ha cambiado. Antes de seguir editando, vaya a la nueva url: <a href="/hq/pannel/'+url.responseText+'/">'+url.responseText+'</a></p>';
			$('posttitle').insert({ after: thing });
			}
	
		new Ajax.InPlaceEditor('posttitle', '/hq/pannel/', { okText:'Guardar',cancelText:'Cancelar', clickToEditText:'Doble-click para editar',
			callback: function(form, value) {return 'post_id=$post_id&text=$safe_text&title=' + encodeURIComponent(value);warning_url(value)},
			onComplete: function(value,element) {warning_url(value);new Effect.Highlight(element, {startcolor: this.options.highlightColor})}}) 

		new Ajax.InPlaceEditor('text', '/hq/pannel/', {rows:10,cols:40,okText:'Guardar',cancelText:'Cancelar',clickToEditText:'Doble-click para editar',
			callback: function(form, value) {return 'post_id=$post_id&title=$page&text='+ encodeURIComponent(value)}})

	</script>
	
SCRIPTS;

	close($c);
}

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
