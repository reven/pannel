<?php
// Plantilla de entrada
if (DEBUG_VIS == 1) {
	debug_add ("\n***Plantilla llamada: page.php\n");
}

// Añadir lógica: si pagina nueva, si vacia, si normal. !!!!!!!! Ver 15.
// Puedo usar esta página para ver revisiones? Partido en horizontal igual??

// Coger variables de constantes para poder incluirlas en variables
$root = ROOT;
$origin = ORIGIN;

// Cargar la página
$c = connect();
$c->set_charset('utf8');
$page = urldecode($page);
$query ="SELECT * FROM posts WHERE title = \"$page\" ORDER BY date DESC LIMIT 1";
$result = query($query,$c);

if (mysqli_num_rows($result)==0){
	echo ("<p class=\"error\">Lo siento, pero parece que no existe esa página. <a href=\"$root\">volver</a>.</p>");
	return FALSE;
}

$out = fetch_array($result);
/* debug */
if (DEBUG_VIS == 1) {
	debug_add ("Page es $page\nQUERY es $query\n");
	debug_add ("Resultados de query: ".print_r($out, TRUE));
}

/* Generamos variables de los resultados de la query.
Las variables seguras protegen JS de las comillas en título y texto) */
$id  				= $out['id'];
$post_id    = $out['post_id'];
$safe_text  = rawurlencode($out['content']);
$safe_title = rawurlencode($out['title']);
if ($out['prioridad']==1) {
	$impor="<span style=\"color:#f00;\">✔</span> <b>Importante</b>";
}else{
	$impor="Marcar prioridad";
}
$status = array ('P'=>"Planteada",
								 'E'=>"En curso",
								 'X'=>"Estancada",
								 'F'=>"Esperando feedback",
								 'C'=>"Cancelada",
								 'H'=>"Hibernando");

$stat_flag = $out['state'];
if ($stat_flag!=""){
	$state="Marcada como <span class=\"$stat_flag\">$status[$stat_flag]</span>";
}else{
	$state="Marcar estado";
}

// Si "delete" es el segundo $term, mostrar aviso de borrado
if (isset($terms[1]) && $terms[1]=="delete") {
?>
<div class="error">
	<p>Seguro que quieres borrar esta entrada? Se borrarán <strong>todas</strong> las revisiones!</p>
	<form id="borrar" class="form" method="POST" action="<?php echo ROOT ?>">
		<input type="hidden" name="function" id="function" value="borrar" />
		<input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
		<input type="hidden" name="title" id="title" value="<?php echo $safe_title; ?>" />
		<p><input type="submit" value="Borrar" class="cancel_button" /><a class="editor_cancel_link" href="<?php echo $page_link; ?>">Cancelar</a></p>
	</form>
</div>
<?php }

// Si es una página recién creada, mostrar aviso y mensaje
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==ORIGIN . ROOT . "nueva/") { ?>
	<p id="yay" class="success">Página creada</p>
	<script>
		$(document).ready(function(){
    	$("#yay").fadeOut(4000);
    });
  </script>")
<?php }

// Mostrar la ayuda para edición
?>
<div class="edit_tools">
	<a href="<?= $page_link ?>versions/">ver revisiones<span class="meta"> (no implementado)</span></a> · <a href="<?= $page_link ?>delete/">borrar</a>
</div>
<div id="markdown" class="markdown" style="display:none"><a href="#" onclick="$(this).slideDown(); return false;"><span style="color:#000;font-size: 200%;">✎</span> Acerca de formato abreviado</a>
	<div id="toggle_slide" style="display:none;">
		<div>Utiliza los siguientes atajos para formatear tu texto:<br />
			*negrita* → <b>negrita</b><br />
			_cursiva_ → <i>cursiva</i><br />
			<b>*</b> Item → Listas<br />
			<b>1.</b> Item → Listas ordenadas<br />
			<b>bq.</b> Texto indentado<br />
			<b>h.</b> Titulo<br />
			<b>"</b>enlace<b>":</b>http://www.nuuve.com → enlace<br />
			<b>!</b>http://www.nuuve.com/logo.gif<b>!</b> → imagen<br /><br />
			Puedes anidar listas y bloques de texto indentado. <a href="<?= ROOT ?>help/#formato">(+ info)</a>
		</div>
	</div>
</div>

<?php
// Mostrar la entrada
echo "\t<h2 id=\"posttitle\" class=\"editInPlace\">".$page."</h2>\n\t<p class=\"meta\">";
echo ("<span id=\"prioridad\" class=\"editInPlace\">$impor | </span>");
echo ("<span id=\"estado\" class=\"editInPlace\">$state | </span>");
echo "Última modificación por <b>".$out['author']."</b> el ".date("j \d\\e M \d\\e Y, \a \l\a\s G:i",strtotime ($out['date']))."</p>\n";
echo "\t<div id=\"text\" class=\"editInPlace\">".get_html($out['content'])."</div>\n";

// Pasar variables y añadir scripts de edición a la página
echo <<<SCRIPTS
<script>
	var pageRoot = '$root';
	var id       = '$id';
	var postId   = '$post_id';
	var safeText = '$safe_text';
	var state    = '$out[state]';
	var prio     = '$out[prioridad]';
	var safeTitle= '$safe_title';
</script>
<script src="{$root}js/edit.js"></script>
SCRIPTS;

close($c);
?>
