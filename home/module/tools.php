<?php
if (eregi('tools.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function tools(){
	global $pth; include($pth['home']['globals']);
	$output = '
	<ul class="tools"><li><a href="'.$pth['app']['www'].'"><img src="'.$pth['home']['icon'].'home.png" alt="[H]" title="'.$txt['tool']['home'].'"/></a></li><li><a href="'.$pth['app']['feed'].'"><img src="'.$pth['home']['icon'].'feed.png" alt="[F]" title="'.$txt['tool']['feed'].'"/></a></li><li><a href="javascript:setBookmark(document.title,self.location.href)"><img src="'.$pth['home']['icon'].'bookmark.png" alt="[B]" title="'.$txt['tool']['bookmark'].'"/></a></li><li><a href="javascript:window.print()"><img src="'.$pth['home']['icon'].'print.png" alt="[P]" title="'.$txt['tool']['print'].'"/></a></li></ul>';
	return $output;
}

?>