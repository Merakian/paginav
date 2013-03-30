<?php
if (eregi('login.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['action']) AND $_POST['action'] == 1){

	if(isset($_POST['function']) AND $_POST['function'] == 'loginForm'){ 
		
		if(crypt(htmlspecialchars(trim($_POST['passwd'])),$cf['acc']['password']) == $cf['acc']['password']){
			setcookie("status", "adm");
			setcookie("passwd", $cf['acc']['password']);
			setcookie("MyContentHTMLSize", filesize($pth['site']['content']));
			$adm = TRUE; $edit = TRUE;
			clearstatcache();
			writeLog(date("Y-m-d H:i:s")." from ".sv('REMOTE_ADDR')." logged_in\n");
		} else {
			header('status: 401 Unauthorized');
			$e = $txt['error']['401'];
		}
		
	}
	
}

function writeLog($m){
	global $pth; include($pth['app']['globals']);
	
	if ($fh = @fopen($pth['site']['log'], "ab")){
		fwrite($fh, $m);
		fclose($fh);
	} else {
		e('cntwriteto', 'log', $pth['site']['log']);
		chkfile('log', true);
	}

}

function pubMaisValor(){
	global $pth; include($pth['app']['globals']);
	
	$output = '';
	
	return $output;
}
	
function loginForm(){
	global $pth; include($pth['app']['globals']);
	
	if(isset($login)){
		$cf['meta']['robots']="noindex";
		$onload = ' onLoad="self.focus();document.login.passwd.focus()"';
		$f = $txt['login']['title'];
		$o.= '<h1>'.$txt['login']['title'].'</h1>
			<form id="loginForm" name="loginForm" action="'.$sn.'?&login" method="post">
			<p>
				<strong>'.$txt['login']['label'].'</strong>
				<input type="password" name="passwd" id="passwd" value=""/> <input type="submit" name="submit" id="submit" value="'.$txt['login']['submit'].'"/>
				( <a href="'.$sn.'?&newPassword">'.$txt['login']['lostPassword'].'</a> )
				<input type="hidden" name="action" value="1"/>
				<input type="hidden" name="function" value="loginForm"/>
				<input type="hidden" name="selected" value="'.@$u[$s].'"/>
			</p>
			</form>'.pubMaisValor();
		$s = -1;
	}
}

if(!isset($adm) OR $adm!=TRUE)loginForm();

?>