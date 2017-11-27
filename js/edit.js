/*
edit and inline form tools

Add event handlers to the elements and define the form.
*/
/* IMPORTANTE ******************************+
post.php está escuchando para los siguentes indicadores:
['editorId']=="text"
['editorId']=="posttitle"
['editorId']=="estado"
['editorId']=="prioridad"
Codifica esto en los formularios o en las llamadas ajax.

Tambien recuerda que tenemos estas vars:
pageRoot
postId
safeText
state
prio
safeTitle

*/

// Init vars for global scope.
var oldValue;

function warning_url(url) {
  var thing = '<p class="error">El título ha cambiado. Antes de seguir editando, vaya a la nueva url: <a href="$root'+url.responseText+'/">'+url.responseText+'</a></p>';
  $('#posttitle').insert({ after: thing });
}

// Hover effect
$("#posttitle").hover(
  function() {
    $( this ).css("background-color", "#FFFF00");
  }, function() {
    $( this ).css("background-color", "#FFFFFF");
  }
);

// Listener and form for posttitle
$("#posttitle").dblclick(function(){
  var $this = $( this );
  oldValue = $this.text(); // oldValue is global
  $this.replaceWith('<form id="posttitle-inplaceeditor" class="inplaceeditor-form"><input id="newtitle" class="editor_field" type="text" value="' + oldValue + '"><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');
  $('#posttitle-inplaceeditor').submit(function() { // catch the form's submit event
    $.ajax({
      data: "editorId=posttitle&id=" + id + "&title=" + encodeURIComponent($("#newtitle").val()),
      method: "GET" //DEBERIA SER POST; ESTO ES OLO DE PRUEBA!!!!!!
    });
      // on sucess haz un flash o alguna notificación y borra el formulario, remplazandolo por oldValue.
    return false;
  });
});

  // Ahora tenemos que capturar events del formulario (neutralizar los botones) y hacer llamadas ajax.


// Form handler. One for each or one for all??
/* Este handler no pilla el formulario. Probablemente porque el formulario no está ahí cuando cargamos el script. La unica opción será añadirlo arriba??*/
$('#posttitle-inplaceeditor').submit(function() { // catch the form's submit event
  alert ("Crisis averted");
  /*$.ajax({ // create an AJAX call...
    data:   $( this ).serialize(), // get the form data
    method: "PUT",                 // GET or POST
    //url: SearchUrl,              // default is current page
    success: function(response) {  // on success..
      $("#resultadosactuales").html(response); // update the DIV
    },
    error: function(response) {    // on error...
      $("#resultadosactuales").html("<p class=\"error\">Error: " + response);
    }
  });*/
  return false; // cancel original event to prevent form submitting
});

/*
select();
*/

/*

OLD SCRIPTS..
new Ajax.InPlaceEditor('posttitle', '$root', { okText:'Guardar',cancelText:'Cancelar', clickToEditText:'Doble-click para editar',
  callback: function(form, value) {return 'post_id=$post_id&text=$safe_text&state=$out[state]&imp=$out[prioridad]&title=' + encodeURIComponent(value);warning_url(value)},
  onComplete: function(value,element) {warning_url(value);new Effect.Highlight(element, {startcolor: this.options.highlightColor})}})

new Ajax.InPlaceCollectionEditor( 'prioridad', '$root', { okText:'Guardar',cancelText:'Cancelar',
  clickToEditText:'Doble-click para editar', collection: [['1','Importante'], ['0','Normal']], callback: function(form, value) {return 'id=$out[id]&value='+value}});

new Ajax.InPlaceCollectionEditor( 'estado', '$root', { okText:'Guardar',cancelText:'Cancelar',
  clickToEditText:'Doble-click para editar', collection: [['','-- (quitar marca)'], ['P','Planteada'], ['E', 'En curso'], ['X', 'Estancada'], ['F', 'Esperando feedback'], ['C', 'Cancelada'], ['H', 'Hibernando']], callback: function(form, value) {return 'id=$out[id]&value='+value}});

new Ajax.InPlaceEditor('text', '$root', {rows:10,cols:40,okText:'Guardar',cancelText:'Cancelar',clickToEditText:'Doble-click para editar',
  loadTextURL:'{$root}?markdown=1&id={$out['id']}',
  callback: function(form, value) {return 'post_id=$post_id&state=$out[state]&imp=$out[prioridad]&title=$safe_title&content='+ encodeURIComponent(value)},
  onEnterEditMode: function(form, value) { Effect.SlideDown('markdown');},
  onLeaveEditMode: function(form, value) { Effect.SlideUp('markdown'); }})
*/
