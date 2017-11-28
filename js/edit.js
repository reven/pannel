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
  var thing = '<p class="success">Guardado! El título ha cambiado; usa la nueva url antes de realizar más cambios:<br /><a href="'+ pageRoot + encodeURIComponent(url) +'/">'+ url +'</a></p>';
  $('#posttitle').after(thing);
}

function get_authdate() {
  var n = new Date();
  var meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  var construct = "Última modificación por <b>" + author + "</b> el " + n.getDate() + " de " + meses[n.getMonth()] + " de " + n.getFullYear() + ", a las " + n.getHours() + ":" + n.getMinutes();
  $("#auth-date").html(construct);
}

// Listener and form for posttitle
$("#posttitle").mousedown(function(){ return false; }); // avoid selection
$("#posttitle").dblclick(function(){
  var $this = $( this );
  var oldValue = $this.text();
  $(".inplaceeditor-form").remove();
  $(".editInPlace").show();
  $this.hide();
  $this.after('<form id="posttitle-inplaceeditor" class="inplaceeditor-form"><input id="newtitle" class="editor_field" type="text" value="' + oldValue + '"><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');
  // Capture the submit action
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
         $("#posttitle-inplaceeditor").remove();
         $this.show();
         get_authdate();
         warning_url(newValue);
       },
       error: handleError = function(response){
         $("#posttitle-inplaceeditor").remove();
         $this.show();
         $this.after("<p class=\"error\"> :-( Errores: " + response);
       }
    });
    // Disable native submission
    return false;
  });
  // Capture the cancel on-click
  $(".cancel_button").click(function(){
    $("#posttitle-inplaceeditor").remove();
    $(".editInPlace").show();
  });
});

// Listener and form for priority
$("#priority").mousedown(function(){ return false; }); // avoid selection
$("#priority").dblclick(function(){
  var pri1;
  var $this = $( this );
  var oldValue = $this.text();
  if (oldValue == "Importante") {var pri1 = " selected"};
  $(".inplaceeditor-form").remove();
  $(".editInPlace").show();
  $this.hide();
  $this.after('<form id="priority-inplaceeditor" class="inplaceeditor-form"><select name="priority"><option value="0">Normal</option><option value="1"' + pri1 + '>Importante</option></select><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');

  // Capture the submit action
  $('#priority-inplaceeditor').submit(function() {
    var newValue = $( "select" ).val();
    $.ajax({
      data: "editorId=priority&id=" + id + "&priority=" + newValue,
      method: "POST",
       success: function(data) {
         if (data != "pannel: success") {
         return handleError(data);
         }
         var construct;
         if (newValue == 1) { construct = "Importante"; $this.addClass("prio1") }
         if (newValue == 0) { construct = "Marcar prioridad"; $this.removeClass("prio1") }
         $this.html(construct);
         $("#priority-inplaceeditor").remove();
         $this.show();
         get_authdate();
         $this.after('<span class="yay">✔ Guardado!</span>');
         $(".yay").fadeOut(3000);
       },
       error: handleError = function(response){
         $("#priority-inplaceeditor").remove();
         $this.show();
         $this.after("<p class=\"error\"> :-( Errores: " + response);
       }
    });
    // Disable native submission
    return false;
  });
  // Capture the cancel on-click
  $(".cancel_button").click(function(){
    $("#priority-inplaceeditor").remove();
    $(".editInPlace").show();
  });
});

// Listener and form for states
$("#state").mousedown(function(){ return false; }); // avoid selection
$("#state").dblclick(function(){
  var $this = $( this );
  var oldState = $this.children().attr("class");
  var states = {'P':"Planteada",
  								 'E':"En curso",
  								 'X':"Estancada",
  								 'F':"Esperando feedback",
  								 'C':"Cancelada",
  								 'H':"Hibernando"};
  $(".inplaceeditor-form").remove();
  $(".editInPlace").show();
  $this.hide();
  $this.after('<form id="state-inplaceeditor" class="inplaceeditor-form"><select name="state"><option value=" ">(sin marca)</option><option value="P">Planteada</option><option value="E">En curso</option><option value="X">Estancada</option><option value="F">Esperando feedback</option><option value="C">Cancelada</option><option value="H">Hibernando</option></select><input value="Guardar" class="editor_ok_button" type="submit"><input value="Cancelar" class="cancel_button" type="button"></form>');
  $("select[name=state] option[value="+oldState+"]").attr('selected','selected');
  // Capture the submit action
  $('#state-inplaceeditor').submit(function() {
    var newValue = $( "select" ).val();
    $.ajax({
      data: "editorId=state&id=" + id + "&state=" + newValue,
      method: "POST",
       success: function(data) {
         if (data != "pannel: success") {
         return handleError(data);
         }
         var construct = "Marcada como <span class=\""+ newValue + "\">" + states[newValue] + "</span>";
         if (newValue == 0) { construct = "Marcar prioridad"; }
         $this.html(construct);
         $("#state-inplaceeditor").remove();
         $this.show();
         get_authdate();
         $this.after('<span class="yay">✔ Guardado!</span>');
         $(".yay").fadeOut(3000);
       },
       error: handleError = function(response){
         $("#state-inplaceeditor").remove();
         $this.show();
         $this.after("<p class=\"error\"> :-( Errores: " + response);
       }
    });
    // Disable native submission
    return false;
  });
  // Capture the cancel on-click
  $(".cancel_button").click(function(){
    $("#state-inplaceeditor").remove();
    $(".editInPlace").show();
  });
});

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
