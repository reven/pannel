$(document).ready(function(){
setClickable();
});

function setClickable() {
$('.editInPlace').click(function() {
var textarea = '<div><textarea rows="10" cols="60">'+$(this).html()+'</textarea>';
var button	 = '<div><input type="button" value="Guardar" class="saveButton" /> OR <input type="button" value="Cancelar" class="cancelButton" /></div></div>';
var revert = $(this).html();
$(this).after(textarea+button).remove();
$('.saveButton').click(function(){saveChanges(this, false);});
$('.cancelButton').click(function(){saveChanges(this, revert);});
})
.mouseover(function() {
$(this).addClass("editable");
})
.mouseout(function() {
$(this).removeClass("editable");
});
};

function saveChanges(obj, cancel) {
if(!cancel) {
var t = $(obj).parent().siblings(0).val();
$.post("/hq/pannel/engine/edit.php",{
  content: t
},function(data){$('.result').html(data);});
}
else {
var t = cancel;
}
if(t=='') t='(click to add text)';
$(obj).parent().parent().after('<div class="editInPlace">'+t+'</div>').remove();
setClickable();
}