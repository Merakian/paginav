<?php
if (eregi('head.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function head(){
	global $pth; include($pth['home']['globals']);
	
	$output = '
	<meta http-equiv="cache-control" content="public" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="content-language" content="'.$cf['acc']['lang'].'" />
	<meta name="robots" content="all" />
	<meta name="author" content="" />
	<meta name="copyright" content="'.date('Y').' &copy; '.$txt['home']['copyright'].'" />
	<meta name="description" content="'.$txt['home']['description'].'" />
	<meta name="keywords" content="'.$txt['home']['keywords'].'" />
	<title>'.$txt['home']['title'].'</title>
	<base href="'.$pth['app']['www'].'" />
	<link rel="P3Pv1" href="'.$pth['app']['p3p'].'" />
	<link href="'.HTTP($pth['home']['style']).'" rel="stylesheet" type="text/css" />
	<link href="'.HTTP($pth['home']['view'].'print.css').'" rel="stylesheet" type="text/css" media="print" />
	<script src="'.HTTP($pth['home']['js'].'master.js').'" type="text/javascript"></script>
	<link rel="alternate" type="application/atom+xml" title="'.$txt['app']['platform'].'" href="'.$pth['app']['feed'].'" />
	';
	
	print $output;
	
}
?>