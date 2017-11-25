<?php

$menu="
		<ul>
		<li><a href=\"".ROOT."\">inicio</a></li>
		<li><a href=\"".ROOT."nueva/\">nueva entrada</a></li>
		<li><a href=\"".ROOT."index/\">índice</a></li>
		<li><a href=\"".ROOT."help/\">ayuda</a></li>
		</ul>";

$base=basename($_SERVER['REQUEST_URI']);
if (DEBUG_VIS == 1) {
  debug_add ("***menu.inc***\nBASE: $base\nROOT: ".ROOT."\n");
}
$menu=preg_replace("|<li><a href=\"/.*".$base."/\">(.*)</a></li>|U", "<li class=\"iscurrent\">$1</li>", $menu);
echo $menu;
?>
