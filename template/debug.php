<?php
// Plantilla de debug
if (DEBUG_VIS == 1) {
	debug_add ("\n***Plantilla llamada: debug.php\n");
}

// Coger variables de constantes para poder incluirlas en variables
$root = ROOT;
$origin = ORIGIN;

$c = connect();
$c->set_charset('utf8');

echo "<h2>Debug playground</h2>";
echo "<pre>";
$query ="SELECT MAX( post_id ) FROM posts"; // tiene que haber una forma mejor de obtener la ID mas alta????
$result = query($query,$c);
$out = fetch_array($result);
//$out2 = $result->fetch_array(MYSQLI_NUM);
$value = current($out);
//$post_id = $out[0] + 1;

echo "Value: $value \n";

print_r ($out);

//print_r ($out2);

echo "</pre>";
close($c);
?>
