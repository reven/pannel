<?php
// Template for revisions display and edit

if (DEBUG_VIS == 1) {
	debug_add ("\n***Plantilla llamada: versions.php\n");
}

// Coger variables de constantes para poder incluirlas en variables
$root = ROOT;
$origin = ORIGIN;

// Connect and get the post_id of this page
$c = connect();
$c->set_charset('utf8');
$page = urldecode($page);
$query ="SELECT post_id FROM posts WHERE title = \"$page\" ORDER BY date DESC LIMIT 1";
$result = query($query,$c);

if (mysqli_num_rows($result)==0){
	echo ("<p class=\"error\">Lo siento, pero parece que no existe esa página. <a href=\"$root\">volver</a>.</p>");
	return FALSE;
}

$out = fetch_array($result);
$post_id = $out['post_id'];
/* debug */
if (DEBUG_VIS == 1) {
	debug_add ("Page es $page\nQUERY es $query\n");
	debug_add ("Resultados de query: ".print_r($out, TRUE));
	debug_add ("\nCargando revisiones para post id: $post_id.\n");
}

// Get all the versions
$query ="SELECT * FROM posts WHERE post_id = '$post_id' ORDER BY date DESC";

$result = query($query,$c);
$rows = $result->num_rows;
$out = $result->fetch_all(MYSQLI_ASSOC); // dump everyting

$result->free(); // not needed anymore

// Use length as measure of difference. Very simplified, but gives a general idea.
for ($i = 0; $i < $rows; $i++) {
  $c_len[$i] = strlen($out[$i]['content']);
}
$cont_diff[$rows-1] = "<td class=\"plus\">+</td>";
for ($i = 0; $i < $rows-1; $i++) { // skip the first
  if ($c_len[$i] > $c_len[$i+1]) { $cont_diff[$i] = "<td class=\"plus\">+</td>";
  } elseif ($c_len[$i] == $c_len[$i+1]) { $cont_diff[$i] = "<td class=\"same\">·</td>";
  } elseif ($c_len[$i] < $c_len[$i+1]) { $cont_diff[$i] = "<td class=\"minus\">-</td>"; }
}
if (DEBUG_VIS == 1 && DEBUG_LVL == "2") {
	debug_add ("rows: $rows \n");
	debug_add ("Longitudes : " . print_r ($c_len, TRUE));
  debug_add ("\nDiffs : " . print_r ($cont_diff, TRUE));
}

// Show the data
echo '
<h2>Versiones</h2>
<p class="meta"><a href="' . ORIGIN . ROOT . $page . '">Editar la página</a> | ' . $rows . ' revisiones</p>
<div id="resultados">
  <table><tbody>
    <tr><th></th><th>Título</th><th>autor</th><th>fecha</th><th>diff</th><th>borrar</th></tr>
';
for ($i = 0; $i < $rows; $i++) {
  echo "    <tr class=\"editInPlace\" id=\"r" . $i . "r" . $out[$i]['id'] . "\"><td class=\"edit\">";
  echo ($i == 0 ? "➤" : "");
  echo "</td><td>" . $out[$i]['title'] . "</td><td>" . $out[$i]['author'];
  echo "</td><td class=\"meta\">" . date("j M Y, G:i",strtotime ($out[$i]['date']));
  echo "</td>" . $cont_diff[$i];
  echo '<td><button class="btnicon" type="submit"><svg height="16" version="1.1" viewBox="0 0 12 16" width="13"><path fill="#666" fill-rule="evenodd" d="M11 2H9c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1H2c-.55 0-1 .45-1 1v1c0 .55.45 1 1 1v9c0 .55.45 1 1 1h7c.55 0 1-.45 1-1V5c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 12H3V5h1v8h1V5h1v8h1V5h1v8h1V5h1v9zm1-10H2V3h9v1z"></path></svg></button></td></tr>' . "\n";
}

?>
</tbody></table>
</div>
<div id="display">
  <h3>contenido:</h3>
  <div id="text">
    <?= get_html($out[0]['content']) ?>
  </div>
</div>

<script>

// Place handler on all rows of class .editInPlace
$('.editInPlace').click(function rowHandler() { // catch the click event
  // parse refs from row ID
  var $this = $( this );
  var refs = this.id.split("r");
	$("#text").addClass("saving");
  $.ajax({ // create an AJAX call...
    url: "<?= ORIGIN . ROOT ?>",
    data: "version=1&id=" + refs[2], // get the form data
    method: "GET",                 // GET or POST
    success: function(response) {  // on success..
      $("#text").html(response); // update the DIV
			$("#text").removeClass("saving");
      $("#text").before('<span class="yay">✔ Cargado</span>');
      $(".yay").fadeOut(1500);
      $("td.edit").html("");
      $this.children( ".edit" ).html("➤");
    },
    error: function(response) {    // on error...
      $("#text").html("<p class=\"error\">Error: " + response);
    }
  });
  return false; // cancel original event to prevent form submitting
});

// Hover over svg buttons
$(".btnicon").hover(
	function() {
		$( this ).find("path").attr("fill", "#ff0000");
	}, function() {
		$( this ).find("path").attr("fill", "#666666");
	});

// Catch delete button click
$('.btnicon').click(function(event) {
	$(".confirm").remove();
  var $this = $( this );
  $this.after('<span class="confirm">Seguro? <input value="Si" class="submit button" type="submit"><input value="No" class="cancel button" type="button"></span>');
  // $this.parent().parent().off( "click");
  $('.submit').click(function() {
    var refs = $this.closest('tr').attr('id').split("r");
    $.ajax({
      url: "<?= ORIGIN . ROOT ?>",
      data: "function=del_rev&id=" + refs[2],
      method: "POST",
       success: function(data) {
         if (data != "pannel: success") {
         return handleError(data);
         }
         $this.closest('tr').after('<span class="yay">✔ Borrada!</span>');
         $(".yay").fadeOut(3000);
         $this.closest('tr').remove();
       },
       error: handleError = function(response){
         $(".confirm").remove;
         $("#resultados").before("<div class=\"error\"><p> :-( Errores: <br>" + response + "</p></div>");
       }
    });
		return false;
  });
  $('.cancel').click(function() {
    $(".confirm").fadeOut(500);
		return false;
  });
  return false;
});

</script>
