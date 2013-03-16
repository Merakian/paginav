<?php
if (eregi('lang.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function lang(){
	global $pth, $s; include($pth['home']['globals']);
	
	$output = '<ul class="lang">';
	$files=array(); $l=array(); $cty=array();
	$dir=opendir($pth['home']['locale']);
	while(($file=readdir($dir))==true ){ if(is_file($pth['home']['locale'].$file)) $files[]=$file; }
	if($dir==true)closedir($dir);
	$i=-1;
	foreach($files as $file){ 
		$i++; $l = explode('.',$file); $cty = explode('-',$l[0]);
		$output.= '<li><a href="'.$pth['app']['www'].$l[0].'/"><img src="'.$pth['home']['flag'].strtolower($cty[1]).'.gif" alt="'.$l[0].'" title="'.$txt['lang'][$l[0]].'" /></a></li>';
	}
	$output.= '</ul>'; 
	return $output;
}

?>