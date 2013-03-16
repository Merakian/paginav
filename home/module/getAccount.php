<?php
if (eregi('getAccount.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

function isCharValid(){
	global $pth; include($pth['home']['globals']);
	
	clearstatcache();
	if ($_POST['getAccRefID']=='YOUR_USER_NAME' AND trim($_POST['getAccEmail'])=='YOUR_EMAIL_ADDRESS'){
		if(!preg_match('/^[a-zA-Z0-9-_\.]*$/i',$account)) return TRUE;
	} else {
		if(!preg_match('/^[a-zA-Z0-9]*$/i',$account)) return TRUE;
	}
	
	return FALSE;
	
}

function isAccount(){
	global $pth; include($pth['home']['globals']);
	
	clearstatcache();
	if (is_dir($pth['site']['root'])) return TRUE;
	
	if ($_POST['getAccRefID']!='YOUR_USER_NAME' || trim($_POST['getAccEmail'])!='YOUR_EMAIL_ADDRESS'){
		
		$buffer = curl_init();
		curl_setopt ($buffer, CURLOPT_URL, $pth['acc']['reserved']);
		curl_setopt ($buffer, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($buffer, CURLOPT_CONNECTTIMEOUT, 5);
		$tmpContent = nl2br(cleanTags(curl_exec($buffer)));
		curl_close($buffer);
		$tmpContent = explode("\n",cleanTags($tmpContent));
		foreach($tmpContent as $line){ if(preg_match('/'.$account.'/i',trim($line))) return TRUE; }
		foreach (file($pth['acc']['reservedAlt']) as $line){ if(trim($line)==$account) return TRUE; }
	
	}
		
	return FALSE;
	
}

if(isset($_POST['submitid']) AND $_POST['submitid'] == 1){

	if(isset($_POST['function']) AND $_POST['function']=='getAccount'){ 
	
		$accName = '';
	
		$accName = strtolower(trim($_POST['getAccRefID']));
		$pth['site']['root']	= $pth['acc']['root'].$accName.'/';
		$refID	= (is_dir($pth['site']['root'])) ? strtolower(trim($_POST['getAccRefID'])) : '';
		
		$account = strtolower(trim($_POST['getAccName']));
		$accName = $account;
		$pth['site']['root']	= $pth['acc']['root'].$accName.'/';
		
		if(mb_strlen(utf8_decode($account))<5 || mb_strlen(utf8_decode($account))>30){
			$txt['getAccount']['alert']=$txt['getAccount']['alert1'];
		} elseif(isCharValid()){
			$txt['getAccount']['alert']=$txt['getAccount']['alert2'];
		} elseif(isAccount()){
			$txt['getAccount']['alert']=$txt['getAccount']['alert3'];
		} elseif (!preg_match('/^[a-zA-Z0-9]{1}\w+([\.-]?\w+)*\+?([\.-]?\w+)*@[a-zA-Z0-9]{1}\w+([\.-]?\w+)*(\.\w{2,4})+$/i',trim($_POST['getAccEmail']))){
			$txt['getAccount']['alert']=$txt['getAccount']['alert4'];
		} else {
			clearstatcache();

			$password	= mt_rand(100000,999999);
			$date		= date(DATE_ATOM);

			// add to SITEMAP + URLLIST
			
			$buffer	= file($pth['app']['sitemap']);
			$handle	= fopen($pth['app']['sitemap'],"wb");
			
			$s1		= '</urlset>';
			$r1		= '<url><loc>'.$pth['app']['www'].$account.'/pt/</loc><lastmod>'.$date.'</lastmod></url>'."\n".'<url><loc>'.$pth['app']['www'].$account.'/en/</loc><lastmod>'.$date.'</lastmod></url>'."\n".'</urlset>';
			
			foreach($buffer as $line){
				if (substr($line,0,strlen($s1))==$s1){ $line = substr_replace($line,$r1,0); }
				fwrite($handle,$line);
			}
			
			fclose($handle);
			
			$urllist= $pth['app']['www'].$account.'/'."\n";
			$handle	= fopen($pth['app']['urllist'],'ab');
			fwrite($handle,$urllist);
			fclose($handle);

			// SAVE REFERRAL INFO
			if(isset($refID) && $refID!=''){
								
				$cf['fan']['fee']='1';
				
				$buffer	= $account.','.$date.','.$cf['fan']['fee'].','."\n";
				$handle	= fopen($pth['data']['fan'].$refID.'.csv','ab');
				fwrite($handle,$buffer);
				fclose($handle);
				
			}

			// BUILD SITE + MEMBER's DATA
			mkdir($pth['site']['root']);

			mkdir($pth['site']['root'].'archive');

			$newfile = $pth['site']['root'].'content.pt.xml'; if(!is_file($newfile)) copy($pth['zero']['root'].'content.pt.xml', $newfile);
			$newfile = $pth['site']['root'].'content.en.xml'; if(!is_file($newfile)) copy($pth['zero']['root'].'content.en.xml', $newfile);
			$newfile = $pth['site']['root'].'avatar.gif'; if(!is_file($newfile)) @copy($pth['zero']['root'].'avatar.gif', $newfile);
			$newfile = $pth['site']['root'].'avatar.jpg'; if(!is_file($newfile)) @copy($pth['zero']['root'].'avatar.jpg', $newfile);
			
			$newfile = $pth['data']['css'].$account.'.php'; if(!is_file($newfile)) @copy($pth['data']['css'].'(zero).php', $newfile);
			
			$output = '<?php
$cf[\'acc\'][\'date\']=\''.$date.'\';
$cf[\'acc\'][\'name\']=\''.$account.'\';
$cf[\'acc\'][\'refID\']=\''.$refID.'\';
$cf[\'acc\'][\'password\']=\''.crypt($password).'\';
$cf[\'acc\'][\'email\']=\''.trim($_POST['getAccEmail']).'\';
$cf[\'acc\'][\'type\']=\'0\';
?>';

			$handle	= fopen($pth['data']['acc'].$account.'.php','wb');
			fwrite($handle,encodeUTF8($output));
			fclose($handle);

			$output = '<?php
$cf[\'site\'][\'startLang\']=\'pt\';
$cf[\'site\'][\'template\']=\'diana\';
$cf[\'site\'][\'layout\']=\'2-columns-right\';
$cf[\'site\'][\'pt\'][\'title\']=\'Página Virtual : O Espaço.NET 24/7 de ( '.$account.' ).\';
$cf[\'site\'][\'pt\'][\'description\']=\'O serviço «PÁGINA VIRTUAL» é desenvolvido pela empresa Segundo Criativo: há vários anos e permite às organizações, profissionais e particulares, estarem presentes na Internet, de forma quase imediata e a preços extremamente competitivos (o Plano BASE é grátis).\';
$cf[\'site\'][\'pt\'][\'keywords\']=\'aminhacomunidade, cms, comunidade, content management system, design, internet, mais valor, membro, página, plataforma, portal, site, sítio, valor, webdesign\';
$cf[\'site\'][\'en\'][\'title\']=\'Página Virtual : O Espaço.NET 24/7 de ( '.$account.' ).\';
$cf[\'site\'][\'en\'][\'description\']=\'O serviço «PÁGINA VIRTUAL» é desenvolvido pela empresa Segundo Criativo: há vários anos e permite às organizações, profissionais e particulares, estarem presentes na Internet, de forma quase imediata e a preços extremamente competitivos (o Plano BASE é grátis).\';
$cf[\'site\'][\'en\'][\'keywords\']=\'aminhacomunidade, cms, comunidade, content management system, design, internet, mais valor, membro, página, plataforma, portal, site, sítio, valor, webdesign\';
?>';

			$handle	= fopen($pth['data']['srv'].$account.'.php','wb');
			fwrite($handle,encodeUTF8($output));
			fclose($handle);

			clearstatcache();

			// INCLUDE IN EMAIL REFERRAL ID
			$whoCreated = (isset($refID) && $refID!='') ? "Por recomendação de {$pth['app']['www']}$refID, criamos a sua ".decodeUTF8($txt['app']['platform']) : "Acabou de criar a sua ".decodeUTF8($txt['app']['platform']) ;

			// BUILD EMAIL
			require($pth['lib']['phpmailer']);
			$mail = new PHPMailer();
			$mail->IsHTML(false);
			$mail->Priority 		= 1;
			$mail->CharSet 			= 'UTF-8';
			$mail->Hostname			= $_SERVER['HTTP_HOST'];
			$mail->From     		= 'YOUR_EMAIL_ADDRESS';
			$mail->FromName 		= encodeUTF8('( '.decodeUTF8($txt['app']['platform']).' )');
			$mail->Subject 			= encodeUTF8('( '.$account.' ) Dados da sua Conta.');
			$mail->Body 			= encodeUTF8('---------------------------------------------------------------------

( '.strtoupper(decodeUTF8($txt['app']['platform'])).' ) || '.$pth['app']['www'].'

---------------------------------------------------------------------

Parabéns. '.$whoCreated.'

================
    ENDEREÇO
================

Para aceder à sua Página, clique no seguinte endereço:
(este é o endereço que deve dar a conhecer ao mundo)

'.$pth['app']['www'].$account.'

=============
    DADOS
=============

Nome da Conta: '.$account.'

Código de Acesso: '.$password.' ( é aconselhável alterar o seu código )

Correio Electrónico: '.trim($_POST['getAccEmail']).'

===================================================
	MANTENHA-SE INFORMADO OU PROCURE AJUDA EM:	
===================================================

http://blog.'.$pth['app']['domain'].' ou http://sos.'.$pth['app']['domain'].'

ATENÇÃO:
Não se esqueça de ler os Termos de Utilização deste Serviço e a nossa Política de Privacidade, que podem ser encontrados em '.$pth['app']['www'].'.

Obrigado pela sua preferência e bem vindo.

'.date('Y').' @ Todos os direitos reservados.');

			$mail->AddAddress(trim($_POST['getAccEmail']));
			$mail->AddBCC($txt['app']['supportMail']);

			// SEND EMAIL
			if(!$mail->Send()){ $msg = encodeUTF8('Pedimos desculpa, mas não foi possível enviar o email com os seus dados.<p>Contudo poderá aceder à sua Página através deste endereço: <a href="'.$pth['app']['www'].$account.'">'.$pth['app']['www'].$account.'</a></p>'); }
			else {
				$msg = encodeUTF8('<h3>Parabéns!</h3>
				<p>Acabou de criar a sua «<strong>PÁGINA VIRTUAL</strong>» (com informação temporária).</p>
				<p>Foram enviados para o seu endereço de correio electrónico os dados da sua conta. Enquanto espera, pode sempre visitar a sua <a href="'.$pth['app']['www'].$account.'">Página</a>.</p>');
			}
			unset($_POST);
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			
			// PING GOOGLE
			pingGoogleSitemaps($pth['app']['www'].$account.'/content.pt.xml');
			pingGoogleSitemaps($pth['app']['www'].$account.'/content.en.xml');
			
		}
		
	}
	
}

function getAccount(){
	global $pth; include($pth['home']['globals']);
	
	$output = '<form action="" method="post" name="getAccount" id="getAccount" class="section">
	<h2>'.$txt['getAccount']['title'].'</h2>';
	if(isset($txt['getAccount']['alert']) AND $txt['getAccount']['alert'] != NULL) $output.='<p class="alert">'.$txt['getAccount']['alert'].'</p>';
	$output.= '<div>
		<label for="getAccName">'.$txt['getAccount']['name'].'</label>
		<input type="text" name="getAccName" id="getAccName" value="';
		if(isset($_POST['getAccName'])) $output.=$_POST['getAccName'];
		$output.= '" maxlength="30" />
		<ul class="footnote">
			<li>'.$txt['getAccount']['nameFootnote2'].'</li>
			<li>'.$txt['getAccount']['nameFootnote3'].'</li>
		</ul>
	</div><div>
		<label for="getAccEmail">'.$txt['getAccount']['email'].'</label>
		<input type="text" name="getAccEmail" id="getAccEmail" value="';
		if(isset($_POST['getAccEmail'])) $output.=$_POST['getAccEmail'];
		$output.= '" maxlength="50" />
		<ul class="footnote">
			<li>'.$txt['getAccount']['emailFootnote1'].'</li>
		</ul>
	</div><div>
		<label for="getAccRefID">'.$txt['getAccount']['refID'].'</label>
		<input type="text" name="getAccRefID" id="getAccRefID" value="';
			$output.=(!isset($_POST['getAccRefID']) AND isset($_GET['refID'])) ? $_GET['refID'] : '';
			$output.=(isset($_POST['getAccRefID'])) ? $_POST['getAccRefID'] : '';
		$output.='" maxlength="30" />
		<ul class="footnote">
			<li>'.$txt['getAccount']['refIDFootnote1'].'</li>
		</ul>
	</div>
	
	<div class="action"><button type="submit">'.$txt['getAccount']['submit'].'</button>&nbsp;<button type="reset">'.$txt['getAccount']['cancel'].'</button></div>
	<p class="getAccAgree">'.$txt['getAccount']['agreement'].'</p>
	<input type="hidden" name="function" value="getAccount"/>
	<input type="hidden" name="submitid" id="submitid" value="1" />	
	</form>';

	print $output;
	
}

?>