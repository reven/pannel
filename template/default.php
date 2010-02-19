
	<h2>Pannel</h2>
	<p>Este es el sistema de gestión de nuuve</p>

<?php
	/*Búsqueda*/
	/* No queremos que se envíe el formulario. Al hacer click queremos ir a http://www.nuuve.com/hq/pannel/index/términos de busqueda/
	Habrá que buscar funciones ajax para coger lo que se mete en el campo del formulario y ponerlo al final de la url
	*/
/*

De momento saco el formulario de la portada hasta que resuelva como rutar el programa. Ahora redirecciona a index, para que se haga la búsqueda
allí, lo cual creo que es lo mejor (index es búsqueda y display), pero al pasar por el flujo con el $_POST se lo traga post.php.

Podría pasar la búsqueda como parámetro, p.ej. /hq/pannel/index/términos , como había pensado, pero dado el carácter dinámico de index, la segunda
busqueda se realizaría con los parámetros aun en la url, lo cual hace feo (cuanto menos) y en el peor de los casos puede confundir al usuario.

No se me ocurre cómo solucionar esto, a no ser que modifique el flujo.

<div id="busca">
	<form id="nuevo" class="form" method="POST" action="http://www.nuuve.com<?php echo $root?>index/">
		<input type="hidden" name="check" value="busqueda" />
		<p style="display:inline;">
		<input type="text" name="title" class="editor_field" value="Buscar entradas..." onfocus="this.value=(this.value=='Buscar entradas...') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Buscar entradas...' : this.value;" size="20"><input type="submit" value="Ir" class="editor_ok_button"><input type="checkbox" name="wholesearch" value="1"><span class="meta">Buscar también en el contenido de las entradas NOTA: ESTE FORMULARIO NO HACE NADA</span></p>
</form>
</div>
*/
?>
	<h3>Entradas con actividad reciente</h3>
	<ul>
	
<?php
$c = connect();
mysql_set_charset('utf8',$c);
//ni siquiera sé cómo funciona esta query!!!!!
$query ="SELECT * FROM posts INNER JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) ids ON posts.id = ids.id ORDER BY date DESC LIMIT 5";
$result = query($query,$c);
while ($out = fetch_array($result)){
echo "\t<li><a href=\"$root".$out['title']."/\">".$out['title']."</a> por ".$out['author'];
echo " <span class=\"meta\">".date("j \d\e M \d\e Y, \a \l\a\s G:i",strtotime ($out['date']));
echo "</span></li>\n";
}
?>
	</ul>
	<p class="meta">No encuentras lo que buscas? Prueba el <a href="<?php echo $root; ?>index/">índice</a></p>
	<h3>Importante</h3>
<?php
$query ="SELECT * FROM posts INNER JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) ids ON posts.id = ids.id WHERE prioridad = 1 ORDER BY date DESC LIMIT 5";
$result = query($query,$c);
if (mysql_num_rows($result)==0){
	echo ("\t<p>No existen páginas marcadas como prioritarias ahora mismo.</p>\n");
}else{
	echo "\t<ul>\n";
	while ($out = fetch_array($result)){
		echo "\t<li><a href=\"$root".$out['title']."/\">".$out['title']."</a> por ".$out['author'];
		echo " <span class=\"meta\">".date("j \d\e M \d\e Y, \a \l\a\s G:i",strtotime ($out['date']));
		echo "</span></li>\n";
	}
	echo "\t</ul>\n";
}

close($c);

?>