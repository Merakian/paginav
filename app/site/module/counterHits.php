<?php
if (eregi('counterHits.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

// count Hits, use cookies for validation, validate save...
function counterHits(){
	global $pth; include($pth['app']['globals']);
	$count 		= '';
	$counterHits= $pth['site']['counterHits'];
	clearstatcache();
	if(!is_file($counterHits)){ $handle	= fopen($counterHits,'w'); fwrite($handle,'0'); fclose($handle); }
	// get current Hits + count
	$count	= file_get_contents($counterHits);
	if(!$adm){
		$count  = $count+1;
		// save Hits
		$files = fopen($counterHits,'w'); 
		fwrite($files,$count);
		fclose($files);
	}
	
	return '<p><strong>'.$count.'</strong> '.$txt['counterHits']['views'].'.</p>';
}

?>