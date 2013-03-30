<?php
if (eregi('logout.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

clearstatcache();
if(filesize($pth['site']['content'])!=$_COOKIE["MyContentHTMLSize"]){
	
	// BACKUP CONTENT.HTML
	$fn = 'content.'.$sl.'.'.date("YmdHis").'.xml';
	
	if (@copy($pth['site']['content'], $pth['site']['root'].$fn)) {
		$o .= '<p>'.ucfirst($txt['filetype']['backup']).' <strong>'.$fn.'</strong> '.$txt['result']['created'].'</p>';
		$fl = array();
		$fd = @opendir($pth['site']['root']);
		while (($p = @readdir($fd)) == true) {
			if (preg_match("/\d{3}\.xml/", $p))$fl[] = $p;
		}
		if ($fd == true)closedir($fd);
		
		@sort($fl, SORT_STRING);
		$v = count($fl)-$cf['backup']['numberoffiles'];
		for($i = 0; $i < $v; $i++) {
			if (@unlink($pth['site']['root'].$fl[$i]))$o .= '<p>'.ucfirst($txt['filetype']['backup']).' <strong>'.$fl[$i].'</strong> '.$txt['result']['deleted'].'</p>';
			else e('cntdelete', 'backup', $fl[$i]);
		}
	}
	else e('cntsave', 'backup', $fn);
	
	// REPORT UPDATE TO SITEMAP						
	$buffer	= file($pth['app']['sitemap']);
	$handle	= fopen($pth['app']['sitemap'],"wb");
	
	$s1		= '<url><loc>'.$pth['app']['www'].$cf['acc']['name'].'/'.$sl.'/</loc>';
	$r1		= '<url><loc>'.$pth['app']['www'].$cf['acc']['name'].'/'.$sl.'/</loc><lastmod>'.date(DATE_ATOM).'</lastmod></url>';
	
	foreach($buffer as $line){
		if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
		fwrite($handle,$line);
	}
	
	fclose($handle);
	
}

$adm = FALSE;
setcookie('MyContentHTMLSize', '');
setcookie('status', '');
setcookie('passwd', '');
$o .= '<p>'.$txt['logout']['info'].'</p>';

?>