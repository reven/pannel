<?php
/*Este archivo recibe los cambios y se ocuparía de guardar a base de datos*/

$debug.=("post.php está incluido");
$c = connect();
mysql_set_charset('utf8',$c);
//Checkeamos vars. Usamos las seguras para meter a BdD, pero las inseguras para pantalla, para evitar barras (\).
$text = mysql_real_escape_string($_POST['text']);
$title = mysql_real_escape_string($_POST['title']);
$state = mysql_real_escape_string($_POST['state']);
if ($_POST['post_id']) {$post_id = mysql_real_escape_string($_POST['post_id']);}


//cambiar el *texto* de una entrada
if ($_POST['editorId']=="text") {
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['imp']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";	

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
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['importante']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";	

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){
		echo ($_POST['title']);
	}else{
		echo ("<p class=\"error\">Lo siento, el texto no se ha guardado</p>");
	}
	

/*formulario página nueva*/
}elseif ($_POST['check']=="newpost"){
	/*formulario página nueva*/
	$query ="SELECT MAX( post_id ) FROM `posts`";
	$result = query($query,$c);
	$out = fetch_array($result);
	$post_id = $out[0] + 1;
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `text`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['importante']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";
	
	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){ //Exito
		header("Location: http://www.nuuve.com$root$_POST[title]");
		exit;
	}else{
		echo ("<p class\"error\">Lo siento, el texto no se ha guardado</p>");
	}

/*Borrado de una entrada. Cómo ser más seguro? */
}elseif ($_POST['function']=="borrar"){
	$query ="DELETE FROM `posts` WHERE `post_id` =  '$_POST[post_id]'";
	$result = query($query,$c);
	if (!$result) {
	    die('Could not query:' . mysql_error());
	}elseif ($result){ //Exito
		$_SESSION['title_del']=$_POST['title'];
		header("Location: http://www.nuuve.com$root");
		exit;
	}
}
/*para la alteración de estado:

UPDATE `panneldb`.`posts` SET `prioridad` =  '1',
`state` = 'F' WHERE `posts`.`id` =10 LIMIT 1 ;

*/


close($c);	

/*debug* /
echo ("<pre>");
print_r ($_POST); 
echo ("</pre>");
/**/

exit;
?>