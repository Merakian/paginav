<?php
if (eregi('codigov.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }
	
if(!(isset($edit) AND isset($adm) AND $adm==TRUE) AND isset($s) AND $s>-1){
	
	$t=preg_replace("/^.*\#CODIGOV:(.*)\#.*$/is", "\\1" , $c[$s]);
	
	if ($t != '' AND $t != $c[$s] AND $t != 'RASCUNHO' AND $t != 'OCULTAR'){
		
		// #CODIGOV:ABRIR(URL)#
		if(preg_match('/^ABRIR\(.+\)$/ius',cleanTags($t))){ $tmp=explode('(',preg_replace("/\)/is", "", $t)); header('Location: http://'.trim($tmp[1])); exit; }
	
	}
	
}

?>