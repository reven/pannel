<?php
// Página de Ayuda
?>

	<h2>Ayuda</h2>
	<p>Si necesitas ayuda con alguno de los elementos de <em><strong>pannel</strong></em>, este es el lugar donde obtenerla.</p>
	<?php
	echo (isset($_SERVER['HTTP_REFERER']) ? '<p class="r"><a href="'.$_SERVER['HTTP_REFERER']."\">volver atrás</a></p>" : "" );
	?>
	<a name="formato"></a>
	<h3>Formato abreviado</h3>
	<p>Para formatear el texto puedes hacer uso de la siguiente sintaxis abreviada:</p>
	<table class="help">
		<tbody>
			<tr><th>Descripción</th><th>Abreviatura</th><th>efecto</th></tr>
			<tr><td>negrita</td><td><strong>**</strong>texto<strong>**</strong></td><td><strong>&lt;strong&gt;</strong>texto<strong>&lt;/strong&gt;</strong></td></tr>
			<tr><td>cursiva</td><td><strong>__</strong>texto<strong>__</strong></td><td><strong>&lt;em&gt;</strong>texto<strong>&lt;/em&gt;</strong></td></tr>
			<tr><td>Listas</td><td><strong>*</strong> Item<br><strong>*</strong> Item</td><td><strong>&lt;ul&gt;<br>&lt;li&gt;</strong>Item<strong>&lt;/li&gt;<br>&lt;li&gt;</strong>Item<strong>&lt;/li&gt;<br>&lt;/ul&gt;</strong></td></tr>
			<tr><td>Listas ordenadas</td><td><strong>1.</strong> Item<br><strong>2.</strong> Item</td><td><strong>&lt;ol&gt;<br>&lt;li&gt;</strong>Item<strong>&lt;/li&gt;<br>&lt;li&gt;</strong>Item<strong>&lt;/li&gt;<br>&lt;/ol&gt;</strong></td></tr>
			<tr><td>Texto indentado</td><td><strong>bq.</strong> texto</td><td><strong>&lt;blockquote&gt;</strong>texto<strong>&lt;/blockquote&gt;</strong></td></tr>
			<tr><td>Cabeceras</td><td><strong>#</strong> Texto<br><strong>##</strong> Texto</td><td><strong>&lt;h3&gt;</strong>texto<strong>&lt;/h3&gt;</strong><br><strong>&lt;h4&gt;</strong>texto<strong>&lt;/h4&gt;</strong></td></tr>
			<tr><td>Enlaces</td><td><strong>"</strong>texto<strong>":</strong>http://www.nuuve.com</td><td><strong>&lt;a href="</strong>http://www.nuuve.com<strong>"&gt;</strong>texto<strong>&lt;/a&gt;</strong></td></tr>
			<tr><td>Imágenes</td><td><strong>!</strong>http://www.nuuve.com/logo.png<strong>!</strong></td><td><strong>&lt;img src="</strong>http://www.nuuve.com/logo.png<strong>" /&gt;</strong></td></tr>
			<tr><td colspan="3"></td></tr>
			<tr><td colspan="3">Adicionalmente, también se añaden etiquetas de párrafo o de <em>breaks</em>, según se deje línea en blanco entremedias o no.</td></tr>
			<tr><td>Párrafos</td><td>texto↵<br>↵<br>texto</td><td><strong>&lt;p&gt;</strong>texto<strong>&lt;/p&gt;</strong><br><br><strong>&lt;p&gt;</strong>texto<strong>&lt;/p&gt;</strong></td></tr>
			<tr><td><em>Breaks</em></td><td>texto↵<br>texto</td><td><strong>&lt;p&gt;</strong>texto<strong>&lt;br /&gt;</strong><br>texto<strong>&lt;/p&gt;</strong></td></tr>
		</tbody>
	</table>
	<p>Notas:</p>
	<ul>
		<li>Las listas pueden ser anidadas, pero sólo hasta 1 nivel</li>
		<li>Los bloques de texto indentado pueden ser anidados</li>
	</ul>
