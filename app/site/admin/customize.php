<?php
if (eregi('customize.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['action']) AND $_POST['action'] == 1){

	if($_POST['function'] == 'formEditMeta'){
			
		if(mb_strlen($_POST['metaTitle'])>100){
			$msg = $txt['menuCustom']['metaTitleError'];
		} else if(mb_strlen($_POST['metaDescription'])>500){
			$msg = $txt['menuCustom']['metaDescriptionError'];
		} else if(mb_strlen($_POST['metaKeywords'])>255){
			$msg = $txt['menuCustom']['metaKeywordsError1'];
		} else if(!preg_match('/^[\w\d\s,-]+$/u',decodeUTF8($_POST['metaKeywords']))){
			$msg = $txt['menuCustom']['metaKeywordsError2'];
		} else {
			clearstatcache();
			
			$buffer	= file($pth['srv']['data']);
			$handle	= fopen($pth['srv']['data'],"wb");
						
			$s1		= '$cf[\'site\'][\''.$sl.'\'][\'title\']';
			$r1		= '$cf[\'site\'][\''.$sl.'\'][\'title\']=\''.encodeUTF8(clean($_POST['metaTitle'],ENT_QUOTES)).'\';';
			$s2		= '$cf[\'site\'][\''.$sl.'\'][\'description\']';
			$r2		= '$cf[\'site\'][\''.$sl.'\'][\'description\']=\''.encodeUTF8(clean($_POST['metaDescription'],ENT_QUOTES)).'\';';
			$r2		= preg_replace('#\n|\r#','',$r2);
			$s3		= '$cf[\'site\'][\''.$sl.'\'][\'keywords\']';
			$r3		= '$cf[\'site\'][\''.$sl.'\'][\'keywords\']=\''.encodeUTF8(clean($_POST['metaKeywords'],ENT_QUOTES)).'\';';
			$r3		= preg_replace('#\n|\r#','',$r3);
			
			foreach($buffer as $line){
				if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
				if (substr($line,0,strlen($s2))==$s2){ $line = substr_replace($line,$r2,0,-1); }
				if (substr($line,0,strlen($s3))==$s3){ $line = substr_replace($line,$r3,0,-1); }
				fwrite($handle,$line);
			}

			fclose($handle);
			
			// REPORT UPDATE TO SITEMAP
		
			$buffer	= file($pth['app']['sitemap']);
			$handle	= fopen($pth['app']['sitemap'],"wb");
			
			$s1		= '<url><loc>'.$pth['site']['base'];
			$r1		= '<url><loc>'.$pth['site']['base'].'</loc><lastmod>'.date(DATE_ATOM).'</lastmod></url>';
			
			foreach($buffer as $line){
				if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
				fwrite($handle,$line);
			}
			
			fclose($handle);
			
			$msg = $txt['menuCustom']['metaSuccess'];
			unset($_POST);
			include($pth['srv']['data']);
			
		}
	
	}

	if(isset($_POST['function']) AND $_POST['function'] == 'formCustomLayout'){
	
		$buffer	= file($pth['srv']['data']);
		$handle	= fopen($pth['srv']['data'],"wb");
					
		$s1		= '$cf[\'site\'][\'layout\']';
		$r1		= '$cf[\'site\'][\'layout\']=\''.$_POST['layout'].'\';';
	
		foreach($buffer as $line){
			if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
			fwrite($handle,$line);
		}
		
		fclose($handle);
		
		$cf['site']['layout'] = $_POST['layout'];
		
		$msg = $txt['menuCustom']['layoutSuccess'];
		
	}
	
	if(isset($_POST['function']) AND $_POST['function'] == 'formCustomStyle'){
	
		function validateRGB($var){
			global $pth; include($pth['app']['globals']);
			if(!preg_match('/^rgb\([0-9]{1,3},[0-9]{1,3},[0-9]{1,3}\)$/i',$var)) return FALSE;
			else return TRUE;
		}

		function validateHEX($var){
			global $pth; include($pth['app']['globals']);
			if(!preg_match('/^#[0-9a-f]{3,6}$/i',$var)) return FALSE;
			else return TRUE;
		}

		function validateHTMLColors($var){
			global $pth; include($pth['app']['globals']);
			if($var == 'aqua' || $var == 'black' || $var == 'blue' || $var == 'fuchsia' || $var == 'gray' || $var == 'green' || $var == 'lime' || $var == 'maroon' || $var == 'navy' || $var == 'olive' || $var == 'purple' || $var == 'red' || $var == 'silver' || $var == 'teal' || $var == 'white' || $var == 'yellow') return TRUE;
			else return FALSE;
		}
	
		$i=0;
		while ((isset($_POST[$i.'_Bg']) AND $_POST[$i.'_Bg']==TRUE) OR (isset($_POST[$i.'_Bd']) AND $_POST[$i.'_Bd']==TRUE) OR (isset($_POST[$i.'_Ft']) AND $_POST[$i.'_Ft']==TRUE)){
			$varBg = (isset($_POST[$i.'_Bg'])) ? strtolower(trim($_POST[$i.'_Bg'])) : '';
			$varBd = (isset($_POST[$i.'_Bd'])) ? strtolower(trim($_POST[$i.'_Bd'])) : '';
			$varFt = (isset($_POST[$i.'_Ft'])) ? strtolower(trim($_POST[$i.'_Ft'])) : '';
			if($varBg != 'transparent' && $varBg != 'inherit' && validateRGB($varBg) != TRUE && validateHEX($varBg) != TRUE && validateHTMLColors($varBg) != TRUE) $_POST[$i.'_Bg']='inherit';
			if($varBd != 'transparent' && $varBd != 'inherit' && validateRGB($varBd) != TRUE && validateHEX($varBd) != TRUE && validateHTMLColors($varBd) != TRUE) $_POST[$i.'_Bd']='inherit';
			if($varFt != 'transparent' && $varFt != 'inherit' && validateRGB($varFt) != TRUE && validateHEX($varFt) != TRUE && validateHTMLColors($varFt) != TRUE) $_POST[$i.'_Ft']='inherit';
			$i++;
		}
		
		$output="<?php \n";
		$i=0;
		$handle	= fopen($pth['site']['customTags'],"rb");
		while (($customTags = fgetcsv($handle, 1000, ",")) !== FALSE){
			$tmpBg = (isset($_POST[$i.'_Bg'])) ? $_POST[$i.'_Bg'] : '';
			$tmpBd = (isset($_POST[$i.'_Bd'])) ? $_POST[$i.'_Bd'] : '';
			$tmpFt = (isset($_POST[$i.'_Ft'])) ? $_POST[$i.'_Ft'] : '';
			$output.='$cf[\'style\'][\''.$customTags[0].'\']=array("'.strtolower(trim($tmpBg)).'","'.strtolower(trim($tmpBd)).'","'.strtolower(trim($tmpFt)).'");'."\n";
			$i++;
		}
		fclose($handle);
		$output.="?>";
		
		$handle	= fopen($pth['site']['customStyle'],'wb');
		fwrite($handle,$output);
		fclose($handle);

		clearstatcache();
				
		$msg = $txt['menuCustom']['styleSuccess'];
		
	}
	
	if(isset($_POST['function']) AND $_POST['function'] == 'formCustomAvatar'){
	
		if(isset($_POST['REMOVE']) AND $_POST['REMOVE']=='1'){
			
			$fd=@opendir($pth['site']['root']);
			while(($p=@readdir($fd))==true ){
				if(@is_file($pth['site']['root'].$p)){
					if(preg_match('/^avatar\.(gif|jpe?g|png)$/',$p)){
						unlink($pth['site']['root'].$p);
					}
				}
			}
			if($fd==true)closedir($fd);
			$msg = $txt['menuCustom']['avatarRemoved'];
		
		} else {
	
			$file	= strtolower($_FILES['avatarUpload']['name']);
			$ext 	= pathinfo($file);
			
			if($ext['extension']=='gif' || $ext['extension']=='jpg' || $ext['extension']=='jpeg' || $ext['extension']=='png'){
			
				if($_FILES['avatarUpload']['size']>$cf['avatar']['size'] || $_FILES['avatarUpload']['size']==0){ $msg = $txt['menuCustom']['avatarErrSize']; }
				else {
				
					clearstatcache();
					$uploadDir 	= $pth['site']['root'];
					$uploadFile = $uploadDir.'avatar.'.$ext['extension'];
					
					if(move_uploaded_file($_FILES['avatarUpload']['tmp_name'], $uploadFile)){
					
						clearstatcache();

						// FIX: get a better code | THEY should only be removed after saving the new one.
						if(is_file($uploadDir.'avatar.gif') && ($uploadDir.'avatar.gif'!=$uploadFile)) unlink($uploadDir.'avatar.gif');
						if(is_file($uploadDir.'avatar.jpg') && ($uploadDir.'avatar.jpg'!=$uploadFile)) unlink($uploadDir.'avatar.jpg');
						if(is_file($uploadDir.'avatar.jpeg') && ($uploadDir.'avatar.jpeg'!=$uploadFile)) unlink($uploadDir.'avatar.jpeg');
						if(is_file($uploadDir.'avatar.png') && ($uploadDir.'avatar.png'!=$uploadFile)) unlink($uploadDir.'avatar.png');
						
						$msg = $txt['menuCustom']['avatarSuccess'];
						
					} else $msg = $txt['menuCustom']['avatarErrMove'];
					
				}
				
			} else { $msg = $txt['menuCustom']['avatarErrType']; }
		
		}
	
	}
	
	if(isset($_POST['function']) AND $_POST['function'] == 'formCustomIntro'){
	
		if(isset($_POST['REMOVE']) AND $_POST['REMOVE']=='1'){
			
			$fd=@opendir($pth['site']['root']);
			while(($p=@readdir($fd))==true ){
				if(@is_file($pth['site']['root'].$p)){
					if(preg_match('/^intro\.(gif|jpe?g|png|swf)$/',$p)){
						unlink($pth['site']['root'].$p);
					}
				}
			}
			if($fd==true)closedir($fd);
			$msg = $txt['menuCustom']['introRemoved'];
		
		} else {
	
			$file	= strtolower($_FILES['introUpload']['name']);
			$ext 	= pathinfo($file);
			
			if($ext['extension']=='gif' || $ext['extension']=='jpg' || $ext['extension']=='jpeg' || $ext['extension']=='png' || $ext['extension']=='swf'){
			
				clearstatcache();
				if($_FILES['introUpload']['size']>$cf['intro']['size'] || $_FILES['introUpload']['size']==0){ $msg = $txt['menuCustom']['introErrSize']; }
				else {
				
					clearstatcache();
					$uploadDir 	= $pth['site']['root'];
					$uploadFile = $uploadDir.'intro.'.$ext['extension'];
					
					if(move_uploaded_file($_FILES['introUpload']['tmp_name'], $uploadFile)){
					
						clearstatcache();

						// FIX: get a better code | THEY should only be removed after saving the new one.
						if(is_file($uploadDir.'intro.gif') && ($uploadDir.'intro.gif'!=$uploadFile)) unlink($uploadDir.'intro.gif');
						if(is_file($uploadDir.'intro.jpg') && ($uploadDir.'intro.jpg'!=$uploadFile)) unlink($uploadDir.'intro.jpg');
						if(is_file($uploadDir.'intro.jpeg') && ($uploadDir.'intro.jpeg'!=$uploadFile)) unlink($uploadDir.'intro.jpeg');
						if(is_file($uploadDir.'intro.png') && ($uploadDir.'intro.png'!=$uploadFile)) unlink($uploadDir.'intro.png');
						if(is_file($uploadDir.'intro.swf') && ($uploadDir.'intro.swf'!=$uploadFile)) unlink($uploadDir.'intro.swf');
						
						$msg = $txt['menuCustom']['introSuccess'];
						
					} else $msg = $txt['menuCustom']['introErrMove'];
					
				}
				
			} else { $msg = $txt['menuCustom']['introErrType']; }
		
		}
	
	}

}

function menuCustomMeta(){
	global $pth; include($pth['app']['globals']);
	
	$charMax='500'; $output = '';
	
	$output.= '<div id="menuCustomMeta">
	
	<h4>'.$txt['menuCustom']['metaLabel'].'</h4>
	<p>'.$txt['menuCustom']['metaIntro'].'</p>
	<form action="'.$_SERVER["SCRIPT_URI"].'?'.$_SERVER["QUERY_STRING"].'" method="post" name="formEditMeta" id="formEditMeta">
	<div>
		<label for="metaTitle">'.$txt['menuCustom']['metaTitle'].'</label>
		<input type="text" name="metaTitle" value="'; $output.=(empty($_POST['metaTitle'])) ? decodeUTF8($cf['site'][$sl]['title']) : clean($_POST['metaTitle']); $output.='" maxlength="100" />
	</div>
	<div id="metaDescription">
		<label for="metaDescription">'.$txt['menuCustom']['metaDescription'].'</label>
		<textarea name="metaDescription" cols="50" rows="5" onfocus="contador(this,formEditMeta.countChars);" onkeyup="contador(this,formEditMeta.countChars);">'; $output.=(empty($_POST['metaDescription'])) ? decodeUTF8($cf['site'][$sl]['description']) : clean($_POST['metaDescription']); $output.='</textarea>
		<input class="countChars" name="countChars" value="'.$charMax.'" disabled="disabled" />
	</div>
	<div>
		<label for="metaKeywords">'.$txt['menuCustom']['metaKeywords'].'</label>
		<input type="text" name="metaKeywords" value="'; $output.=(empty($_POST['metaKeywords'])) ? decodeUTF8($cf['site'][$sl]['keywords']) : $_POST['metaKeywords']; $output.='" maxlength="255" />
	</div>
	<p class="action"><button type="reset">'.$txt['menuCustom']['metaCancel'].'</button>&nbsp;<button type="submit">'.$txt['menuCustom']['metaSubmit'].'</button></p>
	<input type="hidden" name="function" id="function" value="formEditMeta" />
	<input type="hidden" name="action" id="action" value="1">
	</form>
	
	</div>';
	
	return $output;
	
}

function menuCustomLayout(){
	global $pth; include($pth['app']['globals']);
	
	$output = '<!-- SECTION: LAYOUT -->
		<div id="menuCustomLayout">		
		<h4>'.$txt['menuCustom']['layoutTitle'].'</h4>
		<form action="'.$_SERVER["SCRIPT_URI"].'?'.$_SERVER["QUERY_STRING"].'" method="post" name="formCustomLayout" id="formCustomLayout">
		<fieldset>
			<ul>
				<li>
					<input ';
					if($cf['site']['layout']=="2-columns-left")$output.='checked="checked" ';				
					$output.='type="radio" name="layout" id="2-columns-left" value="2-columns-left" />
					<img src="'.$pth['admin']['image'].'2-columns-left.png" title="" alt="" />
				</li>
				<li>
					<img src="'.$pth['admin']['image'].'2-columns-right.png" title="" alt="AAAA" />
					<input ';
					if($cf['site']['layout']=="2-columns-right")$output.='checked="checked" ';
					$output.='type="radio" name="layout" id="2-columns-right" value="2-columns-right" />
				</li>
			</ul>
			<p class="action"><button type="submit">'.$txt['menuCustom']['layoutSubmit'].'</button></p>
			<input type="hidden" name="function" id="function" value="formCustomLayout" />
			<input type="hidden" name="action" id="action" value="1">
		</fieldset>
		</form>
		</div>';
		
	return $output;
}

function menuCustomStyle(){
	global $pth; include($pth['app']['globals']);
	
	if(!is_file($pth['site']['customTags'])) return;
	
	$output='<div id="menuCustomStyle">
	<h4>'.$txt['menuCustom']['styleTitle'].'</h4>
	<p>'.$txt['menuCustom']['styleIntro'].'</p>';
	
	include('colorPallete.php');

	$output.='<form action="'.$_SERVER["SCRIPT_URI"].'?'.$_SERVER["QUERY_STRING"].'" method="post" name="formCustomStyle" id="formCustomStyle">
	<table>
	<thead>
	<tr>
	<th>'.$txt['menuCustom']['styleTag'].'</td>
	<th>'.$txt['menuCustom']['styleBg'].'</td>
	<th>'.$txt['menuCustom']['styleBd'].'</td>
	<th>'.$txt['menuCustom']['styleFt'].'</td>
	</tr>
	</thead><tbody>';
	
	// Get values of tags
	if(is_file($pth['site']['customStyle'])) include($pth['site']['customStyle']);
	
	$i=0;
	$handle	= fopen($pth['site']['customTags'],"rb");
	while (($customTags = fgetcsv($handle, 1000, ",")) !== FALSE){
		$output.='<tr>
		<td class="label">'.$customTags['1'].'</td><td>'; 
		if($customTags['2']=='1'){ $output.='<input type="text" name="'.$i.'_Bg" value="'; if(isset($cf['style'][$customTags['0']][0])) $output.=$cf['style'][$customTags['0']][0]; $output.= '" size="15"/>'; }
		$output.='</td><td>';
		if($customTags['3']=='1'){ $output.='<input type="text" name="'.$i.'_Bd" value="'; if(isset($cf['style'][$customTags['0']][1])) $output.=$cf['style'][$customTags['0']][1]; $output.= '" size="15"/>'; }
		$output.='</td><td>';
		if($customTags['4']=='1'){ $output.='<input type="text" name="'.$i.'_Ft" value="'; if(isset($cf['style'][$customTags['0']][2])) $output.=$cf['style'][$customTags['0']][2]; $output.= '" size="15"/>'; }
		$output.='</td></tr>';
		$i++;
	}
	fclose($handle);
	
	$output.='</tbody></table>
		<p class="action"><button type="submit">'.$txt['menuCustom']['styleSubmit'].'</button></p>
		<input type="hidden" name="function" id="function" value="formCustomStyle" />
		<input type="hidden" name="action" id="action" value="1">
	</form>';
	
	$output.='</div>';
	
	return $output;
			
}

function menuCustomAvatar(){
	global $pth; include($pth['app']['globals']);
	
	$output = '';
	
	// FIX: better code to detect avatar
	$isAvatar=FALSE;
	if(is_file($pth['site']['root'].'avatar.gif')){ $isAvatar=TRUE; $avatar = 'avatar.gif'; }
	if(is_file($pth['site']['root'].'avatar.jpg')){ $isAvatar=TRUE; $avatar = 'avatar.jpg'; }
	if(is_file($pth['site']['root'].'avatar.jpeg')){ $isAvatar=TRUE; $avatar = 'avatar.jpeg'; }
	if(is_file($pth['site']['root'].'avatar.png')){ $isAvatar=TRUE; $avatar = 'avatar.png'; }

	clearstatcache();
	
	$output.= '<!-- SECTION: AVATAR UPLOAD -->
		<div id="menuCustomAvatar">
		<h4>'.$txt['menuCustom']['avatarTitle'].'</h4>
		<form action="'.$_SERVER["SCRIPT_URI"].'?'.$_SERVER["QUERY_STRING"].'" method="post" enctype="multipart/form-data" name="formCustomAvatar" id="formCustomAvatar">
			<fieldset>
			<p>'.$txt['menuCustom']['avatarIntro'].'</p>
			<input type="hidden" name="MAX_FILE_SIZE" value="'.$cf['avatar']['size'].'" />';
			if($isAvatar==TRUE) $output.= '<div><img src="'.$pth['site']['base'].$avatar.'" title="" alt="" /></div><p><button type="submit" name="REMOVE" value="1" onClick="return confirm(\''.$txt['menuCustom']['avatarRemoveConfirm'].'\');">'.$txt['menuCustom']['avatarRemove'].'</button></p><input type="hidden" name="REMOVE" id="REMOVE" value="1">';
			$output.= '<p><input size="50" accept="image/png, image/gif, image/jpg, image/jpeg" type="file" name="avatarUpload" id="avatarUpload" />&nbsp;<button type="submit">'.$txt['menuCustom']['avatarSubmit'].'</button></p>
			</fieldset>
			<input type="hidden" name="function" id="function" value="formCustomAvatar" />
			<input type="hidden" name="action" id="action" value="1">
		</form>
		</div>';
		
		return $output;
}

function menuCustomIntro(){
	global $pth; include($pth['app']['globals']);
	
	$output = '';
	
	// FIX: better code to detect INTRO
	$isAvatar=FALSE;
	if(is_file($pth['site']['root'].'intro.gif')){ $isIntro=TRUE; $intro = 'intro.gif'; }
	if(is_file($pth['site']['root'].'intro.jpg')){ $isIntro=TRUE; $intro = 'intro.jpg'; }
	if(is_file($pth['site']['root'].'intro.jpeg')){ $isIntro=TRUE; $intro = 'intro.jpeg'; }
	if(is_file($pth['site']['root'].'intro.png')){ $isIntro=TRUE; $intro = 'intro.png'; }
	if(is_file($pth['site']['root'].'intro.swf')){ $isIntro=TRUE; $intro = 'intro.swf'; }

	clearstatcache();
	
	$output.= '<!-- SECTION: INTRO UPLOAD -->
		<div id="menuCustomIntro">
		<h4>'.$txt['menuCustom']['introTitle'].'</h4>
		<form action="'.$_SERVER["SCRIPT_URI"].'?'.$_SERVER["QUERY_STRING"].'" method="post" enctype="multipart/form-data" name="formCustomIntro" id="formCustomIntro">
			<fieldset>
			<p>'.$txt['menuCustom']['introIntro'].'</p>
			<input type="hidden" name="MAX_FILE_SIZE" value="'.$cf['intro']['size'].'" />';
			if($isIntro==TRUE) $output.= '<p class="destak">( '.$intro.' ) '.$txt['menuCustom']['introIsSet'].'</p><p><button type="submit" name="REMOVE" value="1" onClick="return confirm(\''.$txt['menuCustom']['introRemoveConfirm'].'\');">'.$txt['menuCustom']['introRemove'].'</button></p><input type="hidden" name="REMOVE" id="REMOVE" value="1">';
			$output.= '<p><input size="50" accept="application/x-shockwave-flash, image/gif, image/jpg, image/jpeg, image/png" type="file" name="introUpload" id="introUpload" />&nbsp;<button type="submit">'.$txt['menuCustom']['introSubmit'].'</button></p>
			</fieldset>
			<input type="hidden" name="function" id="function" value="formCustomIntro" />
			<input type="hidden" name="action" id="action" value="1">
		</form>
		</div>';
		
		return $output;
}

if($msg!='' || $msg==TRUE)$o.=msgBox($msg);

$title=$txt['menuCustom']['title']; $o.='<h1>'.$title.'</h1>';
		
$o.='<div id="menuCustom">'.menuCustomStyle().menuCustomLayout().menuCustomAvatar().menuCustomIntro().menuCustomMeta().'</div>';

?>