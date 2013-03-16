<?php
if (eregi('tools.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function sitemaplink(){ 
	global $pth; include($pth['app']['globals']);
	return '<a href="'.$sn.'?'.amp().'sitemap"><img src="'.$pth['app']['icon2'].'sitemap.png" alt="[P]" title="'.$txt['tool']['sitemap'].'"/></a>';
}
function printlink(){
	global $pth; include($pth['app']['globals']);
	$t=amp().'print';
	if($f=='search')$t.=amp().'function=search'.amp().'search='.$search;
	else if($f=='file')$t.=amp().'file='.$file;
	else if($f!=''&&$f!='save')$t.=amp().$f;
	return '<a href="'.$sn.'?'.$su.$t.'"><img src="'.$pth['app']['icon2'].'print.png" alt="[P]" title="'.$txt['tool']['print'].'"/></a>';
}

function tools(){
	global $pth; include($pth['app']['globals']);
	$output = '
	<ul>
		<li><a href="'.$pth['site']['base'].$sl.'/"><img src="'.$pth['app']['icon2'].'home.png" alt="[H]" title="'.$txt['tool']['home'].'"/></a></li>
		<li>'.sitemaplink().'</li>
		<li>'.printlink().'</li>
		<li><a href="javascript:setBookmark(document.title,self.location.href)"><img src="'.$pth['app']['icon2'].'bookmark.png" alt="[B]" title="'.$txt['tool']['bookmark'].'"/></a></li>
		<li><a href="'.$pth['site']['feed'].'"><img src="'.$pth['app']['icon2'].'feed.png" alt="[F]" title="'.$txt['tool']['feed'].'"/></a></li>
	</ul>';
	return $output;
}

?>