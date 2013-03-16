<?php
require('inc/controller.php');

ob_end_flush();

function onload(){}

function support(){ contactForm(); }

function main(){
	global $pth, $s; include($pth['home']['globals']);
	
	$output = '<h1>'.$txt['intro']['title'].'</h1>';
	
	$output.= '<div id="toolbar" class="clearfix">'.lang().tools().'</div>';
	
	if($msg!='')$output.= '<div id="msgBox">'.$msg.'</div>';
	
	$countRef	= 0;
	$handle		= opendir($pth['data']['fan']);
	while(($file=readdir($handle))==TRUE){
		if(is_file($pth['data']['fan'].$file)){
			$buffer	= file($pth['data']['fan'].$file);
			foreach($buffer as $line){ $countRef++; }
		}
	}
	if($handle==TRUE)closedir($handle);
	
	$buffer = file($pth['app']['sitemap']);
	$buffer2= simplexml_load_file($pth['app']['sitemap']);
	$i		= 0; $data = array();
	$s1		= '<url><loc>';
	foreach($buffer as $line){
		if (substr($line,0,strlen($s1))==$s1){
			$data[] = strtotime($buffer2->url[$i]->lastmod);
			$url[] 	= $buffer2->url[$i]->loc;
			$i++;
		}
	}
	
	arsort($data); $i = 0; $o = '';
	foreach ($data as $key => $val) {
		if($i < 3){ $o.= '<li><a title="'.$url[$key].'" href="'.$url[$key].'"><img alt="'.$url[$key].'" src="http://images.websnapr.com/?size=202x152&key=PUT_HERE_YOUR_OWN_KEY&url='.$url[$key].'" /></a></li> '; }
		$i++;
	}
	
	$output.= '<div id="introBase">
	
	<h2 class="tisep">'.$txt['intro']['base'].'</h2>
	
	<div id="listLastUpdated" class="listCards">
	<p><strong>'.fileCountLines($pth['app']['urllist']).'</strong> '.$txt['intro']['stats1'].' <strong>'.$countRef.'</strong> '.$txt['intro']['stats2'].'</p>
	<ul>'.$o.'</ul>
	</div>';
	
	$output.= '<h3>'.$txt['intro']['baseDesc'].'</h3>
	<ul>
		<li>'.$txt['intro']['baseDesc1'].'</li>
		<li>'.$txt['intro']['baseDesc9'].'</li>
		<li>'.$txt['intro']['baseDesc7'].'</li>
		<li>'.$txt['intro']['baseDesc3'].'</li>
		<li>'.$txt['intro']['baseDesc2'].'</li>
		<li>'.$txt['intro']['baseDesc6'].'</li>
		<li>'.$txt['intro']['baseDesc5'].'</li>
		<li>'.$txt['intro']['baseDesc4'].'</li>
		<li>'.$txt['intro']['baseDesc8'].'</li>
	</ul>
	<p class="footnote">'.$txt['intro']['baseFootnote1'].'</p>';

	$output.= encodeUTF8('
	
	<!-- ++Begin Dynamic Feed Wizard Generated Code++ -->
	<!-- Created with a Google AJAX Search and Feed Wizard || http://code.google.com/apis/ajaxsearch/wizards.html -->

	<!-- The Following div element will end up holding the actual feed control. You can place this anywhere on your page. -->
	<div id="feed-control">A carregar...</div>

	<!-- Google Ajax Api -->
	<script src="http://www.google.com/jsapi?key=ABQIAAAAKFt9do7boUDjzfV2LRno5RRCrxtG7KM1XTzB1ajVw4m1TkFGWRSkT4qgVBzLmz_WhTAWuP87EPEnBA" type="text/javascript"></script>

	<!-- Dynamic Feed Control and Stylesheet -->
	<script src="http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.js" type="text/javascript"></script>
	<style type="text/css">
		@import url("http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.css");
		.gfg-root 		{ margin: 0; padding: 0; }
		.gfg-title		{ background-color: #00f; color: #fff; }
		.gfg-title a	{ color: #fff; }
		.gfg-title, 
		.gfg-subtitle 	{ margin: 0; padding: 10px; }
		.gfg-subtitle 	{ border-top: 1px solid; }
		.gfg-entry 		{ margin: 0 0 10px; padding: 0 0 10px; }
		.gfg-list 		{ margin: 0; padding: 0 0 10px; }
	</style>

	<script type="text/javascript">
		function LoadDynamicFeedControl(){
			var feeds = [ 
				{title: \'Página Virtual .INFO\', url: \'http://blog.paginav.com/feeds/posts/default\'}
			];
			var options = { stacked : true, horizontal : false, title : "" }
			new GFdynamicFeedControl(feeds, \'feed-control\', options);
		}
	
		// Load the feeds API and set the onload callback.
		google.load(\'feeds\', \'1\');
		google.setOnLoadCallback(LoadDynamicFeedControl);
	</script>
	<!-- ++End Dynamic Feed Control Wizard Generated Code++ -->');
		
	$output.= '</div>';
	
	print $output;
	
}

// print Content
require($pth['home']['structure']);

?>