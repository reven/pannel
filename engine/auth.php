<?php
/*Módulo de autentificación. NO IMPLEMENTADO

Esto es sólo un primer de cómo tendría que funcionar, aunque supongo que se puede reciclar el módulo auth de alguna otra app. Ideas, Pau?
*/

session_start();
if(isset($_SESSION['loggedin'])){
	//usuario está registrado
	//devolver $logged_user , que es la variable que he utilizado para esto. Hay otra forma?
	$logged_user = $_SESSION['name'];
	// termina la ejecución
}else{
	//el usuario NO está registrado.
	//header("Location: http://www.nuuve.com{$root}auth/"); //-> En vez de usar otra plantilla más, porque no ponemos aqui el html y punto?
	include ("template/header.php");
	?>

	<h2>Autentificación</h2>
	<div style="text-align: center;">
	<p>Porfavor, introduce tu nombre de usuario y tu contraseña</p>
	<form id="searchform" class="form" method="POST" action="/">
		<p>Usuario <input type="text" name="s" id="s" class="editor_field" value="" size="20"></p>
		<p>Contraseña <input type="password" name="pass" id="pass" class="editor_field" value="" size="20"></p>
		<p><input type="submit" id="submit" value="Entrar" class="editor_ok_button"></p>
	</form>
	
	</div>

<?php
	include ("template/footer.php");
	exit;
	/*
	VER: http://www.phpeasystep.com/workshopview.php?id=6
	POST es el que debe mirar si es válido.
	*/
	
	/*El cómo manejamos esto se puede hacer de varias maneras:
	
	1. cargando una plantilla nueva que tenga solo un formulario de login. Este formulario habría que enviarlo a alguien para que parsee lo que se envía. El candidato ideal sería post.php. Sería algo así como:
	include(engine/login.php);
	exit;
	
	2. Añadir un formulario a la página default. Se puede cargar el html en una variable y entonces se puede imprimir la variable arriba del todo del documento. Pero no tiene sentido cargar default.php con las páginas disponibles, porque una persona sin login podría ver contenido. Tendríamos el mismo problema de quién se ocupa de parsear el formulario.
	
	3. Lo mismo que 2, pero cargando el formulario en un div semitransparente que cubra toda la pantalla, similar a las imágenes que se ven con lightbox. No implica mejor seguridad que 2, es símplemente más vistoso.
	
	4. Alguna forma sin formularios ni post?
	
	En cualquier caso, sería la otra página (post.php por ejemplo) quien se ocupe de verificar que nombre y pass se ajustan a lo que hay en la BdD y fijar los elemntos de sessión. Después devolver el control a la app, parecido a lo que hace con el formulario de nueva entrada (sin escupir html y mediante cabeceras).
	
	*/
}
?>