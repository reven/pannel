<?php
/*Este archivo recibe los cambios y se ocuparía de guardar a base de datos*/

$debug.=("post.php está incluido");
$c = connect();
mysql_set_charset('utf8',$c);
if ($_POST['editorId']=="text") {
	//cambiar el *texto* de una entrada
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $_POST['post_id']."', '";
	$query .= $_POST['title']."', '";
	$query .= $logged_user."', '";
	$query .= $_POST['text']."', NOW());";

	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){
		echo ($_POST['text']);
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}
	
	
}elseif ($_POST['editorId']=="posttitle"){
	//cambiar el *título* de una entrada
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $_POST['post_id']."', '";
	$query .= $_POST['title']."', '";
	$query .= $logged_user."', '";
	$query .= $_POST['text']."', NOW());";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){
		echo ($_POST['title']);
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}
}elseif ($_POST['check']=="newpost"){ //Este check es una tontería, se podría mirar el Submit (pero no se envia). O simplemente default(sin evaluar contenido). O incluso anti-spam...
	/*formulario página nueva
	Recibimos:
	Array
	(
	    [check] => newpost
		[title] => Título ---> MIRAR SI YA ESTÁ EN USO!!!! Dupliación tonta: dos páginas con mismo titulo (dos id's).
									Confuso y en filtros por nombre puede liarse
	    [text] => dsfsdfsdf
	)
	author lo obtenemos de $logged_user o sessiones!!!
	post_id tenemos que obtenerlo de la base de datos. Mirar el más alto.
	date es ahora.
	
	No usar echo() !!! Queremos hacer la introduccion en base de datos y luego redireccionar a la nueva página.
	Para enviar las cabeceras no se tiene que haber enviado nada.
	*/
	// Checkear variables pasadas por POST !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$query ="SELECT MAX( post_id ) FROM `posts`";
	$result = query($query,$c);
	$out = fetch_array($result);
	$post_id = $out[0] + 1;
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $_POST['title']."', '";
	$query .= $logged_user."', '";
	$query .= $_POST['text']."', NOW());";
	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){ //Exito
		header("Location: http://www.nuuve.com$root$_POST[title]");
		exit;
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}
	close($c);	
}elseif ($_POST['check']=="busqueda"){
	//Formulario de búsqueda.
	/* 	1.Procesar el request, query, etc
		2. Si nos han llamado desde inicio, enviar un header para ir a índice. Pasar vars a índice y que se ocupe Ajax? O pasar respuesta?? Mmmm
		3. Si nos han llamado desde índice, podemos enviar datos y ya.
	
	*/
	if ($_POST['completa']==1) {echo ("<p class=\"error\">Búsqueda completa no implementada. Buscando títulos por: <b>$_POST[search]</b> </p>");}else{echo ("<p>Resultados de búsqueda: <b>$_POST[search]</b>:</p>");}
	$query ="SELECT * FROM (SELECT * FROM `posts` WHERE `title` LIKE '%$_POST[search]%' ORDER BY `id` DESC) AS tmp GROUP BY `post_id`";
	$result = query($query,$c);
	if (mysql_num_rows($result)==0){
		echo ("<p class=\"error\">Lo siento, pero no se han encontrado páginas con el término <b>$_POST[search]</b>.</p>");
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
	close($c);
	/*echo ("SIIIIIIIIIII<pre>");
	print_r ($_POST); 
	echo ("</pre>");*/
	
}



/*debug* /
echo ("<pre>");
print_r ($_POST); 
echo ("</pre>");
/**/

exit;
?>