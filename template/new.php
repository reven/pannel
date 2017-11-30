<?php
//new.php es sólo la plantilla del formulario. post.php es el que se encarga de meter campos

?>

<h2>Introducir nueva entrada</h2>
<form id="nuevo" class="form" method="post" action="<?php echo ROOT ?>">
	<input type="hidden" name="check" value="newpost" />
	<p>
		<input type="text" id="titulo" name="title" class="editor_field" value="Título" onfocus="this.value=(this.value=='Título') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Título' : this.value;" size="20" /></p>
	<p>Contenido<br />
		<div id="markdown" class="markdown"><span class="handle">Acerca de formato abreviado</span>
			<div id="toggle_slide" style="display:none;"><p>Utiliza los siguientes atajos para formatear tu texto:<br />
				*negrita* → <b>negrita</b><br />
				_cursiva_ → <i>cursiva</i><br />
				<b>*</b> Item → Listas<br />
				<b>1.</b> Item → Listas ordenadas<br />
				<b>bq.</b> Texto indentado<br />
				<b>h.</b> Titulo<br />
				<b>"</b>enlace<b>":</b>http://www.nuuve.com → enlace<br />
				<b>!</b>http://www.nuuve.com/logo.gif<b>!</b> → imagen<br /><br />
				Puedes anidar listas y bloques de texto indentado. <a href="<?php echo ROOT; ?>help/#formato">(+ info)</a>

			</p></div>
		</div>
		<textarea rows="10" cols="40" name="content" class="editor_field"></textarea></p>
	<p><input type="checkbox" name="priority" id="priority" value="1" />Marcar como importante | Establecer estado como <select name="state" id="state"><option value="">(sin marca)</option><option value="P">Planteada</option><option value="E">En curso</option><option value="X">Estancada</option><option value="F">Esperando Feedback</option><option value="C">Cancelada</option><option value="H">Hibernando</option></select></p>
	<p><input type="submit" value="Guardar" class="submit button_big" /></p>
</form>
<script>
$(document).ready(function(){
  $(".handle").click(function(){
    $("#toggle_slide").slideDown("slow");
		$(".handle").after('<span class="closer">Cerrar</span>');
		$(".closer").click(function(){
			$("#toggle_slide").slideUp("slow");
			$(".closer").remove();
			return false;
		});
		return false;
	});
});
</script>
