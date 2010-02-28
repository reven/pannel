<?php
// Plantilla de entrada

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
//variables seguras (protegen JS de las comillas en título y texto)
$safe_text = rawurlencode($out['text']);
$safe_title = rawurlencode($out['title']);
if ($out['prioridad']==1) {$impor="<span style=\"color:#f00;\">✔</span> <b>Importante</b>";}else{$impor="Marcar prioridad";}
$status = array ('P'=>"Planteada",'E'=>"En curso",'X'=>"Estancada",'F'=>"Esperando feedback",'C'=>"Cancelada",'H'=>"Hibernando");
$stat_flag = $out['state'];
if ($stat_flag!=""){ $state="Marcada como <span class=\"$stat_flag\">$status[$stat_flag]</span>";}else{$state="Marcar estado";}
$debug .= "\nResultados mysql <pre>".print_r ($out, TRUE)."</pre>";
if ($terms[1]=="delete"){
?>
<div class="error">
	<p>Seguro que quieres borrar esta entrada? Se borrarán <strong>todas</strong> las revisiones!</p>
	<form id="borrar" class="form" method="POST" action="/hq/pannel/">
		<input type="hidden" name="function" id="function" value="borrar" />
		<input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
		<input type="hidden" name="title" id="title" value="<?php echo $safe_title; ?>" />
		<p><input type="submit" value="Borrar" class="cancel_button" /><a class="editor_cancel_link" href="<?php echo $page_link; ?>">Cancelar</a></p>
	</form>
</div>
<?php }

if ($_SERVER['HTTP_REFERER']=="http://www.nuuve.com{$root}nueva/") {
	echo ("<p id=\"yay\" class=\"success\">Página creada</p><script type=\"text/javascript\">Effect.Fade('yay', { duration: 4.0 });</script>");}
echo <<<EDITTOOLS
<div class="edit_tools">
	<a href="{$page_link}versions/">ver revisiones<span class="meta"> (no implementado)</span></a> · <a href="{$page_link}delete/">borrar</a>
</div>
<div id="markdown" class="markdown" style="display:none"><a href="#" onclick="Effect.toggle('toggle_slide','slide'); return false;"><span style="color:#000;font-size: 200%;">✎</span>Quieres usar formato abreviado?</a>
	<div id="toggle_slide" style="display:none;">
		<div>Puedes usar los siguientes atajos para introducir texto más rápido:<br />
			*negrita* → <b>negrita</b><br />
			_cursiva_ → <i>cursiva</i><br />
			<b>*</b> Item → Listas<br />
			<b>1.</b> Item → Listas ordenadas<br />
			<b>bq.</b> Texto indentado<br />
			<b>h.</b> Titulo<br />
			<b>"</b>enlace<b>":</b>http://www.nuuve.com → enlace<br />
			<b>!</b>http://www.nuuve.com/logo.gif<b>!</b> → imagen<br /><br />
			Puedes anidar listas y bloques de texto indentado. <a href="{$root}help/#formato">(+ info)</a>
		</div>
	</div>
</div>

EDITTOOLS;
echo "\t<h2 id=\"posttitle\" class=\"editInPlace\">".$page."</h2>\n\t<p class=\"meta\">";
echo ("<span id=\"prioridad\" class=\"editInPlace\">$impor | </span>");
echo ("<span id=\"estado\" class=\"editInPlace\">$state | </span>");
echo "Última modificación por <b>".$out[author]."</b> el ".date("j \d\e M \d\e Y, \a \l\a\s G:i",strtotime ($out['date']))."</p>\n";
echo "\t<div id=\"text\" class=\"editInPlace\">".$out['text']."</div>\n";
//Quizás este HEREDOC se puede sacar a un include. Igual todo el bloque de html.
//Las rutas en este snippet deberían ser relativas o cambaidas mediante la variable $root
echo <<<SCRIPTS
<script type="text/javascript">
/* <![CDATA[ */
	function warning_url(url)
		{
		var thing = '<p class="error">El título ha cambiado. Antes de seguir editando, vaya a la nueva url: <a href="/hq/pannel/'+url.responseText+'/">'+url.responseText+'</a></p>';
		$('posttitle').insert({ after: thing });
		}

	new Ajax.InPlaceEditor('posttitle', '/hq/pannel/', { okText:'Guardar',cancelText:'Cancelar', clickToEditText:'Doble-click para editar',
		callback: function(form, value) {return 'post_id=$post_id&text=$safe_text&state=$out[state]&imp=$out[prioridad]&title=' + encodeURIComponent(value);warning_url(value)},
		onComplete: function(value,element) {warning_url(value);new Effect.Highlight(element, {startcolor: this.options.highlightColor})}}) 

	new Ajax.InPlaceCollectionEditor( 'prioridad', '/hq/pannel/', { okText:'Guardar',cancelText:'Cancelar',
		clickToEditText:'Doble-click para editar', collection: [['1','Importante'], ['0','Normal']], callback: function(form, value) {return 'id=$out[id]&value='+value}});
	
	new Ajax.InPlaceCollectionEditor( 'estado', '/hq/pannel/', { okText:'Guardar',cancelText:'Cancelar',
		clickToEditText:'Doble-click para editar', collection: [['','-- (quitar marca)'], ['P','Planteada'], ['E', 'En curso'], ['X', 'Estancada'], ['F', 'Esperando feedback'], ['C', 'Cancelada'], ['H', 'Hibernando']], callback: function(form, value) {return 'id=$out[id]&value='+value}});

	new Ajax.InPlaceEditor('text', '/hq/pannel/', {rows:10,cols:40,okText:'Guardar',cancelText:'Cancelar',clickToEditText:'Doble-click para editar',
		callback: function(form, value) {return 'post_id=$post_id&state=$out[state]&imp=$out[prioridad]&title=$safe_title&text='+ encodeURIComponent(value)},
		onEnterEditMode: function(form, value) { Effect.SlideDown('markdown');},
		onLeaveEditMode: function(form, value) { Effect.SlideUp('markdown'); }})
/* ]]> */
</script>

SCRIPTS;

close($c);
?>