<?php
if (eregi('globar.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function globar(){ 
	global $pth, $s; include($pth['home']['globals']);
	
	$output = '<ul class="left">
		<li class="logo">'.$txt['globar']['title'].'</li>
	</ul>
	<ul class="right"></ul>';
	
	print $output;
	
}

?>