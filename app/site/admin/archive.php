<?php
if (eregi('archive.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$regexIMG="/\.gif$|\.jpg$|\.jpeg$|\.png$/i";
$regexDOC="/^[^\.]/i";

if(isset($_POST['action']) AND $_POST['action']=='delete'){
	if(@unlink($pth['site']['archive'].$_POST['archive'])) $msg = '<strong>'.$_POST['archive'].'</strong> '.$txt['menuArchive']['deleted'];
	else $msg = $txt['menuArchive']['delError'].' <strong>'.$_POST['archive'].'</strong>';
}

if(isset($_POST['action']) AND $_POST['action']=='upload'){

	$name=$_FILES['archive']['name'];
	$size=$_FILES['archive']['size'];
	
	$cf[$f]['maxsize'] = (preg_match($regexIMG,$name)) ? $cf['archive']['imgMaxSize'] : $cf['archive']['docMaxSize'];
	
	if(!(preg_match($regexDOC,$name)) AND !(preg_match($regexIMG,$name)))$e.='<p>( '.$name.' ) '.$txt['menuArchive']['errInvalidExt'].'</p>';
	else if($cf['disk']['quota']<quotaused()+$size)$e.='<p>'.$txt['menuArchive']['errNoDiskSpace'].'</p>';
	else if(file_exists(rp($pth['site']['archive'].$name)))e('alreadyexists','file','<strong>'.$name.'</strong>');
	else if($size>$cf[$f]['maxsize'])$e.='<p>'.ucfirst($txt['filetype']['file']).' "<strong>'.$name.'</strong>" ('.$size.' '.$txt['files']['bytes'].') '.$txt['error']['tolarge'].' <strong>'.$cf[$f]['maxsize'].'</strong> '.$txt['files']['bytes'].'</p>';
	if(!$e){
		if(@move_uploaded_file($_FILES['archive']['tmp_name'],$pth['site']['archive'].$name)){ 
			$e.='<p>'.ucfirst($txt['filetype']['file']).' <strong>'.$name.'</strong> '.$txt['result']['uploaded'].'</p>';
		} else e('cntsave','file',$name);
	}
}

if($msg!='' || $msg==TRUE)$o.=msgBox($msg);

$title=$txt['menuArchive']['title']; $o.='<h1>'.$title.'</h1>';

if($cf['acc']['type']=='0' AND isset($adm)){ $o.=pubMain('archive'); }

$o.='<form id="formArchiveUpload" method="POST" action="'.$sn.'?&archive" enctype="multipart/form-data"><p>
<input type="file" class="file" name="'.$f.'" size="45"/>
<input type="hidden" name="action" value="upload"/>
<input type="hidden" name="function" value="'.$f.'"/>
<input type="submit" class="submit" value="'.ucfirst($txt['action']['upload']).'"/>
</p></form>';

$o.='<form id="formArchiveList" method="post" action="'.$sn.'?&archive">
<table>';

$quotafree=$cf['disk']['quota']-quotaused();
$totalsize=$cf['disk']['quota']-$quotafree;

$listDOC=''; $listIMG='';

if(@is_dir($pth['site']['archive'])){
	$fs=sortdir($pth['site']['archive']);
	foreach($fs as $p){
		if(preg_match($regexDOC,$p) AND !preg_match($regexIMG,$p)){
			$listDOC.='<tr><td class="action"><input type="radio" class="radio" name="'.$f.'" value="'.$p.'"/></td><td class="listDoc" colspan="2">'.$p.' ('.(round((filesize($pth['site']['archive'].$p))/102.4)/10).' KB)</td></tr>';
		}
		if(preg_match($regexIMG,$p)){
			$listIMG.='<tr><td class="action"><input type="radio" class="radio" name="'.$f.'" value="'.$p.'"/></td><td class="thumbnail"><img src="'.$pth['site']['archive2'].urlencode($p).'"></td><td><p>'.$p.' ('.(round((filesize($pth['site']['archive'].$p))/102.4)/10).' KB)</p>';
			for($i=0; $i<$cl; $i++){
				$ic=preg_match_all('/<img src=["]*([^"]*?)'.'\/'.$p.'["]*(.*?)>/i',$c[$i],$matches,PREG_PATTERN_ORDER);
				if($ic>0) $listIMG.='<p>'.$txt['images']['usedin'].'<br/>» '.a($i,'').$h[$i].'</a></p>';
			}
			$listIMG.='</td></tr>';
		}
	}
	$o.=$listDOC.$listIMG.'</table>
	<input type="hidden" name="action" value="delete"/><input type="hidden" name="function" value="'.$f.'"/>';
	if($totalsize > 0) $o.='<p class="action"><input type="submit" class="submit" value="'.ucfirst($txt['action']['delete']).'"/></p>';
	$o.='</form>';
	$o.='<p id="quotaInfo"><strong>Espaço</strong> ( <strong>Máximo:</strong> '.(round($cf['disk']['quota']/102.4)/10).' KB &middot; <b>Livre:</b> '.(round($quotafree/102.4)/10).' KB &middot; <b>Usado:</b> '.(round($totalsize/102.4)/10).' KB )</p>';
}
else e('cntopen','folder',$pth['site']['archive']);

?>