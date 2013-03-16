<?php
if (eregi('footer.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function footer(){
	global $pth, $s; include($pth['home']['globals']);
	$output = '<p class="copyright"><a href="YOUR_URL">YOUR_NAME</a> '.date('Y').' &copy; '.$txt['app']['copyright'].' &middot <a href="'.$pth['app']['privacy'].'">'.$txt['app']['privacy'].'</a> &middot <a href="'.$pth['app']['tos'].'">'.$txt['app']['tos'].'</a></p><p class="gotop"><a href="javascript:window.scrollTo(0,0)">'.$txt['app']['gotop'].'</a></p>';
	print $output;
}
?>