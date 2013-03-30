<?php	/*	SUPPORT FUNCTIONS	*/
if (eregi('functions.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function HTTP($path)	{ return str_replace($_SERVER['DOCUMENT_ROOT'],'http://'.$_SERVER['HTTP_HOST'],$path); }
function ROOT($path)	{ return str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$path); }

function decodeUTF8($item){ return mb_convert_encoding($item,'','UTF-8'); }
function encodeUTF8($item){ return mb_convert_encoding($item,'UTF-8'); }

function clean($file){ return stripslashes(htmlspecialchars($file,ENT_QUOTES)); }
function cleanTags($file){ return stripslashes(htmlspecialchars(strip_tags($file),ENT_QUOTES)); }

function phpPing($url){
	$handle=parse_url($url);
	$host = ($handle['scheme']=='') ? $handle['path'] : $handle['host'];
	exec("ping -w 1 $host", $list);
	if(count($list)>=2){ return true; }
	return false;
}

function pingGoogleSitemaps($url_xml){
	$status = 0;
	$google = 'www.google.com';
	if( $fp=@fsockopen($google, 80) ){
		$req =  'GET /webmasters/sitemaps/ping?sitemap='.urlencode( $url_xml )." HTTP/1.1\r\n"."Host: $google\r\n"."Connection: Close\r\n\r\n";
		fwrite( $fp, $req );
		while( !feof($fp) ){ if( @preg_match('~^HTTP/\d\.\d (\d+)~i', fgets($fp, 128), $m) ){ $status = intval( $m[1] ); break; } }
		fclose( $fp );
	}
	return( $status );
}

function fileCountLines($this){
	global $pth; include($pth['home']['globals']);
	
	$buffer = file($this);
	$count 	= 0; foreach($buffer as $line){ $count++; }
	return $count;
	
}

?>