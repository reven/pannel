<?php

$menu=<<<HERE
		<ul>
		<li><a href="$root">inicio</a></li>
		<li><a href="{$root}nueva/">nueva entrada</a></li>
		<li><a href="{$root}index/">índice</a></li>
		</ul>

HERE;

$base=basename($_SERVER['REQUEST_URI']); 
//echo ("<p class=\"debug\">BASE: $base<br />ROOT: $root</p>");
$menu=preg_replace("|<li><a href=\"/hq/.*".$base."/\">(.*)</a></li>|U", "<li class=\"iscurrent\">$1</li>", $menu);
echo $menu;
?>
