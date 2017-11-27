/*
edit and inline form tools

Add event handlers to the elements and define the form.
*/

function warning_url(url)
  {
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

  $("#posttitle").dblclick(function(){
    $( this ).replaceWith('<form>');

  });
/*
  $( "#posttitle" ).hover(function() {
    $( this ).fadeOut( 100 );
    $( this ).fadeIn( 500 );
  });

/*
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
