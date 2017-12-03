<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pannel</title>

	<script src="<?= ROOT ?>js/jquery.js"></script>

	<link rel="stylesheet" media="screen,print" href="<?= ROOT ?>css/pannel.css" />
<?php
if (DEBUG_VIS == 1){
	echo '<link rel="stylesheet" media="screen,print" href="'.ROOT.'css/debug.css" />';
} ?>

</head>
<body>
	<div id="header">
		<?php if (isset($_SESSION['nombre'])) {echo "<div class=\"loggedin meta\"><p>$_SESSION[nombre] (<a href=\"".ROOT."logout.php\">cerrar sessión</a>)</p></div>\n"; }?>
		<div class="dashboard meta"><p><a href="<?= ORIGIN ?>">↺ volver al Dashboard</a></p></div>
		<div id="nav">

<?php

include ("menu.inc");
?>

		</div>
		<h1>Pannel (beta)</h1>
	</div>
	<div id="content">
