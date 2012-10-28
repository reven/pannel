<?php
require "config.php";

/** Open a connection to a database server */
function connect(){
    global $db;

    $c = mysql_connect($db['host'], $db['user'], $db['password']) or die ("Error connecting to database."); ;
    mysql_select_db($db['db'], $c) or die ("Couldn't select the database."); 
    return $c;
}

/** Close a database connection */
function close($c){
    return mysql_close($c);
}

/** Send a query */
function query($query, $c){
	$result = mysql_query($query, $c);
	return ($result);
}

/** Returns and associative array filled with the data from the connection */
function fetch_array($d){
    return mysql_fetch_array($d);
}
?>
