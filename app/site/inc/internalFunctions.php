<?php
if (eregi('internalFunctions.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function rfc(){		// read file content
	global $pth; include($pth['app']['globals']);
	
    $c=array(); $h=array(); $u=array(); $l=array(); $hc=array();
    $c=explode('§',preg_replace("/(<h[1-3][^>]*>)/i","§\\1",str_replace('§','&#167;',rf($pth['site']['content']))));
	
	$docstart='';
    if(h(0)=='')$docstart.=array_shift($c);
    if(count($c)==0)$c[0]='<h1>'.$txt['toc']['newpage'].'</h1>';
    if(!preg_match("/^<h1[^>]*>.*<\/h1>/is",$c[0])) $c[0]='<h1>'.$txt['toc']['missing'].'</h1>'.$c[0];
    $c[count($c)-1]=preg_replace("/<\/div><\/content>.*<\/feed>/i","\\1",$c[count($c)-1]);
    
	if(!($edit AND $adm)){ foreach($c as $i=>$j){ if(preg_match("/\#CODIGOV:RASCUNHO\#/is",$j)) unset($c[$i]); } $c=array_values($c); }
    
	$duplicate=1; $empty=1; $l1=''; $l2=''; $s=-1; $hs=-1; 
	$cl=count($c);
	
    for($i=0;$i<$cl;$i++){$h[$i]=h($i);$l[$i]=l($i);
    if($h[$i]==''){$h[$i]=$txt['toc']['empty'].' '.$empty++;$u[$i]=$h[$i];}
    if($l[$i]==1){$l1=uenc($h[$i]);$u[$i]=$l1;}
    if($l[$i]==2){$l2=uenc($h[$i]);$u[$i]=$l1.$cf['uri']['seperator'].$l2;}
    if($l[$i]==3){$u[$i]=$l1.$cf['uri']['seperator'].$l2.$cf['uri']['seperator'].uenc($h[$i]);}$w=count($u)-1;
    for($j=0;$j<$w;$j++){
		if($u[$i]==$u[$j]){
			$h[$i]=$txt['toc']['dupl'].' '.$duplicate++;$u[$i]=uenc($h[$i]);
		}
	}
    
	if(($edit AND $adm) OR (!preg_match("/\#CODIGOV:OCULTAR\#/is",$c[$i]))){ $hc[]=$i; if($su==$u[$i]) $hs=count($hc)-1; }
	
	if($su==$u[$i])$s=$i;}$hl=count($hc);
	
}

function siteCustomStyle(){
	global $pth; include($pth['app']['globals']);
	
	$tmp='';
	if(is_file($pth['site']['customStyle'])){
		include($pth['site']['customStyle']);
		while ($key = current($cf['style'])){
			$tag=key($cf['style']);
			$tmp.=$tag.' { ';
			$tmp.=($cf['style'][$tag][0]=='') ? '' : 'background-color: '.$cf['style'][$tag][0].'; ';
			$tmp.=($cf['style'][$tag][1]=='') ? 'border: none; ' : 'border-color: '.$cf['style'][$tag][1].'; ';
			$tmp.=($cf['style'][$tag][2]=='') ? '' : 'color: '.$cf['style'][$tag][2].'; ';
			$tmp.="} \n";
			next($cf['style']);
		}
	}
	return '
	<style type="text/css">'."\n\n".$tmp.'
	</style>
	';
}

// ERROR
function e($et,$ft,$fn){ global $pth; include($pth['app']['globals']); $e='<li>'.$txt['error'][$et].' '.$txt['filetype'][$ft].' '.$fn.'</li>'; }
function msgBox($temp){ 
	global $pth; include($pth['app']['globals']); 
	return '<div id="msgBox"><h1>'.$txt['heading']['warning'].'</h1><p>'.$temp.'</p></div>';
}

// CONTENT SUPPORT
function logincheck(){ global $pth; include($pth['app']['globals']); return (gc('passwd') == $cf['acc']['password']); }

// SHORTCUTS
function sv($s){ global $_SERVER; return $_SERVER[$s]; }
function gc($s){ if(isset($_COOKIE[$s]))return $_COOKIE[$s]; }

// MISC UTILS
function rmnl($text){ global $pth; include($pth['app']['globals']); return preg_replace("/(\r\n|\r|\n)+/","",$text); }

function h($n){ global $pth; include($pth['app']['globals']); return trim(strip_tags(preg_replace("/(<h[1-3][^>]*>([^§]*?)<\/h[1-3]>)?[^¶]*/i","\\2",$c[$n])));}
function l($n){ global $pth; include($pth['app']['globals']); if(isset($c[$n]))return preg_replace("/<h([1-3])[^>]*>[^§]*/i","\\1",$c[$n]);else return 0; }
function a($i,$x){ global $pth; include($pth['app']['globals']); return '<a href="'.$sn.'?'.$u[$i].$x.'">';}

// Menu Link
function ml($i){ global $pth; include($pth['app']['globals']); $t=''; if($f!=$i) $t.='<a href="'.$sn.'?'.amp().$i.'">'; $t.=$txt['menu'][$i]; if($f!=$i)$t.='</a>'; return $t;}

// File, Folder and URL Related Functions
function uenc($s){ return str_replace('+','_',urlencode($s)); }
function rp($p){ if(@realpath($p)=='')return $p; else return realpath($p); }
function rf($fl){	// read file
	$fl = @rp($fl);
	if (!file_exists($fl))return;
	clearstatcache();
	if (function_exists('file_get_contents'))return file_get_contents($fl);
	else { return join("\n", file($fl)); }
}
function chkfile($fl,$writable){ global $pth; include($pth['app']['globals']); $t=@rp($pth['file'][$fl]);if($t=='')e('undefined','file',$fl);else if(!file_exists($t))e('missing',$fl,$t);else if(!is_readable($t))e('notreadable',$fl,$t);else if(!is_writable($t)&&$writable)e('notwritable',$fl,$t); }
function sortdir($dir){ 
	$fs=array();
	$fd=@opendir($dir);
	while(false!==($fn=@readdir($fd))){ $fs[]=$fn; }
	if($fd==true)closedir($fd);
	@sort($fs,SORT_STRING);
	return $fs;
}
function quotaused(){
	global $pth; include($pth['app']['globals']);
    
	$size 	= 0;
	$path 	= $pth['site']['archive'];
    $handle = opendir($path) or die("Unable to open $path");
    while ($file = readdir($handle)){
        if($file == "." || $file == "..") continue;
        if (is_file($path.$file)){
            $size += filesize($path.$file);
        }
    }
    closedir($handle);
	
    return $size;
}

function download($fl){	
	global $pth; include($pth['app']['globals']); 
	if(!is_readable($fl)||($download!='' && !chkdl($sn.'?download='.basename($fl)))){
		global $o, $text_title; 
		header('status: 404 Not Found');
		$o='<p>Ficheiro '.$fl.' não encontrado.</p>'.$o; return; 
	} else { 
		header('Content-Type: application/save-as');
		header('Content-Disposition: attachment; filename="'.basename($fl).'"');
		header('Content-Length:'.filesize($fl));
		header('Content-Transfer-Encoding: binary');
		if($fh=@fopen($fl,"rb")){ while(!feof($fh))print fread($fh,filesize($fl)); fclose($fh); }
		exit; 
	} 
}
function chkdl($fl){ global $pth; include($pth['app']['globals']); $m=false;if(@is_dir($pth['site']['archive'])){$fd=@opendir($pth['site']['archive']);while(($p=@readdir($fd))==true){if(preg_match("/.+\..+$/",$p)){if($fl==$sn.'?download='.$p)$m=true;}}if($fd==true)closedir($fd);}return $m; }

// For valid XHTML
function amp(){ global $pth; include($pth['app']['globals']); if($cf['xhtml']['amp']=='true')return '&amp;'; else return('&'); }

function HTTP($path){ return str_replace($_SERVER['DOCUMENT_ROOT'],'http://'.$_SERVER['HTTP_HOST'],$path); }
function ROOT($path){ return str_replace('http://'.$_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],$path); }

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

function li($ta,$st){
	global $pth; include($pth['app']['globals']);
	
	if(count($ta)==0)return;
	
	$t='';
	
	if($st=='submenu'||$st=='search')$t.='<ul class="'.$st.'">';
	
	$b=0; if($st==1||$st==2||$st==3) $b=$st-1; $j=0; $le=0; $lf[0]=$lf[1]=$lf[2]=$lf[3]=false;
	for($i=0;$i<$hl;$i++){
		if(!isset($ta[$j]))break;
		if($hc[$i]!=$ta[$j])continue;
		$tf=($s!=$ta[$j]);
		$t.='<li class="'; if(!$tf)$t.='s'; $t.='doc'; if(isset($hc[$i+1]))if($l[$hc[$i+1]]>$l[$ta[$j]])$t.='s';$t.='">';
		if($tf) $t.=a($ta[$j],''); $t.=$h[$ta[$j]]; if($tf)$t.='</a>';
		else $t.='</li>';
		$j++;
	}
	
	if($st=='submenu'||$st=='search')$t.='</ul>';
	
	return $t;
	
}

?>