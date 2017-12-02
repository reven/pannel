<?php
// Pannel
// Esta página es indice.php, alias "index"

/*Además sería guapo poder ordenarlas por Autor, por número de revisión o por fecha.
Obtener el numero de revisiones seguro que se puede hacer con querys anidadas, pero no sé cómo. Hago otra query en el bucle.*/

?>
	<h2>Índice</h2>
	<div id="busca">
		<form id="searchform" class="form" method="get" action="<?php echo ORIGIN . ROOT; ?>index/">
			<p style="display:inline;">
			<input type="text" name="search" id="s" class="editor_field" value="" size="20" /><input type="submit" id="btnsubmit" value="Ir" class="submit button_big" /><input type="checkbox" name="allrevisions" id="wholesearch" value="1" /><span class="meta">Buscar también en las versiones anteriores de las entradas</span></p>
		</form>
	</div>
	<div id="resultados">
		<div id="resultadosactuales"> <?php // este div es solo por si hay otro div de resultados nuevos y queremos hacer una animación, pero quitar si no lo hacemos. ?>
			<p>Estas son todas las entradas por orden de importancia y alfabético</p>
			<table><tbody>
				<tr><th>Título</th><th>último autor</th><th>última revisión</th><th>revisiones</th><th>prioridad</th></tr>
<?php
/* Lista de todas las entradas */
$c = connect();
$c->set_charset('utf8');
// Esta query no tiene límite, pero igual se le puede añadir un límite de 50 y un enlace a buscar las siguientes 50, y que se actualice de forma dinámica. Implica contar total y generar enlaces a las subconsultas.
$query ="SELECT * FROM posts INNER JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) ids ON posts.id = ids.id ORDER BY priority DESC, title";

$result = query($query,$c);

// Mostrar los resultados
while ($out = fetch_array($result)){
echo "\t\t\t\t\t<tr><td><a href=\"".ROOT.$out['title']."/\">".$out['title']."</a></td><td>".$out['author'];
echo "</td><td><span class=\"meta\">".date("j M Y, G:i",strtotime ($out['date']));
echo "</span></td><td class=\"c\">";
$query = "SELECT COUNT(*) AS NumberOf FROM posts WHERE post_id=$out[post_id]";
$result2 = query($query,$c);
$out2 = fetch_array($result2);
$revs = current($out2);
echo ("<a href=\"".ROOT.$out['title']."/versions/\">".$revs."</a>");
echo ("</td><td class=\"c\">");
if ($out['priority']==1) {echo ("<span style=\"color:#f00;\">✔</span>");}else{echo ("<span class=\"meta\">--</span>");}
echo ("</td></tr>\n");
}
close($c);
?>
			</tbody></table>
		</div>
	</div>
	<script src="<?php echo ROOT ?>js/search.js"></script>
