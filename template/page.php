<?php
// Plantilla de entrada

if (DEBUG_VIS == 1) {
	debug_add ("\n***Plantilla llamada: page.php\n");
}

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
if ($out['priority']==1) {
	$impor = "Importante";
	$impor_class = " prio1";
}else{
	$impor = "Marcar prioridad";
	$impor_class = "";
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

// Si "delete" es el segundo $term, mostrar formulario de borrado
if (isset($terms[1]) && $terms[1]=="delete") {
?>
<div class="error">
	<p>Seguro que quieres borrar esta entrada? Se borrarán <strong>todas</strong> las revisiones!</p>
	<form id="borrar" class="form" method="POST" action="<?= ROOT ?>">
		<input type="hidden" name="function" id="function" value="borrar" />
		<input type="hidden" name="post_id" id="post_id" value="<?= $post_id ?>" />
		<input type="hidden" name="title" id="title" value="<?= $safe_title ?>" />
		<p><input type="submit" value="Borrar" class="cancel button_big" /> <a href="<?= $page_link ?>">Cancelar</a></p>
	</form>
</div>
<?php }

// Si es una página recién creada, mostrar aviso y mensaje
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==ORIGIN . ROOT . "nueva/") { ?>
	<div id="yay" class="success"><p>Página creada.<br>&nbsp;</p></div>
	<script>
		$(document).ready(function(){
    	$("#yay").fadeOut(4000);
    });
  </script>
<?php }

// Mostrar la entrada ?>
<h2 id="posttitle" class="editInPlace" title="Doble-click para editar"><?=$page?></h2>
    <p class="meta">
        <span id="priority" class="editInPlace<?=$impor_class?>" title="Doble-click para editar"><?=$impor?></span> | <span id="state" class="editInPlace" title="Doble-click para editar"><?=$state?></span> | <span id="auth-date">Última modificación por <strong><?=$out['author']?></strong> el <?=date("j \d\\e M \d\\e Y, \a \l\a\s G:i",strtotime ($out['date']))?></span></p>
				<div id="markdown" class="markdown" style="display:none;"><span class="handle">Acerca de formato abreviado</span>
					<div id="toggle_slide" style="display:none;"><p>Utiliza los siguientes atajos para formatear tu texto:<br>
							**negrita** → <strong>negrita</strong><br>
							__cursiva__ → <em>cursiva</em><br>
							<strong>*</strong> Item → Listas<br>
							<strong>1.</strong> Item → Listas ordenadas<br>
							<strong>bq.</strong> Texto indentado<br>
							<strong># Título</strong> → Título<br>
							<strong>## subtítulo</strong> → subtítulo<br>
							<strong>"</strong>enlace<strong>":</strong>http://www.nuuve.com → enlace<br>
							<strong>!</strong>http://www.nuuve.com/logo.gif<strong>!</strong> → imagen<br><br>
							Puedes anidar listas y bloques de texto indentado. <a href="<?= ROOT ?>help/#formato">(+ info)</a>
						</p>
					</div>
				</div>

    <div id="text" class="editInPlace" title="Doble-click para editar"><?=get_html($out['content'])?></div>

		<div class="edit_tools">
			<a href="<?= $page_link ?>versions/">ver revisiones</a> · <a href="<?= $page_link ?>delete">borrar</a>
		</div>

<?php
// Pasar variables y añadir scripts de edición a la página
echo <<<SCRIPTS
<script>
	var pageRoot  = '$root';
	var id        = '$id';
	var postId    = '$post_id';
	var safeText  = '$safe_text';
	var state     = '$out[state]';
	var priority  = '$out[priority]';
	var safeTitle = '$safe_title';
	var author    = '$_SESSION[nombre]';
</script>
<script src="{$root}js/edit.js"></script>
SCRIPTS;

close($c);
?>
