<?php

$state=check_state($_GET['state']);

echo "<h2>State es: $state __";



function check_state($state) {
  return (preg_match ("/^[P|E|X|F|C|H]{1}$/", $state) ? $state : "");
}

 ?>
