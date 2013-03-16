<?php
require('inc/controller.php');

ob_end_flush();

function onload(){}

function support(){ searchBaseLocal().getAccount().contactForm(); }

function main(){
	global $pth, $s; include($pth['home']['globals']);
	
	$output = '<h1>'.$txt['list']['title'].'</h1>';
	
	$output.= '<div id="toolbar" class="clearfix">'.lang().tools().'</div>';
	
	if($msg!='')$output.= '<div id="msgBox">'.$msg.'</div>';
	
	$buffer = file($pth['app']['sitemap']);
	$buffer2= simplexml_load_file($pth['app']['sitemap']);
	$count	= 0; $data = array();
	$s1		= '<url><loc>';
	foreach($buffer as $line){ 
		if (substr($line,0,strlen($s1))==$s1){
			$data[] = strtotime($buffer2->url[$count]->lastmod);
			$url[] 	= $buffer2->url[$count]->loc;
			$count++;
		}
	}
	
	arsort($data); $i = 0; $o1 = '';
	foreach ($data as $key => $val) {
		if($i < 3){ $o1.= '<li><a href="'.$url[$key].'"><img alt="'.$url[$key].'" src="http://images.websnapr.com/?size=202x152&key=PUT_HERE_YOUR_OWN_KEY='.$url[$key].'" /></a></li> '; }
		$i++;
	}
	
	$output.= '<div id="listLastUpdated" class="listCards">
	<h2>'.$txt['list']['lastUpdated'].'</h2>
	<ul>'.$o1.'</ul>
	</div>';
	
	$buffer = file($pth['app']['urllist']);
	$i = 0; $o2 = ''; $o3 = '';
	foreach($buffer as $line){
		$i++;
		$data = explode("/",$line);
		if($i > fileCountLines($pth['app']['urllist'])-3){ $o2.= '<li><a title="'.$data[3].'" href="'.$line.'"><img alt="'.$data[3].'" src="http://images.websnapr.com/?size=202x152&key=PUT_HERE_YOUR_OWN_KEY&url='.$line.'" /></a></li> '; }
		if($i!=1) $o3.= '<li><a href="'.$line.'">'.$data[3].'</a></li> &middot; ';
	}
	
	$output.= '<div id="listLastCreated" class="listCards">
	<h2>'.$txt['list']['lastCreated'].'</h2>
	<ul>'.$o2.'</ul>
	</div>';
	
	$output.= '<div id="listAll" class="listCards">
	<h2>'.$txt['list']['all'].fileCountLines($pth['app']['urllist']).'</h2>
	<ul>'.$o3.'</ul>
	</div>';
	
	print $output;
	
}

// print Content
require($pth['home']['structure']);

?>