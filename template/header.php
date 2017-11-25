<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Nuuve Pannel</title>

	<script src="<?php echo ROOT ?>js/prototype.js" type="text/javascript"></script>
	<script src="<?php echo ROOT ?>js/scriptaculous.js" type="text/javascript"></script>
	<script src="<?php echo ROOT ?>js/extend.js" type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo ROOT ?>css/pannel.css" />
<?php
if (DEBUG_VIS == 1){
	echo '<link rel="stylesheet" type="text/css" media="screen,print" href="'.ROOT.'css/debug.css" />';
} ?>

</head>
<body>
	<div id="header">
		<?php if (isset($_SESSION['nombre'])) {echo "<div class=\"loggedin meta\"><p>$_SESSION[nombre] (<a href=\"".ROOT."logout.php/\">cerrar sessión</a>)</p></div>"; }?>
			<div class="dashboard meta"><p><a href="/">↺ volver al Dashboard</a></p></div>
		<div id="nav">

<?php

include ("menu.inc");
?>

		</div>
		<h1>Pannel (beta)</h1>
	</div>
	<div id="content">
