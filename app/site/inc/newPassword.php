<?php
if (eregi('newPassword.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['submitid']) AND $_POST['submitid'] == 1){

	if(isset($_POST['function']) AND $_POST['function'] == 'newPasswordSetupForm'){
	
		if(!preg_match('/^[a-zA-Z0-9]+$/',$_POST['accPass'])){
			$msg = $txt['newPasswordSetup']['alert2'];
		} else if(strlen($_POST['accPass'])<5 || strlen($_POST['accPass'])>30){
			$msg = $txt['newPasswordSetup']['alert1'];
		} else if($_POST['accPass']!=$_POST['accPassAgain']){
			$msg = $txt['newPasswordSetup']['alert3'];
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
			
			print '<script type="text/javascript">
				<!--
				setTimeout("location.href = \''.$pth['site']['base'].$sl.'/\'",5000);
				-->
			</script>';
			$msg = $txt['newPasswordSetup']['success'];
			
		}
	}

	if(isset($_POST['function']) AND $_POST['function'] == 'newPasswordForm'){ 
	
		$accName = strtolower(trim($_POST['accNameRequest']));
		
		if($_POST['accNameRequest']=='' || $_POST['accNameRequest']!=$cf['acc']['name']) $msg = $txt['newPasswordErr']['accName'];
		else {
			
			clearstatcache();

			// BUILD EMAIL
			include($pth['lib']['phpmailer']);
			$mail = new PHPMailer();
			$mail->IsHTML(false);
			$mail->Priority 		= 1;
			$mail->CharSet 			= 'UTF-8';
			$mail->Hostname			= $_SERVER['HTTP_HOST'];
			$mail->From     		= 'YOUR_EMAIL_ADDRESS';
			$mail->FromName 		= encodeUTF8('( '.$txt['app']['name'].' )');
			$mail->Subject 			= encodeUTF8('( '.$accName.' ) Recuperar Código de Acesso.');
			$mail->Body 			= encodeUTF8('---------------------------------------------------------------------

( '.$txt['app']['name'].' ) '.$pth['app']['www'].'

---------------------------------------------------------------------

Olá ( '.$accName.' )!

Está a receber esta mensagem porque solicitou a recuperação do Código de Acesso da sua conta.

Se não tiver feito este pedido, por favor ***IGNORE*** e apague esta mensagem.
Relate qualquer abuse para '.$txt['app']['email'].'.

Para alterar o seu Código abra o seguinte endereço:

'.$pth['site']['base'].$sl.'/?&newPassword&newPasswordOK&userID='.$accName.'&requestID='.$cf['acc']['password'].'&requestDL='.date("YmdHis").'

Obrigado!

NOTA: Este pedido é válido por 24h.

'.date('Y').' @ Todos os direitos reservados.');

			$mail->AddAddress($cf['acc']['email']);

			// SEND EMAIL
			if(!$mail->Send()) $msg = $txt['newPasswordErr']['mailNotSent'];
			else $msg = $txt['newPasswordErr']['mailSent'];
			unset($_POST);
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			
		}
	
	}

}

if($msg!='' || $msg==TRUE)$o.=msgBox($msg);

$title=$txt['newPassword']['title']; $o.='<h1>'.$title.'</h1>';

if(isset($_GET['newPasswordOK']) AND $msg!=$txt['newPasswordSetup']['success']){
	
	$days = (!isset($_GET['requestDL'])) ? 1 : floor((time() - strtotime($_GET['requestDL']))/86400);
	if($days>=1) $o.= $txt['newPasswordErr']['requestInvalid'];
	else {
		$accName = (isset($_GET['userID'])) ? $_GET['userID'] : ''; 
		if(!isset($_GET['requestID']) OR $_GET['requestID'] != $cf['acc']['password']) { $o.= $txt['newPasswordErr']['requestInvalid']; }
		else $o.='<form action="" method="post" name="newPasswordSetupForm" id="newPasswordSetupForm">
			<p>'.$txt['newPassword']['chgDescription'].'</p>
			<fieldset>
				<legend>'.$txt['newPasswordSetup']['title'].'</legend>
				<p>
				<input type="password" name="accPass" id="accPass" value="" maxlength="30" />
				<label for="accPass">'.$txt['newPasswordSetup']['label'].'</label>
				</p>
				<p>
				<input type="password" name="accPassAgain" id="accPassAgain" value="" maxlength="30" />
				<label for="accPassAgain">'.$txt['newPasswordSetup']['pwAgainLabel'].'</label>
				</p>
				<p class="action"><button type="submit">'.$txt['newPasswordSetup']['accSubmit'].'</button> <a href="'.$pth['site']['base'].$sl.'/">'.$txt['newPassword']['cancel'].'</a></p>
			</fieldset>
			<input type="hidden" name="function" value="newPasswordSetupForm" />
			<input type="hidden" name="submitid" value="1">
		</form>';
	}

} else {

$o.= '<form action="" method="post" name="newPasswordForm" id="newPasswordForm">
<h4>'.$txt['newPassword']['subtitle'].'</h4>
<p>'.$txt['newPassword']['description'].'</p>
<div>
	<label for="accNameRequest">'.$txt['newPassword']['accName'].'</label>
	<input type="text" name="accNameRequest" value="';
	if(isset($_POST['accNameRequest'])) $o.=$_POST['accNameRequest'];
	$o.= '" maxlength="30" />
	<span class="action"><button type="submit">'.$txt['newPassword']['submit'].'</button> <a href="'.$pth['site']['base'].$sl.'/">'.$txt['newPassword']['cancel'].'</a></span>
</div>
<input type="hidden" name="function" value="newPasswordForm"/>
<input type="hidden" name="submitid" value="1" />	
</form>';

$o.= '<h4>'.$txt['newPassword']['suggestionTitle'].'</h4>
<p>'.$txt['newPassword']['suggestionDesc'].'</p>
<ul>
	<li><a href="http://keepass.info/" target="_blank">'.$txt['newPassword']['option1'].'</a></li>
	<li><a href="http://www.roboform.com/" target="_blank">'.$txt['newPassword']['option2'].'</a></li>
</ul>';

}

?>