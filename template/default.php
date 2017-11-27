<?php
/*
 Nuuve
Pannel

Default page template. Shows last posts.

/* debug */
if (DEBUG_VIS == 1) {
	debug_add ("Plantilla llamada: default.php\n");
}

if (isset($_SESSION['title_del'])) {
	echo ("<p id=\"yay\" class=\"success\">La página <strong>$_SESSION[title_del]</strong> y todas sus revisiones han sido borradas</p><script>Effect.Fade('yay', { duration: 4.0 });</script>");
	unset($_SESSION['title_del']);
}
?>
	<div class="columnl">
	<h2>Entradas con actividad reciente</h2>
	<ul>

<?php
// Por defecto queremos mostrar las ultimas entradas. Seleccionar solo la version mas actual de cada una de las páginas únicas disponibles.
$c = connect();
$c->set_charset('utf8');

$query ="SELECT * FROM posts JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) AS maxids USING (id) ORDER BY date DESC LIMIT 5";

$result = query($query,$c);
while ($out = fetch_array($result)){
	echo "\t<li><a href=\"".ROOT.$out['title']."/\">".$out['title']."</a> por ".$out['author'];
	echo " <span class=\"meta\">".date("j \d\\e M \d\\e Y, \a \l\a\s G:i",strtotime ($out['date']));
	echo "</span></li>\n";
}
?>
	</ul>
	<p class="meta">No encuentras lo que buscas? Prueba el <a href="<?php echo ROOT; ?>index/">índice</a></p>
	<h2>Importante</h2>

<?php
// Aqui mostramos las entradas que estan marcadas como importantes
$query ="SELECT * FROM posts JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) AS maxids USING (id) WHERE prioridad = 1 ORDER BY date DESC LIMIT 5";

$result = query($query,$c);
if (mysqli_num_rows($result)==0){
	echo ("\t<p>No existen páginas marcadas como prioritarias ahora mismo.</p>\n");
}else{
	echo "\t<ul>\n";
	while ($out = fetch_array($result)){
		echo "\t<li><a href=\"".ROOT.$out['title']."/\">".$out['title']."</a> por ".$out['author'];
		echo " <span class=\"meta\">".date("j \d\\e M \d\\e Y, \a \l\a\s G:i",strtotime ($out['date']));
		echo "</span></li>\n";
	}
	echo "\t</ul>\n";
}
echo "\t</div>\n\t<div class=\"column\">\n";
echo "\t<h2>Entradas esperando <em>Feedback</em></h2>\n";
// Aqui mostramos las páginas marcadas como esperando feedback
$query ="SELECT * FROM posts JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) AS maxids USING (id) WHERE state = 'F' ORDER BY date DESC LIMIT 5";
$result = query($query,$c);
if (mysqli_num_rows($result)==0){
	echo ("\t<p>No existen páginas pendientes de feedback ahora mismo.</p>\n");
}else{
	echo "\t<ul>\n";
	while ($out = fetch_array($result)){
		echo "\t<li><a href=\"".ROOT.$out['title']."/\">".$out['title']."</a> por ".$out['author'];
		echo " <span class=\"meta\">".date("j \d\\e M \d\\e Y, \a \l\a\s G:i",strtotime ($out['date']));
		echo "</span></li>\n";
	}
	echo "\t</ul>\n";
}
close($c);

?>
</div>
<p style="clear:both;"></p>
