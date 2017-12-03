<?php
/*
Database controler. Should provide a limited amount of abstraction,
in case of database change.
*/

/** Open a connection to a database server */
function connect(){
    global $db;

    $c = new mysqli($db['host'], $db['user'], $db['password'], $db['db']);
    if (mysqli_connect_errno()) {
      printf("Error al conectar con base de datos: %s\n",mysqli_connect_error());
      exit();
    }
    return $c;
}

/** Close a database connection */
function close($c){
    return $c->close();
}

/** Send a query */
function query($query, $c){
	$result = $c->query($query) or trigger_error($c->error."[$query]");
	return ($result);
}

/** Returns an associative array filled with the data from the connection */
function fetch_array($d){
    return $d->fetch_assoc();
}
?>
