<?php
/*Módulo de autentificación. NO IMPLEMENTADO

Esto es sólo un primer de cómo tendría que funcionar, aunque supongo que se puede reciclar el módulo auth de alguna otra app. Ideas, Pau?
*/

session_start();
if(isset($_SESSION['nombre'])){
	//usuario está registrado
	// Devolvemos control a index.php
	
	
}elseif (!isset($_SESSION['nombre']) && isset($_POST['usuario']) && isset($_POST['pass'])){
	//No está la sesión y hemos recibido el formulario via POST
	$c = connect();
	mysql_set_charset('utf8',$c);

	// Limpiar $usuario y $pass
	$usuario=mysql_real_escape_string($_POST['usuario']);
	$pass=mysql_real_escape_string($_POST['pass']);
	$crypt_pass=md5($pass);

	$query="SELECT * FROM users WHERE login='$usuario' and password='$crypt_pass'";
	$result = query($query,$c);

/*debug* /
echo "<p>Comprobando nombre</p>";
echo "<ul><li><b>Usuario:</b> $usuario</li><li><b>Pass:</b> $pass</li><li><b>MD5:</b> $crypt_pass</li></ul>";
/*fin debug*/

	// Contar filas
	if (mysql_num_rows($result)==1){
		// Exito. registrar nombre.
		$out = fetch_array($result);
		$_SESSION['nombre']=$out['name'];
		header("Location: http://www.nuuve.com/hq/pannel/");
	}else{
		$loginerror = "<p class=\"error\">Usuario o contraseña erróneos</p>";
		show_form();
		exit;
	}
	
	
}else{
	//el usuario NO está registrado.
	//recrear formulario login y cerrar
	show_form();
	exit;
}

function show_form(){
	global $loginerror;
	include ("template/header.php");
	?>

	<div style="text-align: center;">
		<h2>Autentificación</h2>
		<?php echo $loginerror; ?>
	
		<p>Porfavor, introduce tu nombre de usuario y tu contraseña</p>
		<form id="login" class="form" method="post" action="<?php echo $root; ?>">
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