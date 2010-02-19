function activateSearch() {
    if ($('searchform')) {
        $('s').value = 'Buscar entradas...'; // Default text in the search box
        var o = document.createElement('div'); // Old search results div
        //var n = document.createElement('div'); // Current search results div
        $('searchform').onsubmit = function() { doSearch();return false; };
        $('s').onfocus = focusS; // Function to clear the default search box text on focus
        $('s').onblur = blurS; // Function to clear the default search box text on focus
        var s = $('resultados');
        var f = $('searchform');
        var n = $('resultadosactuales')
        o.id = 'resultadosviejos';
        //n.id = 'resultadosactuales';
        //s.appendChild(n);
        s.appendChild(o);
        o.style.display = 'none';
        //n.style.display = 'none';
        is_searching = false;
    }
}

function doSearch() {
    // If we're already loading, don't do anything
    if (is_searching) return false; 
    s = $F('s');
    w = $F('wholesearch');
    // Same if the search is blank
    if (s == '' || s == 'Buscar entradas...') return false; 
    is_searching = true;
    c = $('resultadosactuales');
    o = $('resultadosviejos');
    b = $('submit');
    b.value = 'Cargando...';
    b.disabled = true;
    o.innerHTML = c.innerHTML;
    c.style.display = 'none';
    o.style.display = 'block';
    // Setup the parameters and make the ajax call
    pars = "check=busqueda&completa="+w+"&search="+encodeURIComponent(s);
    var myAjax = new Ajax.Request('http://www.nuuve.com/hq/pannel/', 
          {method: 'POST', parameters: pars, onSuccess:doSearchResponse});
}

function doSearchResponse(response) {
    $('resultadosactuales').innerHTML = response.responseText;
    new Effect.BlindUp('resultadosviejos',{duration:.8});
    new Effect.BlindDown('resultadosactuales',{duration:.8, afterFinish:resetForm});
}

function resetForm() {
    s = $('submit');
    s.value = 'Ir';
    s.disabled = false;
    is_searching = false;
}

function focusS() {
    if ($F('s') == 'Buscar entradas...') $('s').value = '';
}
function blurS() {
    if ($F('s') == '') $('s').value = 'Buscar entradas...';
}

Event.observe(window, 'load', activateSearch, false);