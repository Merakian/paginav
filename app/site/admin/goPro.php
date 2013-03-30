<?php
if (eregi('goPro.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['action']) AND $_POST['action'] == 1){

	if($_POST['function'] == ''){
	}

}

function goPro(){
	global $pth; include($pth['app']['globals']);
	
	if($cf['acc']['type']=='1'){ 
		$output = '<h4>'.$txt['goPro']['activeTitle'].'</h4>
			<ul>
				<li>'.$txt['goPro']['activeOpt1'].'</li>
				<li>'.(round($cf['disk']['quota']/1024000)).' '.$txt['goPro']['activeOpt2'].'</li>
			</ul>
		';
	}
	else {
		$output = '<h4>'.$txt['goPro']['subtitle'].'</h4>
			<ul>
				<li>'.$txt['goPro']['Opt1'].'</li>
				<li>'.$txt['goPro']['Opt2'].'</li>
			</ul>
			<h4>'.$txt['goPro']['feeTitle'].'</h4>
			<ul>
				<li><strong>'.$cf['goPro']['fee'].'</strong> '.$txt['goPro']['feeDescription'].'</li>
				<li>'.$txt['goPro']['feeDetails'].'</li>
			</ul>
			<h4>'.$txt['goPro']['process'].'</h4>
			<ol>
				<li>'.$txt['goPro']['step1'].' '.$cf['goPro']['fee'].'</li>
				<li>'.$txt['goPro']['step2'].'</li>
				<li>'.$txt['goPro']['step3'].'</li>
			</ol>
			<p><small>'.$txt['goPro']['processfootnote'].'</small></p>
			<h4>'.$txt['goPro']['step1title'].' '.$cf['goPro']['fee'].'</h4>
			<p>'.$txt['goPro']['step1desc'].'</p>
			<ul>
				<li>'.$txt['goPro']['step1OptA1'].'</li>
				<li>'.$txt['goPro']['step1OptA2'].'</li>
				<li>'.$txt['goPro']['step1OptA3'].'</li>
				<li>'.$txt['goPro']['step1OptA4'].'</li>
				<li>'.$txt['goPro']['step1OptA5'].'</li>
			</ul>
			<ul>
				<li>'.$txt['goPro']['step1OptB1'].'</li>
				<li>'.$txt['goPro']['step1OptB2'].'</li>
				<li>'.$txt['goPro']['step1OptB3'].'</li>
				<li>'.$txt['goPro']['step1OptB4'].'</li>
				<li>'.$txt['goPro']['step1OptB5'].'</li>
			</ul>
			<ul>
				<li>'.$txt['goPro']['step1OptRef'].'</li>
			</ul>
			<p><small>'.$txt['goPro']['step1footnote'].'</small></p>
			<h4>'.$txt['goPro']['step2title'].'</h4>
			<p>'.$txt['goPro']['step2txt1'].'</p>
			<ol>
				<li>'.$txt['goPro']['step2txt2'].'</li>
				<li>'.$txt['goPro']['step2txt3'].'</li>
				<li>'.$txt['goPro']['step2txt4'].'</li>
			</ol>
			<h4>'.$txt['goPro']['whoisTitle'].'</h4>
			<ul>
				<li>'.$txt['goPro']['whois1'].'</li>
				<li>'.$txt['goPro']['whois2'].'</li>
				<li>'.$txt['goPro']['whois3'].'</li>
				<li>'.$txt['goPro']['whois4'].'</li>
				<li>'.$txt['goPro']['whois5'].'</li>
				<li>'.$txt['goPro']['whois6'].'</li>
				<li>'.$txt['goPro']['whois7'].'</li>
			</ul>
		';
	}
		
	return $output;
}

if($msg!='' || $msg==TRUE)$o.=msgBox($msg);

$title=$txt['goPro']['title']; $o.='<h1>'.$title.'</h1>';
		
$o.=goPro();

?>