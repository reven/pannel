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

function warning_url(url) {
  var thing = '<p class="warning">El título ha cambiado. Antes de seguir editando, vaya a la nueva url: <a href="'+ pageRoot + encodeURIComponent(url) +'/">'+ url +'</a></p>';
  $('#posttitle').after(thing);
}

// Hover effect
$(".editInPlace").hover(
  function() {
    $( this ).css("background-color", "#FFFF00");
  }, function() {
    $( this ).css("background-color", "#FFFFFF");
  }
);

// Listener and form for posttitle
$("#posttitle").dblclick(function(){
  var $this = $( this );
  var oldValue = $this.text();
  $this.hide();
  $ (".inplaceeditor-form").remove();
  $this.after('<form id="posttitle-inplaceeditor" class="inplaceeditor-form"><input id="newtitle" class="editor_field" type="text" value="' + oldValue + '"><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');
  $('#posttitle-inplaceeditor').submit(function() {
    $.ajax({
      data: "editorId=posttitle&id=" + id + "&title=" + encodeURIComponent($("#newtitle").val()),
      method: "POST",
       success: function(data) {
         if (data != "pannel: success") {
           return handleError(data);
         }
         var newValue = $("#newtitle").val();
         $this.html(newValue);
         $this.show();
         $("#posttitle-inplaceeditor").remove();
         warning_url(newValue);
       },
       error: handleError = function(response){
         $("#posttitle-inplaceeditor").remove();
         $this.show();
         $this.after("<p class=\"error\"> :-( Errores: " + response);
       }
    });
      // on sucess haz un flash o alguna notificación y borra el formulario, remplazandolo por oldValue.
    return false;
  });

});

// Listener and form for priority
$("#prioridad").dblclick(function(){
  var pri0, pri1;
  var $this = $( this );
  var oldValue = $this.text();
  alert (oldValue);
  if (oldValue == 1) {var pri1 = " selected"}else{var pri0 = " selected"};
  $this.hide();
  $this.after('<form id="prioridad-inplaceeditor" class="inplaceeditor-form"><select name="prioridad"><option value="0"' + pri0 + '>Normal</option><option value="1"' + pri1 + '>Importante</option></select><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');
  $('#posttitle-inplaceeditor').submit(function() {
    $.ajax({
      data: "editorId=posttitle&id=" + id + "&title=" + encodeURIComponent($("#newtitle").val()),
      method: "POST",
       success: function(data) {
         if (data != "pannel: success") {
           return handleError(data);
         }
         var newValue = $("#newtitle").val();
         $this.html(newValue);
         $this.show();
         $("#posttitle-inplaceeditor").remove();
         warning_url(newValue);
       },
       error: handleError = function(response){
         $("#posttitle-inplaceeditor").remove();
         $this.show();
         $this.after("<p class=\"error\"> :-( Errores: " + response);
       }
    });
      // on sucess haz un flash o alguna notificación y borra el formulario, remplazandolo por oldValue.
    return false;
  });
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
