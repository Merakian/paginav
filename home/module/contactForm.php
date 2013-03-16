<?php
if (eregi('contactForm.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

	// FORM VALIDATION
	if(isset($_POST['function']) AND $_POST['function']=='contactForm' AND isset($_POST['action']) AND $_POST['action']=='send'){

		if($_POST['subject']=='' || $_POST['subject']==$txt['contactForm']['subject'] || strlen($_POST['subject'])<2){
			$txt['contactForm']['alert']=$txt['contactForm']['subjectAlert'];
		}
		elseif($_POST['message']=='' || $_POST['message']==$txt['contactForm']['message'] || strlen($_POST['message'])<5 || strlen($_POST['message'])>500){
			$txt['contactForm']['alert']=$txt['contactForm']['messageAlert'];
		} 
		elseif($_POST['name']=='' || $_POST['name']==$txt['contactForm']['name'] || strlen($_POST['name'])<3){
			$txt['contactForm']['alert']=$txt['contactForm']['nameAlert'];
		}
		elseif (!preg_match('/^[a-zA-Z0-9]{1}\w+([\.-]?\w+)*\+?([\.-]?\w+)*@[a-zA-Z0-9]{1}\w+([\.-]?\w+)*(\.\w{2,4})+$/i',$_POST['email'])){
			$txt['contactForm']['alert']=$txt['contactForm']['emailAlert'];
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
			$mail->AddAddress($txt['app']['email']);
			$mail->ConfirmReadingTo = $txt['app']['email'];
			$mail->IsHTML(false);
			$mail->Subject 			= 'via '.$txt['app']['platform'].' : '.$_POST['subject'];
			$mail->Body 			= $txt['contactForm']['messageIntro'].'

	'.$_POST['message'].'

	---
	'.$_POST['name'].' <'.$_POST['email'].'>

	X-Remote: '.$_SERVER['REMOTE_ADDR'];

			// SEND EMAIL
			if(!$mail->Send()) $txt['contactForm']['alert']=$txt['contactForm']['notsent'];
			else {
				$txt['contactForm']['alert']=$txt['contactForm']['sent'];
				unset($_POST);
			}
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		} 
	}

	function contactForm(){
		global $pth; include($pth['home']['globals']);
		
		// BUILD FORM
		$output = '
		<form id="contactForm" class="section" name="contactForm" method="post" action="">
		<h2>'.$txt['contactForm']['title'].'</h2>
		<fieldset>';
			if($txt['contactForm']['alert']!='') $output.= '<p class="alert">'.$txt['contactForm']['alert'].'</p>';
			$output.= '
			<div><input name="subject" type="text" maxlength="100" value="'; $output.=(!isset($_POST['subject']) OR $_POST['subject']=='') ? $txt['contactForm']['subject'] : $_POST['subject']; $output.='" /></div>
			<div><textarea name="message" rows="5" cols="20" >'; $output.=(!isset($_POST['message']) OR $_POST['message']=='') ? $txt['contactForm']['message'] : $_POST['message']; $output.='</textarea></div>
			<div><input name="name" type="text" maxlength="50" value="'; $output.=(!isset($_POST['name']) OR $_POST['name']=='') ? $txt['contactForm']['name'] : $_POST['name']; $output.='" /></div>
			<div><input name="email" type="text" maxlength="50" value="'; $output.=(!isset($_POST['email']) OR $_POST['email']=='') ? $txt['contactForm']['email'] : $_POST['email']; $output.='" /></div>
			<div class="actionButtons"><button type="reset">'.$txt['contactForm']['resetButton'].'</button>&nbsp;<button type="submit">'.$txt['contactForm']['sendButton'].'</button></div>
			<input type="hidden" name="function" value="contactForm"/>
			<input type="hidden" name="action" value="send"/>
		</fieldset>
		</form>';
		print $output;
		
	}

?>