<?php

/*
Vale,

Después de jugar un poco con el código, no estoy seguro de que quiera esto. Es decir, para tener que poner *sdad* más me vale poner <b>kjhkj</b>,
no es tanto ahorro de teclear. O podemos dejar unas básicas (como *, _ y las listas), pero para lo demás me parece mejor hacer html puro y duro, que
esto sólo sea un atajo. Mmm... no sé.

El problema adicional es que como queremos que la edición sea in situ, el texto de la base de datos debe ser html. De lo contrario, si es texto
con símbolos, se traduce a html para que se visualice correctamente en la página y al editarlo, se está editando el html... Debemos cambiar a html
a la hora de guardar, por lo que no haría falta para visualizar, sólo para guardar.

Pero entonces lo que pasa es que como los regexp están definidos a lo bruto (en vez de detectar las listas con '*' hacemos dos pasos intermedios y en 
el último cambiamos los <li>), entonces pillan también el html que ya está limpio. Para evitarlo habría que pasar todo a símbolos, editar, convertir
a html y guardar. Impráctico, porque entonces jode el html.

Creo que de momento dejo estas funciones para más adelante.

______________________________________________________________________________________
Básicamente queremos implementar el lenguaje de las Writeboards:
                    
*sdfsfdsdf*         -> negritas
_jhkhkjhkk_         -> cursiva
                    
*Item               -> lista
*Item               
                    
1. Item             -> lista numerada
2. Item             
                    
                    
bq. Indented block  -> Bloque indentado

h. Titulo           -> Cabecera (h3, porque h1 es el logotipo y h2 es el título de la página, aunque se puede depurar el css)

"texto":http://www.37signals.com  -> enlace

!http://37signals.com/logo.gif!   -> imagen

Podemos empezar por los sencillos (negritas, cursivas y listas)
*/

function get_html($input){
	$text = preg_replace("/(.+)\r\n\r\n/i","<p>$1</p>\n",$input);
	$text = preg_replace("/\*(.*)\*/i","<b>$1</b>",$text);
	$text = preg_replace("/_(.*)_/i","<i>$1</i>",$text);
	
	//listas. bufff!!!
	$text = preg_replace("/\*+(.*)?/i","<li>$1</li>",$text);
	$text = preg_replace("/(\<li\>)(.*)\s(\<\/li\>)/i","$1$2$3",$text); //horrible. Para limpiar algunos li
	$text = preg_replace("/(\<li\>(.*)\<\/li\>\n*)+/is","<ul>\n$1\n</ul>\n",$text); //Igualmente horrible. Tiene que haber una forma más elegante!!!
	//Problema: las tres lineas anteriores se tienen que hacer en un mismo paso, para que no interfiera con la detección de las listas ordenadas.
	//Lo dejo por ahora
	//$text = preg_replace("/\d+(.*)?/i","<li>$1</li>",$text);
	//$text = preg_replace("/(\<li\>)(.*)\s(\<\/li\>)/i","$1$2$3",$text); 
	//$text = preg_replace("/(\<li\>(.*)\<\/li\>\n*)+/is","<ol>\n$1\n</ol>\n",$text); 

	return ($text);
}
?>