<?php
require "config.php";

/** Open a connection to a database server */
function connect(){
    global $db;

    $c = new mysqli($db['host'], $db['user'], $db['password'], $db['db']);
    if ($c->connect_errno) {
      echo "Error al conectar con base de datos: " . $c->connect_error;
      die;
    }
    return $c;
}

/** Close a database connection */
function close($c){
    return mysql_close($c);
}

/** Send a query */
function query($query, $c){
	$result = $c->query($query) or trigger_error($c->error."[$query]");
	return ($result);
}

/** Returns and associative array filled with the data from the connection */
function fetch_array($d){
    return $d->fetch_assoc();
}
?>
