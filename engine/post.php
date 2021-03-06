<?php
/* post.php
Recibe cambios y maneja la lógica para ver qué cambios a la base de datos se requeiren.

Modificar el mínimo número de campos que se requeiran!
*/

if (DEBUG_VIS == 1) {
  debug_add("***post.php está incluido\n");
}
$c = connect();
$c->set_charset('utf8');

// Escapamos las vars para uso en querys, aunque deberíamos chequear que sean seguras
if (isset($_POST['content']))  $content  = $c->real_escape_string($_POST['content']);
if (isset($_POST['title']))    $title    = $c->real_escape_string($_POST['title']);
if (isset($_POST['state']))    $state    = $c->real_escape_string($_POST['state']);
if (isset($_POST['post_id']))  $post_id  = $c->real_escape_string($_POST['post_id']);
if (isset($_POST['id']))       $id       = $c->real_escape_string($_POST['id']);

/* 1. Cambiar el *contenido* de una entrada */
if (isset($_POST['editorId']) && $_POST['editorId'] == "text") {
  $state = check_state($state);
	$query = "INSERT INTO posts (id, post_id, title, author, content, date, priority, state) VALUES (NULL, '"; // No se si este null funciona???
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $content."', NOW(), '";
	if ($_POST['priority']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";

	$result = query($query,$c);
	if (!$result) {
    exit('Error al intentar guardar la entrada: ' . mysqli_error($c));
	}elseif ($result){
    exit('pannel: success;newid: ' . mysqli_insert_id($c));
	}

/* 2. Cambiar el *título* de una entrada */
}elseif (isset($_POST['editorId']) && $_POST['editorId'] == "posttitle") {
  $query="UPDATE posts SET author = '$_SESSION[nombre]', title = '$title', date= NOW() WHERE id = $id";

	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar guardar el título: ' . mysqli_error($c));
	}elseif ($result){
    exit('pannel: success');
	}

/* 3. Formulario página nueva */
}elseif (isset($_POST['check']) && $_POST['check']=="newpost"){

  /* Averiguar la post_id mas alta para usar la siguente */
	$query ="SELECT MAX( post_id ) FROM posts";
	$result = query($query,$c);
  $out = fetch_array($result);
	$post_id = current($out) + 1;

  /* insertar los nuevos datos */
  $state = check_state($state);
	$query = "INSERT INTO posts (id, post_id, title, author, content, date, priority, state) VALUES (NULL, '";
	$query .= $post_id."', '";
	$query .= $title."', '";
	$query .= $_SESSION['nombre']."', '";
	$query .= $content."', NOW(), '";
	if ($_POST['priority']==1){$query .= "1', '";}else{$query .= "0', '";}
	$query .= $state."');";

	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar guardar la página: ' . mysqli_error($c));
	}elseif ($result){ //Exito
    header("Location: " . ORIGIN . ROOT . $_POST[title]);
		exit;
	}

/* 4. Borrado de una entrada. */
}elseif (isset($_POST['function']) && $_POST['function'] == "borrar"){
	$query ="DELETE FROM posts WHERE post_id =  '$post_id'";
	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar borrar las entradas: ' . mysqli_error($c));
	}elseif ($result){ //Exito
		$_SESSION['title_del'] = $title;
		header("Location: " . ORIGIN . ROOT);
		exit;
	}

/* 5. Borrado de una sola revisión */
}elseif (isset($_POST['function']) && $_POST['function'] == "del_rev"){
	$query ="DELETE FROM posts WHERE id =  '$id'";
	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar borrar la revisión: ' . mysqli_error($c));
	}elseif ($result){ //Exito
		exit('pannel: success');
	}

/* 6. Cambio de *estado* */
}elseif (isset($_POST['editorId']) && $_POST['editorId'] == "state") {
  $state = check_state($state);

	$query="UPDATE posts SET author = '$_SESSION[nombre]', state = '$state', date= NOW() WHERE id = $id";

	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar cambiar el estado: ' . mysqli_error($c));
	}elseif ($result){ //Exito
    exit('pannel: success');
	}

/* 7. Cambio de *prioridad* */
}elseif (isset($_POST['editorId']) && $_POST['editorId'] == "priority") {
	if ($_POST['priority']==1) { $priority = 1; }else{ $priority = 0; }

	$query="UPDATE posts SET author = '$_SESSION[nombre]', priority =  '$priority', date = NOW() WHERE id = $id";

	$result = query($query,$c);
	if (!$result) {
	  exit('Error al intentar cambiar la prioridad: ' . mysqli_error($c));
	}elseif ($result){ //Exito
    exit('pannel: success');
	}
}

close($c);

/*
check_state (var)
Cleans the var or anything that isn't a valid state marker
*/
function check_state($state) {
  return (preg_match ("/^[P|E|X|F|C|H]{1}$/", $state) ? $state : "");
}

/* debug */
if (DEBUG_VIS == 1 && DEBUG_LVL == 2) {
  debug_add("<pre>\n $query " . print_r ($_POST, TRUE) . "\n</pre>\n");
}
/**/

exit;
?>
