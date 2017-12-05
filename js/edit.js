/*
edit and inline form tools

Add event handlers to the elements and define the form.
*/
/*
Tambien recuerda que tenemos estas vars:
id
pageRoot
postId --> Realmente no necesario???
safeText
state
priority
safeTitle

*/

function warning_url(url) {
  var thing = '<p class="success">Guardado! El título ha cambiado; usa la nueva url antes de realizar más cambios:<br><a href="'+ pageRoot + encodeURIComponent(url) +'/">'+ url +'</a></p>';
  $('#posttitle').after(thing);
}

function get_authdate() {
  var n = new Date();
  var meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  var construct = "Última modificación por <strong>" + author + "</strong> el " + n.getDate() + " de " + meses[n.getMonth()] + " de " + n.getFullYear() + ", a las " + n.getHours() + ":" + n.getMinutes();
  $("#auth-date").html(construct);
}

// Listener and form for posttitle
$("#posttitle").mousedown(function(){ return false; }); // avoid selection
$("#posttitle").dblclick(function(){
  var $this = $( this );
  var oldValue = $this.text();
  $(".inplaceeditor-form").remove();  // Remove all other open editors
  $(".editInPlace").show();           // Show all other hidden editable blocks
  $this.hide();                       // Hide this block
  $this.after('<form id="posttitle-inplaceeditor" class="inplaceeditor-form"><input id="newtitle" class="editor_field" type="text" value="' + oldValue + '"><input value="Guardar" class="submit button_big" type="submit"><input value="Cancelar" class="cancel button_big" type="button"></form>');
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
  $(".cancel").click(function(){
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
  $this.after('<form id="priority-inplaceeditor" class="inplaceeditor-form"><select name="priority"><option value="0">Normal</option><option value="1"' + pri1 + '>Importante</option></select><input value="Guardar" class="submit button" type="submit"><input value="Cancelar" class="cancel button" type="button"></form>');

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
  $(".cancel").click(function(){
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
  $this.after('<form id="state-inplaceeditor" class="inplaceeditor-form"><select name="state"><option value=" ">(sin marca)</option><option value="P">Planteada</option><option value="E">En curso</option><option value="X">Estancada</option><option value="F">Esperando feedback</option><option value="C">Cancelada</option><option value="H">Hibernando</option></select><input value="Guardar" class="submit button" type="submit"><input value="Cancelar" class="cancel button" type="button"></form>');
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
  $(".cancel").click(function(){
    $("#state-inplaceeditor").remove();
    $(".editInPlace").show();
  });
});

/* Listener and form for text. This needs all vars for new revision
    Logic: 1. GET markdown text to put in edit box
           2. POST it to the server to get it saved
           3. GET html to display the page again
*/
$("#text").mousedown(function(){ return false; }); // avoid selection
$("#text").dblclick(function(){
  var $this = $( this );
  var oldHtml = $this.html();
  $(".inplaceeditor-form").remove();
  $(".editInPlace").show();
  $this.hide();
  $("#markdown").show();    // Show the edit_tools
  $this.after('<form id="text-inplaceeditor" class="inplaceeditor-form" style="width:100%"><textarea rows="10" cols="40" name="content" class="editor_field saving">loading...</textarea><br><input value="Guardar" class="submit button_big" type="submit"><input value="Cancelar" class="cancel button_big" type="button"></form>');
  var oldMarkdown;
  // 1. GET markdown
  $.get( pageRoot, { markdown: 1, id: id }, function( data ) {
    oldMarkdown = data;
    if (oldMarkdown == "XaX") oldMarkdown = "";
    $("textarea").text(oldMarkdown);
    $("textarea").removeClass("saving");
  });
  // Capture the submit action
  $('#text-inplaceeditor').submit(function() {
    var newValue = $( "textarea" ).val();
    $("input[type='button']").after('<img src="' + pageRoot + 'images/loader.gif">');
    $.ajax({ // 2. POST new value to server
      data: "editorId=text&id=" + id + "&state=" + state + "&post_id=" + postId + "&title=" + safeTitle + "&priority=" + priority + "&content=" + encodeURIComponent(newValue),
      method: "POST",
      success: function(data) {
        data = data.split(";");
        if (data[0] != "pannel: success") {
        return handleError(data);
        }
        var num = data[1].split(" ");
        id = num[1];
        // 3. GET NEW html to re draw page
        var newHtml;
        $.get( pageRoot, { markdown: 0, post_id: postId }, function( data ) {
          newHtml = data;
          if (newHtml == "XaX") newHtml = "";
          $this.html(newHtml);
          $("textarea").removeClass("saving");
          $this.html(newHtml);
          $("#text-inplaceeditor").remove();
          $("#markdown").hide();
          $this.show();
          get_authdate();
          $this.before('<div class="yay">✔ Guardado!</div>');
          $(".yay").fadeOut(4000);
        })
        .fail(function() {
          $this.after("<p class=\"error\"> Error al mostrar el resultado, pero los cambios pudieron salvarse!");

        }); // end of GET

      },
      error: handleError = function(response){
        $("#text-inplaceeditor").remove();
        $this.show();
        $this.after("<p class=\"error\">Error: los datos no pudieron guardarse.<br>" + response);
      }
    });
    // Disable native submission
    return false;
  });
  // Capture the cancel on-click
  $(".cancel").click(function(){
    $("#markdown").hide();
    $("#text-inplaceeditor").remove();
    $(".editInPlace").show();
  });
});

// Listener para edit_tools
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
