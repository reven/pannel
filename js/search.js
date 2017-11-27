/*
search parser

We identify the results div and take care to fill in with new results after a search
*/

// Make search box more obvious
$("#s").val("Buscar entradas..."); // Default text in the search box

// Function to clear the default search box text on focus
$("#s").focus(function(){
  if ($( this ).val() === "Buscar entradas...") $( this ).val("");
});

// Function to restore placeholder text on blur
$("#s").blur(function(){
  if ($( this ).val() === "") $( this ).val("Buscar entradas...");
});

// Handle the search form
$('#searchform').submit(function() { // catch the form's submit event
    $.ajax({ // create an AJAX call...
        data:   $( this ).serialize(), // get the form data
        method: "GET",               // GET or POST
        //url: SearchUrl,            // default is current page
        success: function(response) { // on success..
          $("#resultadosactuales").html(response); // update the DIV
        },
        error: function(response) {   // on error...
          $("#resultadosactuales").html("<p class=\"error\">Error: " + response);
        }
    });
    return false; // cancel original event to prevent form submitting
});
