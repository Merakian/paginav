<?php
if (eregi('manage.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['action']) AND $_POST['action'] == 1){

	if($_POST['function'] == 'formSetupAccEmail'){
		
		if(!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/i',trim($_POST['accEmail']))){
			$msg = $txt['menuManage']['accEmailAlert1'];
		} else {
	
			$buffer	= file($pth['acc']['data']);
			$handle	= fopen($pth['acc']['data'],"wb");
			
			$s1		= '$cf[\'acc\'][\'email\']';
			$r1		= '$cf[\'acc\'][\'email\']="'.trim($_POST['accEmail']).'";';
			
			foreach($buffer as $line){
				if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
				fwrite($handle,$line);
			}
			
			fclose($handle);
			$msg = $txt['menuManage']['accEmailSuccess'];
		}
	}

	if($_POST['function'] == 'formSetupAccPass'){
		if(!preg_match('/^[a-zA-Z0-9]+$/',$_POST['accPass'])){
			$msg = $txt['menuManage']['accPassAlert2'];
		} else if(strlen($_POST['accPass'])<5 || strlen($_POST['accPass'])>30){
			$msg = $txt['menuManage']['accPassAlert1'];
		} else if($_POST['accPass']!=$_POST['accPassAgain']){
			$msg = $txt['menuManage']['accPassAlert3'];
		} else {
		
			$buffer	= file($pth['acc']['data']);
			$handle	= fopen($pth['acc']['data'],"wb");
			
			$s1		= '$cf[\'acc\'][\'password\']';
			$r1		= '$cf[\'acc\'][\'password\']=\''.crypt($_POST['accPass']).'\';';
			
			foreach($buffer as $line){
				if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0,-1); }
				fwrite($handle,$line);
			}
			
			fclose($handle);
			$msg = $txt['menuManage']['accPassSuccess'];
		}
	}
	
}

function menuFanatics(){
	global $pth; include($pth['app']['globals']);
	
	$countRef = 0;
	if(is_file($pth['fan']['data'])){
		$buffer	= file($pth['fan']['data']);
		foreach($buffer as $line) $countRef++;
	}
	
	$output = '<div id="menuFan">
	<h4>'.$txt['menuFan']['title'].'</h4>
	<p class="destak">( <strong>'.$countRef.'</strong> )&nbsp;'.$txt['menuFan']['txt1'].'</p>
	<fieldset>
		<legend>'.$txt['menuFan']['txt2'].'</legend>
		<p>'.$txt['menuFan']['txt3'].'</p>
		<ul>
			<li>'.$txt['menuFan']['opt1'].'</li>
		</ul>
		<p>'.$txt['menuFan']['txt4'].'</p>
		<p>'.$txt['menuFan']['txt5'].'</p>
	</fieldset>
	</div>';
	
	return $output;

}

function menuAccType(){
	global $pth; include($pth['app']['globals']);
	
	$output = '<div id="menuAccType">
	<h4>'.$txt['menuManage']['accTypeTitle'].'</h4>';
	
	$output.= ($cf['acc']['type']=='1') ? '<p>'.$txt['menuManage']['accTypeTxtPRO'].'</p>' : '<p class="destak">'.$txt['menuManage']['accTypeTxtBASE'].'</p>';
	
	return '</div>'.$output;
	
}

function menuAccData(){
	global $pth; include($pth['app']['globals']);
	
	$accEmail 	= (isset($_POST['accEmail'])) ? $_POST['accEmail'] : $cf['acc']['email'];
	
	$output = '<div id="menuManageAccData">
	
	<h4>'.$txt['menuManage']['accDataTitle'].'</h4>
	
	<form action="" method="post" name="formSetupAccEmail" id="formSetupAccEmail">
		<fieldset>
			<legend>'.$txt['menuManage']['accEmailTitle'].'</legend>
			<p>
				<input type="text" name="accEmail" id="accEmail" value="'.$accEmail.'" maxlength="50" />
				<label for="accEmail">'.$txt['menuManage']['accEmailLabel'].'</label>
			</p>
			<p class="action"><button type="submit">'.$txt['menuManage']['accSubmit'].'</button></p>
		</fieldset>
		<input type="hidden" name="function" id="function" value="formSetupAccEmail" />
		<input type="hidden" name="action" id="action" value="1">
	</form>
	
	<form action="" method="post" name="formSetupAccPass" id="formSetupAccPass">
		<fieldset>
			<legend>'.$txt['menuManage']['accPassTitle'].'</legend>
			<p>
				<input type="password" name="accPass" id="accPass" value="" maxlength="30" />
				<label for="accPass">'.$txt['menuManage']['accPassLabel'].'</label>
			<br/>
				<input type="password" name="accPassAgain" id="accPassAgain" value="" maxlength="30" />
				<label for="accPassAgain">'.$txt['menuManage']['accPassAgainLabel'].'</label>
			</p>
			<p class="action"><button type="submit">'.$txt['menuManage']['accSubmit'].'</button></p>
		</fieldset>
		<input type="hidden" name="function" id="function" value="formSetupAccPass" />
		<input type="hidden" name="action" id="action" value="1">
	</form>	
	
	</div>';
	
	return $output;

}

if($msg!='' || $msg==TRUE)$o.=msgBox($msg);

$title=$txt['menuManage']['title']; $o.='<h1>'.$title.'</h1>';

$o.='<div id="menuManage">'.menuFanatics().menuAccType().menuAccData().'</div>';

?>