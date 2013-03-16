<?php
if (eregi('globar.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function pubGAdsense(){
	global $pth; include($pth['app']['globals']);
	
	$output = '';
	
	if($cf['acc']['type']=='0'){
		$output.= '<div id="pubGAdsense" class="clearfix">
			Include Google Adsense Code HERE
		</div>';
	}
	
	return $output;
	
}

function globar(){
	global $pth; include($pth['app']['globals']);

	$output = '<div id="globar" class="clearfix">
	<ul class="left"><li class="logo">&lsaquo; Página Virtual <sup>lab</sup> &rsaquo;</li></ul>
	<ul class="right">
		<li class="login">'.loginlink().'</li>
	</ul>';
	
	$output.= pubGAdsense();
	
	print $output.'</div>';
	
}

?>