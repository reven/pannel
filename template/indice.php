<?php 
//Nuuve Pannel
// Esta página es indice.php, alias "index"

/*El formulario de búsqueda tendría 2 opciones: buscar por título o buscar en el contenido. Usaríamos consultas de búsqueda de mysql. La idea es que debajo haya un div vacío y que se introduzcan los resultados en ese div por una consulta Ajax. Una vez populado, se hace que el div aparezca con un efecto de scriptaculous. Las consultas se envían a post.php*/

/*Además sería guapo poder ordenarlas por Autor, por número de revisión o por fecha.
Obtener el numero de revisiones seguro que se puede hacer con querys anidadas, pero no sé cómo. Hago otra query en el bucle.*/

// Aqui debería ir lógica.

?>
	<h2>Índice</h2>
	<div id="busca">
		<form id="searchform" class="form" method="POST" action="/">
			<input type="hidden" name="check" value="busqueda" />
			<p style="display:inline;">
			<input type="text" name="s" id="s" class="editor_field" value="" size="20"><input type="submit" id="submit" value="Ir" class="editor_ok_button"><input type="checkbox" name="wholesearch" id="wholesearch" value="1"><span class="meta">Buscar también en el contenido de las entradas</span></p>
	</form>
	</div>
	
	<?php
	// Si hay terms[1] han pasado parámetros -> búscalos.
	
	
	
	// De lo contrario es que nos han llamdo a pelo, poner ultimas entradas por orden cronológico
	
	
	?>
	
	<div id="resultados">
		<div id="resultadosactuales">
			<p>Estas son todas las entradas por orden alfabético</p>
			<table><tbody>
				<tr><th>Título</th><th>último autor</th><th>última revisión</th><th>revisiones</th><th>prioridad</th></tr>
<?php
/* Lista de todas las entradas */
$c = connect();
mysql_set_charset('utf8',$c);
// Esta query no tiene límite, pero igual se le puede añadir un límite de 50 y un enlace a buscar las siguientes 50, y que se actualice de forma dinámica
$query ="SELECT * FROM posts INNER JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) ids ON posts.id = ids.id ORDER BY title ASC";
$result = query($query,$c);
while ($out = fetch_array($result)){
echo "\t\t\t\t\t<tr><td><a href=\"$root".$out['title']."/\">".$out['title']."</a></td><td>".$out['author'];
echo "</td><td><span class=\"meta\">".date("j M Y, G:i",strtotime ($out['date']));
echo "</span></td><td class=\"c\">";
$query = "SELECT COUNT(*) AS NumberOf FROM posts WHERE post_id=$out[post_id]";
$result2 = query($query,$c);
$out2 = fetch_array($result2);
echo ("<a href=\"$root$out[title]/versions/\">".$out2[0]."</a>");
echo ("</td><td class=\"c\">");
if ($out['prioridad']==1) {echo ("<span style=\"color:#f00;\">✔</span>");}else{echo ("<span class=\"meta\">--</span>");}
echo ("</td></tr>\n");
}
close($c);

?>
			</tbody></table>
		</div>
	</div>
	<script src="<?php echo $root; ?>js/search.js" type="text/javascript"></script>
