<?php
/* Este archivo recibe consultas y no escribe en la base de datos.
En un mundo ideal, las consultas estarían cacheadas */

// debug, needs to be called early because of exits
if (DEBUG_VIS == 1) {
	debug_add ("*** get.php called\n");
	if (DEBUG_LVL == 2) {
	  debug_add (print_r ($_GET, TRUE) . "\n");
	}
}

$c = connect();
$c->set_charset('utf8');


/* 1. Called from Search */
if (isset($_GET['search']) && !$_GET['search'] == ""){
	$search = $_GET['search'];
	echo ("<p>Resultados de búsqueda: <b>$_GET[search]</b>:</p>");

	// Modificar query segun se quieran o no todas las revisiones
	if (isset($_GET['allrevisions'])){
		$query = "SELECT * FROM posts WHERE MATCH (title, content) AGAINST ('$search' WITH QUERY EXPANSION)";
	}else{
		$query = "SELECT * FROM (SELECT * FROM posts JOIN (SELECT MAX( id ) AS id FROM posts GROUP BY post_id ) AS maxids USING (id)) AS uniqueposts WHERE MATCH (title, content) AGAINST ('$search' WITH QUERY EXPANSION);";
	}

	if (!$result = query($query,$c)) {
		echo "<p class=\"error\">Se ha producido un error al hacer la búsqueda: " . mysqli_error($c);
		exit;
	}elseif (mysqli_num_rows($result)==0){
		echo ("<p class=\"error\">Lo siento, pero no se han encontrado páginas cuyo título contenga \"<b>$_GET[search]\"</b>.</p>");
		exit;
	}
	echo "\n\t\t\t<table><tbody>\n\t\t\t\t<tr><th>Título</th><th>último autor</th><th>última revisión</th><th>revisiones</th><th>prioridad</th></tr>\n";
	while ($out = fetch_array($result)){
		echo "\t\t\t\t<tr><td><a href=\"".ROOT.$out['title']."/\">".$out['title']."</a></td><td>".$out['author'];
		echo "</td><td><span class=\"meta\">".date("j M Y, G:i",strtotime ($out['date']));
		echo "</span></td><td>";
		$query = "SELECT COUNT(*) AS NumberOf FROM posts WHERE post_id=$out[post_id]";
		$result2 = query($query,$c);
		$out2 = fetch_array($result2);
		$revs = current($out2);

		echo ("<a href=\"".ROOT."$out[title]/versions/\">".$revs."</a>");
		echo ("</td><td class=\"c\">");
		if ($out['prioridad']==1) {echo ("<span style=\"color:#f00;\">✔</span>");}else{echo ("<span class=\"meta\">--</span>");}
		echo ("</td></tr>\n");
	}
	echo "\t\t\t</tbody></table>\n";
	exit;

	/* 2. Called from a markdown text box */
}elseif (isset($_GET['markdown']) && $_GET['markdown'] == 1){
	$query = "SELECT `content` FROM `posts` WHERE `id`=$_GET[id]";
  $result = query($query,$c);
	$out = fetch_array($result);

	echo ($out['content']);
	exit;
}

close($c);

/*
:)
*/
?>
