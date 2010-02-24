<?php
/*Este archivo recibe los cambios y se ocuparía de guardar a base de datos*/

$debug.=("post.php está incluido");
$c = connect();
mysql_set_charset('utf8',$c);
//Checkeamos vars. Usamos las seguras para meter a BdD, pero las inseguras para pantalla, para evitar barras (\).
$text = mysql_real_escape_string($_POST['text']);
$title = mysql_real_escape_string($_POST['title']);
if ($_POST['post_id']) {$post_id = mysql_real_escape_string($_POST['post_id']);}


//cambiar el *texto* de una entrada
if ($_POST['editorId']=="text") {
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $logged_user."', '";
	$query .= $text."', NOW());";

	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){
		echo ($_POST['text']);
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}
	

//cambiar el *título* de una entrada
}elseif ($_POST['editorId']=="posttitle"){
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $logged_user."', '";
	$query .= $text."', NOW());";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){
		echo ($_POST['title']);
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}
	

/*formulario página nueva*/
}elseif ($_POST['check']=="newpost"){
	/*formulario página nueva*/
	$query ="SELECT MAX( post_id ) FROM `posts`";
	$result = query($query,$c);
	$out = fetch_array($result);
	$post_id = $out[0] + 1;
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $logged_user."', '";
	$query .= $text."', NOW());";
	
	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){ //Exito
		header("Location: http://www.nuuve.com$root$title");
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