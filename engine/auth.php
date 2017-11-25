<?php
/*Módulo de autentificación. NO IMPLEMENTADO

Esto es sólo un primer de cómo tendría que funcionar, aunque supongo que se puede reciclar el módulo auth de alguna otra app.

TO DO: Este módulo no debería exponerse dando salida a html o al formulario. Aquí se deberían definir los casos y el flujo de auth y las salidas ser devueltas a las funciones correspondientes
*/

session_start();
if(isset($_SESSION['nombre'])){
	// Usuario está registrado
	// No hacemos nada y devolvemos control a index.php


}elseif (!isset($_SESSION['nombre']) && isset($_POST['usuario']) && isset($_POST['pass'])){
	// No está la sesión y hemos recibido el formulario via POST
	// Comprobar las credenciales recibidas con la base de datos.
	$c = connect();
	mysql_set_charset('utf8',$c);

	// Limpiar $usuario y $pass de formulario
	$usuario=mysql_real_escape_string($_POST['usuario']);
	$pass=mysql_real_escape_string($_POST['pass']);
	$crypt_pass=md5($pass); // Implementar mas seguridad: cifrado + salt (bug 6)

	$query="SELECT * FROM users WHERE user_login='$usuario' and user_pass='$crypt_pass'";
	$result = query($query,$c);

  //debug
	$debug.="\n<p>Comprobando nombre</p>\n
					<ul><li><b>Usuario:</b> $usuario</li><li><b>Pass:</b> $pass</li><li><b>MD5:</b> $crypt_pass</li></ul>";
	//fin debug

	// Contar filas
	if (mysql_num_rows($result)==1){
		// Exito. registrar nombre en variable de sesión y recargar la página principal.
		$out = fetch_array($result);
		$_SESSION['nombre']=$out['name'];
		header("Location: http://" . $_SERVER['SERVER_NAME'] . ROOT); // ver bug 3
	}else{
		// Contraseña errónea. Mostrar formulario y salir.
		$loginerror = "<p class=\"error\">Usuario o contraseña erróneos</p>";
		show_form();
		exit;
	}

}else{
	// Cualquier otro caso indeterminado o el usuario NO está registrado.
	// recrear formulario login y terminar la ejecución. No volver a index.
	show_form();
	exit;
}

function show_form(){  // ESTA FUNCION NO DEBERIA ESTAR AQUI?? Ver bug 4
	global $loginerror;
	include ("template/header.php");
	?>

	<div style="text-align: center;">
		<h2>Autentificación</h2>
		<?php echo $loginerror; ?>

		<p>Porfavor, introduce tu nombre de usuario y tu contraseña</p>
		<form id="login" class="form" method="post" action="<?php echo ROOT; ?>">
			<table class="login">
				<tr><td class="r"><p>Usuario:</p></td><td><input type="text" name="usuario" id="usuario" class="editor_field" value="" size="20" /></td></tr>
				<tr><td class="r"><p>Contraseña:</p></td><td><input type="password" name="pass" id="pass" class="editor_field" value="" size="20" /></td></tr>
			</table>
			<p><input type="submit" id="submit" value="Entrar" class="editor_ok_button" /></p>
		</form>
	</div>
<?php
	include ("template/footer.php");
}
?>
