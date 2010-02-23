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
}

/*debug* /
echo ("<pre>");
print_r ($_POST); 
echo ("</pre>");
/**/

exit;
?>