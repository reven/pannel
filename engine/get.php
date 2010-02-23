<?php
/*Este archivo recibe consultas y no escribe en la base de datos.
En un mundo ideal, las consultas estarían cacheadas */

$debug.=("get.php está incluido");
$c = connect();
mysql_set_charset('utf8',$c);


//Formulario de búsqueda.
if ($_GET['completa']==1) {
	echo ("<p>Buscando entradas por: <b>$_GET[search]</b> </p>");
	$query ="SELECT * FROM (SELECT * FROM `posts` WHERE MATCH (`text`,`title`) AGAINST (\"%$_GET[search]%\" IN BOOLEAN MODE) ORDER BY `id` DESC) AS tmp GROUP BY `post_id`";
	$result = query($query,$c);
	if (mysql_num_rows($result)==0){
		echo ("<p class=\"error\">Lo siento, pero no se han encontrado páginas que contengan \"<b>$_GET[search]\"</b>.</p>");
		exit;
	}
	echo "\n\t\t\t<table><tbody>\n\t\t\t\t<tr><th>Título</th><th>último autor</th><th>última revisión</th><th>revisiones</th><th>prioridad</th></tr>\n";
	while ($out = fetch_array($result)){
		echo "\t\t\t\t\t<tr><td><a href=\"$root".$out['title']."/\">".$out['title']."</a></td><td>".$out['author'];
		echo "</td><td><span class=\"meta\">".date("j M Y, G:i",strtotime ($out['date']));
		echo "</span></td><td>";
		$query = "SELECT COUNT(*) AS NumberOf FROM posts WHERE post_id=$out[post_id]";
		$result2 = query($query,$c);
		$out2 = fetch_array($result2);
		echo ("<a href=\"$root$out[title]/versions/\">".$out2[0]."</a>");
		echo ("</td><td class=\"c\">");
		if ($out['prioridad']==1) {echo ("<span style=\"color:#f00;\">✔</span>");}else{echo ("<span class=\"meta\">--</span>");}
		echo ("</td></tr>\n");
	}
}else{
	echo ("<p>Resultados de búsqueda: <b>$_GET[search]</b>:</p>");
	$query ="SELECT * FROM (SELECT * FROM `posts` WHERE `title` LIKE '%$_GET[search]%' ORDER BY `id` DESC) AS tmp GROUP BY `post_id`";
	// Esta búsqueda puede ser ineficiente. Puesto que title también está indexado, puede ser mejor usar MATCH() AGAINST.
	$result = query($query,$c);
	if (mysql_num_rows($result)==0){
		echo ("<p class=\"error\">Lo siento, pero no se han encontrado páginas cuyo título contenga \"<b>$_GET[search]\"</b>.</p>");
		exit;
	}
	echo "\n\t\t\t<table><tbody>\n\t\t\t\t<tr><th>Título</th><th>último autor</th><th>última revisión</th><th>revisiones</th><th>prioridad</th></tr>\n";
	while ($out = fetch_array($result)){
		echo "\t\t\t\t\t<tr><td><a href=\"$root".$out['title']."/\">".$out['title']."</a></td><td>".$out['author'];
		echo "</td><td><span class=\"meta\">".date("j M Y, G:i",strtotime ($out['date']));
		echo "</span></td><td>";
		$query = "SELECT COUNT(*) AS NumberOf FROM posts WHERE post_id=$out[post_id]";
		$result2 = query($query,$c);
		$out2 = fetch_array($result2);
		echo ("<a href=\"$root$out[title]/versions/\">".$out2[0]."</a>");
		echo ("</td><td class=\"c\">");
		if ($out['prioridad']==1) {echo ("<span style=\"color:#f00;\">✔</span>");}else{echo ("<span class=\"meta\">--</span>");}
		echo ("</td></tr>\n");
	}
}
close($c);
/*echo ("<pre>");
print_r ($_GET); 
echo ("</pre>");*/
exit;
?>