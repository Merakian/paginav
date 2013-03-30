<?php
if (eregi('intro.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

	$embed = '';
	
	if($pth['site']['introINFO']['extension']=='swf'){ 
		$embed = '<object>
			<param name="movie" value="'.$pth['site']['introURL'].'" />
			<param name="bgcolor" value="#000000" />
			<embed src="'.$pth['site']['introURL'].'" bgcolor="#000000" pluginspage="http://www.macromedia.com/go/getflashplayer"/>
		</object>'; }
	else $embed = '<img src="'.$pth['site']['introURL'].'" />';

	print '<html>
	<head>
		<title>Página Virtual : O Espaço.NET 24/7 de ( '.$accName.' ).</title>
		<style>
			*		{ background-color: #000; color: #fff; font-family: Verdana, Arial, Sans-Serif; font-size: 10pt; }
			a		{ font-variant: small-caps; text-decoration: none; }
			object, param,
			embed	{ width: 100%; height: 90%; }
		</style>
	</head>
	<body>
		<div id="intro" class="clearfix" style="text-align: center">
		<p><a href="'.$pth['site']['base'].$cf['site']['startLang'].'/">Saltar Apresentação &middot; Skip Intro</a></p>
		<a href="'.$pth['site']['base'].$cf['site']['startLang'].'/">
		<div>'.$embed.'</div>
		</a>
		<p><a href="'.$pth['site']['base'].$cf['site']['startLang'].'/">Saltar Apresentação &middot; Skip Intro</a></p>
		</div>
	</body>
	</html>';


?>