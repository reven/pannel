/*
Modificaciones a scriptaculous.js para nuuve pannel
Reven
*/

/*Permitir html en edición*/
Object.extend(Ajax.InPlaceEditor.prototype, {
    onLoadedExternalText: function(transport) {
        Element.removeClassName(this.form,
        this.options.loadingClassName);
        this.editField.disabled = false;
        this.editField.value = transport.responseText;
        Field.scrollFreeActivate(this.editField);
    }

});

Object.extend(Ajax.InPlaceEditor.prototype, {
    getText: function() {
     return this.element.innerHTML;}

});

/*Cambiar click por doble click para poder seleccionar cómodamente*/
Ajax.InPlaceEditor.Listeners.dblclick = Ajax.InPlaceEditor.Listeners.click;
delete Ajax.InPlaceEditor.Listeners.click;