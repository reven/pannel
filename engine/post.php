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

/*Cambio de estado */
}elseif ($_POST['editorId']=="estado"){//Verificar valor de $_POST[value] y $_POST[id] antes de introducirlos?
	$status = array ('P'=>"Planteada",'E'=>"En curso",'X'=>"Estancada",'F'=>"Esperando feedback",'C'=>"Cancelada",'H'=>"Hibernando");
	$stat_flag = $_POST['value'];
	if ($stat_flag!=""){ $state="Marcada como <span class=\"$stat_flag\">$status[$stat_flag]</span> | ";}else{$state="Marcar estado | ";}
	$query="UPDATE `posts` SET `author` = '$_SESSION[nombre]', `state` = '$_POST[value]', `date`= NOW() WHERE `id` = $_POST[id]";
	
	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){ //Exito
		echo $state;
	}else{
		echo ("<p class=\"error\">Lo siento, el texto no se ha guardado</p>");
	}



/* Cambio de relevancia */
}elseif ($_POST['editorId']=="prioridad"){//Verificar valor de $_POST[value] y $_POST[id] antes de introducirlos?
	if ($_POST['value']==1) {$impor="<span style=\"color:#f00;\">✔</span> <b>Importante</b> | ";}elseif($_POST['value']==0){$impor="Marcar prioridad | ";}
	$query="UPDATE `posts` SET `author` = '$_SESSION[nombre]', `prioridad` =  '$_POST[value]', `date` = NOW() WHERE `id` = $_POST[id]";
	
	$result = query($query,$c);
	if (!$result) {
	    /*die*/ echo('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){ //Exito
		echo $impor;
	}else{
		echo ("<p class=\"error\">Lo siento, el texto no se ha guardado</p>");
	}

}

close($c);	

/*debug*/
echo ("<pre>");
echo $query;
print_r ($_POST); 
echo ("</pre>");
/**/

exit;
?>