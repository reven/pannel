<?php
/*Este archivo recibe los cambios y se ocuparía de guardar a base de datos*/

if (DEBUG_VIS == 1) {
  debug_add("***post.php está incluido\n");
}
$c = connect();
$c->set_charset('utf8');

// Checkeamos TODAS vars. Usamos las seguras para meter a BdD, pero las inseguras para pantalla, para evitar barras (\).
if (isset($_POST['usuario'])) $usuario = $c->real_escape_string($_POST['usuario']);
if (isset($_POST['content'])) $text    = $c->real_escape_string($_POST['content']);
if (isset($_POST['title']))   $title   = $c->real_escape_string($_POST['title']);
if (isset($_POST['state']))   $state   = $c->real_escape_string($_POST['state']);
if (isset($_POST['post_id'])) $post_id = $c->real_escape_string($_POST['post_id']);
if (isset($_POST['id']))      $id      = $c->real_escape_string($_POST['id']);
if (isset($_POST['value']))   $value   = $c->real_escape_string($_POST['value']);

/* 1. Cambiar el *contenido* de una entrada */
if ($_POST['editorId']=="text") {
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `content`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['imp']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Error al intentar guardar el texto: ' . mysqli_error($c));
	}elseif ($result){
		echo (get_html($_POST['content']));
    exit;
	}

/* 2. Cambiar el *título* de una entrada */
}elseif ($_POST['editorId']=="posttitle"){
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `content`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['importante']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Error al intentar guardar el título: ' . mysqli_error($c));
	}elseif ($result){
		echo ($_POST['title']);
    exit;
	}

/* 3. Formulario página nueva */
}elseif (isset($_POST['check']) && $_POST['check']=="newpost"){

  /* Averiguar la post_id mas alta para usar la siguente */
	$query ="SELECT MAX( post_id ) FROM `posts`";
	$result = query($query,$c);
  $out = fetch_array($result);
	$post_id = current($out) + 1;

  /* insertar los nuevos datos */
	$query = "INSERT INTO posts (`id`, `post_id`, `title`, `author`, `content`, `date`, `prioridad`, `state`) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $text."', NOW(), '";
	if ($_POST['importante']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Error al intentar guardar la página: ' . mysqli_error($c));
	}elseif ($result){ //Exito
    header("Location: " . ORIGIN . ROOT . $_POST[title]);
		exit;
	}

/* 4. Borrado de una entrada. Cómo ser más seguro? */
}elseif (isset($_POST['function']) && $_POST['function']=="borrar"){
	$query ="DELETE FROM `posts` WHERE `post_id` =  '$_POST[post_id]'";
	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Error al intentar borrar las entradas: ' . mysqli_error($c));
	}elseif ($result){ //Exito
		$_SESSION['title_del']=$_POST['title'];
		header("Location: " . ORIGIN . ROOT);
		exit;
	}

/* 5. Cambio de *estado* */
}elseif ($_POST['editorId']=="estado"){
	$status = array ('P'=>"Planteada",
                   'E'=>"En curso",
                   'X'=>"Estancada",
                   'F'=>"Esperando feedback",
                   'C'=>"Cancelada",
                   'H'=>"Hibernando");
	$stat_flag = $value;
	if ($stat_flag!=""){ $state="Marcada como <span class=\"$stat_flag\">$status[$stat_flag]</span> | ";}else{$state="Marcar estado | ";}
	$query="UPDATE `posts` SET `author` = '$_SESSION[nombre]', `state` = '$value', `date`= NOW() WHERE `id` = $id";

	$result = query($query,$c);
	if (!$result) {
	    die('<p class="error">Error al intentar cambiar el estado: ' . mysqli_error($c)."<br />Intenta volver atrás.</p>");
	}elseif ($result){ //Exito
		echo $state;
    exit;
	}

/* 6. Cambio de *prioridad* */
}elseif ($_POST['editorId']=="prioridad"){
	if ($value==1) {$impor="<span style=\"color:#f00;\">✔</span> <b>Importante</b> | ";}elseif($value==0){$impor="Marcar prioridad | ";}
	$query="UPDATE `posts` SET `author` = '$_SESSION[nombre]', `prioridad` =  '$value', `date` = NOW() WHERE `id` = $id";

	$result = query($query,$c);
	if (!$result) {
	    /*die*/ echo('<p class="error">Could not query:' . mysql_error()."<br />Intenta volver atrás.</p>");
	}elseif ($result){ //Exito
		echo $impor;
    exit;
	}

}

close($c);

/* debug */
if (DEBUG_VIS == 1 && DEBUG_LVL == 2) {
  debug_add("<pre>\n $query " . print_r ($_POST, TRUE) . "\n</pre>\n");
}
/**/

exit;
?>
