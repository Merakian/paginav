<?php
if (eregi('contactForm.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

	$f	= (isset($mailform) OR (isset($_POST['function']) AND $_POST['function']=='mailform')) ? 'mailform' : '';
	
	// FORM VALIDATION
	
	if(isset($_POST['function']) AND $_POST['function']=='contactForm' AND isset($_POST['action']) AND $_POST['action']=='send'){

		if($_POST['subject']=='' || $_POST['subject']==$txt['contactForm']['subject'] || strlen($_POST['subject'])<2){
			$e = $txt['contactForm']['subjectAlert'];
		}
		elseif($_POST['message']=='' || $_POST['message']==$txt['contactForm']['message'] || strlen($_POST['message'])<5 || strlen($_POST['message'])>500){
			$e = $txt['contactForm']['messageAlert'];
		} 
		elseif($_POST['name']=='' || $_POST['name']==$txt['contactForm']['name'] || strlen($_POST['name'])<3){
			$e = $txt['contactForm']['nameAlert'];
		}
		elseif (!preg_match('/^[a-zA-Z0-9]{1}\w+([\.-]?\w+)*\+?([\.-]?\w+)*@[a-zA-Z0-9]{1}\w+([\.-]?\w+)*(\.\w{2,4})+$/i',$_POST['email'])){
			$e = $txt['contactForm']['emailAlert'];
		}
		else {
		
			// BUILD EMAIL
			require($pth['lib']['phpmailer']);
			$mail = new PHPMailer();
			$mail->Priority 		= 3;
			$mail->CharSet 			= 'UTF-8';
			$mail->Hostname			= $_SERVER['HTTP_HOST'];
			$mail->FromName 		= $_POST['name'];
			$mail->From     		= $_POST['email'];
			$mail->AddAddress($cf['acc']['email']);
			$mail->ConfirmReadingTo = $cf['acc']['email'];
			$mail->IsHTML(false);
			$mail->Subject 			= 'via '.$txt['app']['name'].' : '.$_POST['subject'];
			$mail->Body 			= $txt['contactForm']['messageIntro'].'

	'.$_POST['message'].'

	---
	'.$_POST['name'].' <'.$_POST['email'].'>
	
	via '.$pth['site']['base'].'

	X-Remote: '.$_SERVER['REMOTE_ADDR'];

			// SEND EMAIL
			if(!$mail->Send()) $e = $txt['contactForm']['notsent'];
			else {
				$e = $txt['contactForm']['sent'];
				unset($_POST);
			}
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		} 
	}
	
	function mailform(){
		global $pth; include($pth['app']['globals']);
		
		$title=$txt['title'][$f];
		$o.= '
		<h2>'.$txt['contactForm']['title'].'</h2>
		<form id="contactForm" class="section" name="contactForm" method="post" action="">
		<fieldset>';
			$o.= '
			<div><label for="subject">'.$txt['contactForm']['subject'].'</label><input name="subject" type="text" maxlength="100" value="'; $o.=(!isset($_POST['subject']) OR $_POST['subject']=='') ? '' : $_POST['subject']; $o.='" /></div>
			<div><label for="message">'.$txt['contactForm']['message'].'</label><textarea name="message" rows="5" cols="20" >'; $o.=(!isset($_POST['message']) OR $_POST['message']=='') ? '' : $_POST['message']; $o.='</textarea></div>
			<div><label for="name">'.$txt['contactForm']['name'].'</label><input name="name" type="text" maxlength="50" value="'; $o.=(!isset($_POST['name']) OR $_POST['name']=='') ? '' : $_POST['name']; $o.='" /></div>
			<div><label for="email">'.$txt['contactForm']['email'].'</label><input name="email" type="text" maxlength="50" value="'; $o.=(!isset($_POST['email']) OR $_POST['email']=='') ? '' : $_POST['email']; $o.='" /></div>
			<div class="actionButtons"><button type="reset">'.$txt['contactForm']['resetButton'].'</button>&nbsp;<button type="submit">'.$txt['contactForm']['sendButton'].'</button></div>
			<input type="hidden" name="function" value="contactForm"/>
			<input type="hidden" name="action" value="send"/>
		</fieldset>
		</form>';
		
		return $o;
	}
	
	if($f=='mailform') mailform();

?>