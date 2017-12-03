<?php
/*Módulo de autentificación.

Inicia sesion si es nula, muestra formulario login y comprueba credenciales

TO DO: - Logout aqui? para tener session controlada en el mismo sitio?
       - Mejorar flujo un poco.
*/

session_start();
if(isset($_SESSION['nombre'])){
	// Usuario está registrado
	// No hacemos nada y devolvemos control a index.php


}elseif (!isset($_SESSION['nombre']) && isset($_POST['usuario']) && isset($_POST['pass'])){
	// No está la sesión y hemos recibido el formulario via POST
	// Comprobar las credenciales recibidas con la base de datos.
	$c = connect();
	$c->set_charset('utf8');

	// Limpiar $usuario y $pass de formulario
	$usuario = $c->real_escape_string($_POST['usuario']);
	$pass = $c->real_escape_string($_POST['pass']);

	// Get the hash
	$query="SELECT * FROM users WHERE login='$usuario'";
	$result = query($query,$c);
  $out = fetch_array($result);
	$hash = $out['pass'];

  //debug
	if (DEBUG_VIS == 1) { // No se si las declaracions de abajo funcionaran.
		debug_add ("***auth.php\nComprobando nombre en db\nUsuario: $usuario\nPass:  $pass\nHASH:  $hash\n"); // no se si esto llega a una llamada para que se vea.
	}
	//fin debug

	// Test the hash received
	if (password_verify($pass, $hash)) {
		// Exito. registrar nombre en variable de sesión y recargar la página principal.
		$_SESSION['nombre']=$out['nicename'];
		header("Location: " . ORIGIN . ROOT);
	}else{
		// Contraseña errónea. Mostrar formulario y salir.
		$loginerror = "<p class=\"error\">Usuario o contraseña erróneos</p>";
		show_form();
		exit;
	}

}else{
	// Cualquier otro caso indeterminado o el usuario NO está registrado.
	// recrear formulario login y terminar la ejecución. No volver a index.
	if (DEBUG_VIS == 1) { // No se si las declaracions de abajo funcionaran.
		debug_add ("***auth.php\nEsperando credenciales\n"); // no se si esto llega a una llamada para que se vea.
		debug_add ("SESION: ".session_id()."\n");
		debug_add ("matriz de session: ".print_r ($_SESSION, TRUE)."\n");
	}
	show_form();
	exit;
}

function show_form(){  // A LO MEJOR ESTA FUNCION NO DEBERIA ESTAR AQUI?? Ver bug 4
	global $loginerror;
	include ("template/header.php");
	?>

	<div style="text-align: center;">
		<h2>Autentificación</h2>
		<?= $loginerror ?>

		<p>Porfavor, introduce tu nombre de usuario y tu contraseña</p>
		<form id="login" class="form" method="post" action="<?= ROOT ?>">
			<table class="login">
				<tr><td class="r"><p>Usuario:</p></td><td><input type="text" name="usuario" id="usuario" class="editor_field" value="" size="20" /></td></tr>
				<tr><td class="r"><p>Contraseña:</p></td><td><input type="password" name="pass" id="pass" class="editor_field" value="" size="20" /></td></tr>
			</table>
			<p><input type="submit" id="submit" value="Entrar" class="submit button_big" /></p>
		</form>
	</div>
<?php
	include ("template/footer.php");
}
?>
