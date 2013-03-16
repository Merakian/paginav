<?php
if (eregi('pageFunctions.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function loginlink(){ global $pth; include($pth['app']['globals']); return a($s, amp().'login').$txt['menu']['login'].'</a>'; }

function head(){
	global $pth; include($pth['app']['globals']);
	
	if($cf['site'][$sl]['title']!='') $t=decodeUTF8($cf['site'][$sl]['title']).' &middot; '.$title;
	else $t=$title;

	$output = '
	<meta http-equiv="cache-control" content="public" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="content-type" content="text/html; charset='.$txt['meta']['codepage'].'" />
	<meta http-equiv="content-language" content="'.$sl.'" />
	<meta name="robots" content="all" />
	<meta name="author" content="'.$txt['meta']['author'].'" />
	<meta name="generator" content="'.$txt['meta']['generator'].'" />
	<meta name="copyright" content="'.$txt['meta']['copyright'].'" />
	<meta name="title" content="'.decodeUTF8($cf['site'][$sl]['title']).'" />
	<meta name="description" content="'.decodeUTF8($cf['site'][$sl]['description']).'" />
	<meta name="keywords" content="'.decodeUTF8($cf['site'][$sl]['keywords']).'" />
	<title>'.$t.'</title>
	<base target="_self" href="'.$pth['site']['base'].'"/>
	<link rel="P3Pv1" href="'.$pth['app']['p3p'].'" />
	<link rel="alternate" type="application/atom+xml" title="'.$txt['app']['name'].' { '.$t.' } FEED" href="'.$pth['site']['feed'].'" />
	<link href="'.$pth['site']['stylesheet'].'" rel="stylesheet" type="text/css" />';
	if($cf['site']['layout']!='') $output.= '<link href="'.$pth['site']['layout'].'" rel="stylesheet" type="text/css" />';
	if(is_file($pth['site']['customTags'])) $output.= siteCustomStyle();
	$output.= '<script src="'.$pth['app']['js'].'master.js'.'" type="text/javascript"></script>
	<script src="'.$pth['admin']['js'].'master.js'.'" type="text/javascript"></script>
	'.$hjs;
	
	return $output;
	
}
function onload(){ global $pth; include($pth['app']['globals']); return $onload;}

function content(){
	global $pth; include($pth['app']['globals']);
	if(!(isset($edit) AND isset($adm) AND $adm==TRUE) AND $s>-1)return $o.preg_replace("/\#CODIGOV:.*\#/is","",$c[$s]);
	else return $o;
}

function sitename(){ global $pth; include($pth['app']['globals']); return '<div id="avatar" class="sitename"><a href="'.$pth['site']['base'].$sl.'/">'.decodeUTF8($cf['site'][$sl]['title']).'</a></div>'; }
function avatar(){
	global $pth; include($pth['app']['globals']);
	
	clearstatcache();
	
	if(is_file($pth['site']['root'].'avatar.gif')) $extension = 'gif';
	if(is_file($pth['site']['root'].'avatar.png')) $extension = 'png';
	if(is_file($pth['site']['root'].'avatar.jpg')) $extension = 'jpg';
	if(is_file($pth['site']['root'].'avatar.jpeg')) $extension = 'jpeg';
	
	if(!is_file($pth['site']['root'].'avatar.'.$extension)) return sitename();
	else return '<div id="avatar"><a title="'.decodeUTF8($cf['site'][$sl]['title']).'" href="'.$pth['site']['base'].$sl.'/"><img src="'.$pth['site']['base'].'avatar.'.$extension.'" alt="'.decodeUTF8($cf['site'][$sl]['title']).'" /></a></div>';
}

function toc($start,$end){ 
	global $pth; include($pth['app']['globals']); 
	
	$ta=array(); 
	if(isset($start) AND !isset($end)) $end=$start;
	if(!isset($end))$end=3;	if(!isset($start))$start=1;
	
	for($i=0;$i<$hl;$i++){ 
		if($l[$hc[$i]]==1){ if($start==1)$ta[]=$hc[$i]; $r1=$r2=$i;	}
		if($s==$hc[$i]){
			$s3=true;
			for($j=$r1+1;$j<$hl;$j++){ 
				if($l[$hc[$j]]==1)break;
				if($l[$hc[$j]]==2){ if($start<3&&$end>1)$ta[]=$hc[$j]; $r2=$j; $s3=false; }
				if($s==$hc[$j]){ 
					for($k=$r2+1;$k<$hl;$k++){
						if($l[$hc[$k]]==3){	if($end>2)$ta[]=$hc[$k]; } else { $s3=false;break; }
					}
				}
				if($l[$hc[$j]]==3&&$l[$s]==1&&$s3) if($end>2)$ta[]=$hc[$j];
			}
		}
	}
	
	$t=''; $st=$start; $st='menulevel'; $j=0; $lf[0]=$lf[1]=$lf[2]=$lf[3]=false;
	
	for($i=0; $i<$hl; $i++){
		if(!isset($ta[$j])) break; if($hc[$i]!=$ta[$j]) continue;
		$tf=($s!=$ta[$j]);
		$t.='<ul>
			<li class="'.$st.$l[$ta[$j]].' '; if(!$tf)$t.='s'; $t.='doc';
			if(isset($hc[$i+1])) if($l[$hc[$i+1]]>$l[$ta[$j]]) $t.='s';
			$t.='">'.a($ta[$j],'').$h[$ta[$j]].'</a></li>
		</ul>';
		$j++;
	}
	
	return $t;
	
}

function locator(){
	global $pth; include($pth['app']['globals']);
	
	if(!($edit AND $adm) AND preg_match("/\#CODIGOV:RASCUNHO\#/is",$c[$s]))return $h[$s];
	if($title!='' AND $h[$s]!=$title) return $title;
	$t='';
	$tl=$l[$s];
	if($tl>1){
		for($i=$s-1;$i>0 ;$i--){
			if($l[$i]<$tl){
				$t=a($i,'').$h[$i].'</a> &rsaquo; '.$t;$tl--;
			}
			if($tl<2)break;
		}
	}
	if($s==$hc[0])return $h[$s];
	else if($s>0)return a($hc[0],'').$h[$hc[0]].'</a> &rsaquo; '.$t.$h[$s];
	else if($f!='')return ucfirst($f);
	else return '&nbsp;';
}
function submenu(){ global $pth; include($pth['app']['globals']); $ta=array();if(!($l[$s]==1||$l[$s]==2))return;$s3=true;for($i=$s+1;$i<$cl;$i++){if($l[$i]==1)break;if($l[$i]==2){if($l[$s]!=1)break;else{$ta[]=$i;$s3=false;}}if($l[$i]==3&&$s3)$ta[]=$i;}if(count($ta)!=0)return '<h4>'.$txt['submenu']['heading'].'</h4>'.li($ta,'submenu'); }
function previouspage(){ global $pth; include($pth['app']['globals']); if(isset($hc[$hs-1]))return a($hc[$hs-1],'').$txt['navigator']['previous'].'</a>'; }
function nextpage(){ global $pth; include($pth['app']['globals']); if(isset($hc[$hs+1])&&$hs>-1)return a($hc[$hs+1],'').$txt['navigator']['next'].'</a>'; }
function top(){	global $pth; include($pth['app']['globals']); return '<a href="javascript:window.scrollTo(0,0)">'.$txt['navigator']['top'].'</a>'; }

function languagemenu(){
	global $pth; include($pth['app']['globals']); 
	$t='';
	$r=array();
	$fd=@opendir($pth['site']['root']);
	while(($p=@readdir($fd))==true ){
		if(@is_file($pth['site']['root'].$p)){
			if(preg_match('/^content\.[a-zA-Z-]{2,6}\.xml$/',$p)){
				$p = explode(".",$p);
				$r[]=$p[1];
			}
		}
	}
	if($fd==true)closedir($fd);
	if(count($r)<=1)return '';

	$v=count($r);
	for($i=0;$i<$v;$i++){
		if($sl!=$r[$i]){
			if(is_file($pth['app']['flags'].'/'.$r[$i].'.gif')) $t.='<a href="'.$pth['site']['base'].$r[$i].'/"><img src="'.$pth['app']['flags2'].$r[$i].'.gif" alt="'.$r[$i].'" class="flag"></a> ';
			else $t.='<a href="'.$pth['site']['base'].$r[$i].'/">['.$r[$i].']]</a> ';
		}
    }
    return ''.$t.'';
}

function searchbox(){
	global $pth; include($pth['app']['globals']);
	
	return
	'<form action="'.$sn.'" method="post">
		<input type="text" name="search" size="11"/>
		<input type="submit" value="'.$txt['search']['button'].'"/>
		<input type="hidden" name="function" value="search"/>
	</form>';
}
function mailformlink(){ global $pth; include($pth['app']['globals']); if($cf['acc']['email']!='')return ml('mailform'); }

function lastupdate(){ global $pth; include($pth['app']['globals']); return '<p>'.$txt['lastupdate']['text'].':<br/>'.date($txt['lastupdate']['dateformat'], filemtime($pth['site']['content'])).'</p>'; }

function pubMain($temp){
	global $pth; include($pth['app']['globals']);
	
	if($cf['acc']['type']=='0' && $adm){
		$output = '<p id="pubMain" class="">';
			if($temp=='archive') $output.= $txt['goPro']['pubArchive'];
		$output.= '</p>';
		
		return $output;
	}
	
}
function pubSidebar(){
	global $pth; include($pth['app']['globals']);
	
	if($cf['acc']['type']=='0'){
		$output = '<div class="section" id="pubSidebar">';
		if($adm) $output.= '<p><a href="'.$sn.'?'.amp().'goPro">'.$txt['goPro']['pubSidebar'].'</a></p>';
		$output.= '<div><a title="[ PUBLICIDADE ]" href="http://www.baselocal.com/showcase"><img src="'.$pth['app']['media2'].'pub.gif" alt="[ PUBLICIDADE ]" /></a></div></div>';
		return $output;
	}
	
}

function footer(){
	global $pth; include($pth['app']['globals']);
	
	$output = $txt['footer']['txt'];	
	return $output;
	
}

function extra(){
	return GAnalytics();
}
function GAnalytics(){
return '<!-- GOOGLE ANALYTICS -->
<!-- GOOGLE ANALYTICS -->
';
}

?>