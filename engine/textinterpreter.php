<?php

/*
______________________________________________________________________________________
Text-interpreter (mini-markdown) by Reven ©2010
--------------------------------------------------------------------------------------

Sustituciones:

*sdfsfdsdf*         -> negritas
_jhkhkjhkk_         -> cursiva

* Item              -> lista
* Item

1. Item             -> lista numerada
2. Item


bq. Indented block  -> Bloque indentado

h. Titulo           -> Cabecera (h3, porque h1 es el logotipo y h2 es el título de la página, aunque se puede depurar el css)

"texto":http://www.37signals.com  -> enlace

!http://37signals.com/logo.gif!   -> imagen

*/
/*
get_html (string);
Takes a string, substitutes markdown tags and returns safe valid html
*/
function get_html($input){
	# First strip tags; We don't want any tags before we start substituting
	$input = htmlentities($input);

	# Standardize line endings: DOS to Unix and Mac to Unix
	$text = preg_replace('{\r\n?}', "\n", $input);

	$regex = array(
		'/\*\*(.*?)\*\*/',
		'/__(.*?)__/',
		'/^#{2}\s?(.*)$/m',
		'/^#{1}\s?(.*)$/m',
		'@"(.+)":(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', // Enlaces con texto: "texto":http://www.37signals.com
		'@!(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)!@', //imágen

	);
	$replace = array(
		"<strong>$1</strong>",
		"<em>$1</em>",
		"<h4>$1</h4>",
		"<h3>$1</h3>",
		"<a href=\"$2\">$1</a>",
		"<img src=\"$1\" />",
	);

	$text = preg_replace($regex,$replace,$text);

	$paragraphs = explode("\n\n", $text);
	// debug
	if (DEBUG_VIS == 1 && DEBUG_LVL == 2) {
		debug_add("Paragraphs : " . print_r ($paragraphs, TRUE) . "\n");
	}
	$text = null;
    foreach($paragraphs as $paragraph) {
		if (!preg_match("/^(\n?)[<|\*|\d|bq\.]/",$paragraph)){
			$paragraph = preg_replace('/(.+)\n/',"$1<br>",$paragraph);
			$text .= "\n<p>".$paragraph."</p>\n";

		}else{
			$paragraph = preg_replace("/^(\t?)\*+\s?(.*)/m","$1<uli>$2</uli>",$paragraph);
			$paragraph = preg_replace("/(\<uli\>(.+?)\<\/uli\>)$/s","<ul>\n$1\n</ul>",$paragraph);
			$paragraph = preg_replace("/^(\t?)\d+\.+\s?(.*)/m","$1<oli>$2</oli>",$paragraph);
			$paragraph = preg_replace("/(\<oli\>(.+?)\<\/oli\>)$/s","<ol>\n$1\n</ol>",$paragraph);
			//Sublistas ---> Solo un nivel de recursión
			//intentar arreglar sublistas --> ul dentro de ul
			$paragraph = preg_replace("#(\</uli\>\n\t\<uli\>)((.+?\</uli\>)\n)(^\<uli\>)#ms","\n\t<ul><li>$3</ul></li>\n<li>",$paragraph);
			//intentar arreglar sublistas --> ol dentro de ol
			$paragraph = preg_replace("#(\</oli\>\n\t\<oli\>)((.+?\</oli\>)\n)(^\<oli\>)#ms","\n\t<ul><li>$3</ul></li>\n<li>",$paragraph);
			//intentar arreglar sublistas --> ol dentro de ul
			$paragraph = preg_replace("#(\</uli\>\n\t\<oli\>)((.+?\</oli\>)\n)(^\<uli\>)#ms","\n\t<ol><li>$3</ol></li>\n<li>",$paragraph);
			//intentar arreglar sublistas --> ul dentro de ol
			$paragraph = preg_replace("#(\</oli\>\n\t\<uli\>)((.+?\</uli\>)\n)(^\<oli\>)#ms","\n\t<ul><li>$3</ul></li>\n<li>",$paragraph);

			//Blockquotes anidados
			while (preg_match("/bq\./",$paragraph)){
				$paragraph = preg_replace('/bq\.\s(.*)/sm', "<blockquote>$1</blockquote>\n",$paragraph);
			}

			$text .= "\n".$paragraph."\n";

		}
    }
	$text = preg_replace("/\<uli\>/","<li>",$text);
	$text = preg_replace("/\<\/uli\>/","</li>",$text);
	$text = preg_replace("/\<oli\>/","<li>",$text);
	$text = preg_replace("/\<\/oli\>/","</li>",$text);
	return ($text);
}


?>
