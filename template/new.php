<?php
//new.php es sólo la plantilla del formulario. edit.php es el que se encarga de meter campos

?>

<h2>Introducir nueva entrada</h2>

<form id="nuevo" class="form" method="POST" action="http://www.nuuve.com<?php echo $root?>">
	<input type="hidden" name="check" value="newpost" />
	<p>
		<input type="text" name="title" class="editor_field" value="Título" onfocus="this.value=(this.value=='Título') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Título' : this.value;" size="20" /></p>
	<p>Contenido<br />
		<textarea rows="10" cols="40" name="text" class="editor_field"></textarea></p>
	<p><input type="submit" value="Guardar" class="editor_ok_button" /></p>
</form>
