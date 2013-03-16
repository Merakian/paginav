<?php
if (eregi('router.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$ns 							= 'http://ns.baselocal.com/2008/';

$pth['lib']['root']				=$_SERVER['DOCUMENT_ROOT'].'/lib/';
$pth['lib']['phpmailer']		=$pth['lib']['root'].'phpmailer/class.phpmailer.php';

$pth['acc']['log']				=$_SERVER['DOCUMENT_ROOT'].'/_log/';

$pth['app']['bak']				=$_SERVER['DOCUMENT_ROOT'].'/_bak/';
$pth['app']['data']				=$_SERVER['DOCUMENT_ROOT'].'/data/';
	$pth['data']['acc']			=$pth['app']['data'].'acc/';
	$pth['data']['css']			=$pth['app']['data'].'css/';
	$pth['data']['fan']			=$pth['app']['data'].'fan/';
	$pth['data']['srv']			=$pth['app']['data'].'srv/';
	$pth['data']['static']		=$pth['app']['data'].'static/';

$pth['acc']['root']				=$pth['data']['static'];

$pth['app']['root']				=$_SERVER['DOCUMENT_ROOT'].'/app/';

$handle							=explode(".",$_SERVER['HTTP_HOST'],2);
$pth['app']['domain']			=$handle[1].'/';
$pth['app']['www']				="http://".$_SERVER['HTTP_HOST'].'/';
$pth['app']['base']				="http://".$_SERVER['HTTP_HOST'].'/app/';
$pth['app']['home']				="http://".$_SERVER['HTTP_HOST'].'/home/';

$pth['app']['sitemap']			= $_SERVER['DOCUMENT_ROOT'].'/sitemap1.xml';
$pth['app']['urllist']			= $_SERVER['DOCUMENT_ROOT'].'/urllist.txt';

$pth['app']['p3p'] 				= $pth['app']['domain'].'w3c/p3p.xml';
$pth['app']['privacy'] 			= '';
$pth['app']['tos'] 				= '';

$pth['app']['feed']				= '';

//	HOME global
$pth['home']['root']			= $_SERVER['DOCUMENT_ROOT'].'/home/';
$pth['home']['inc']				= $pth['home']['root'].'inc/';
	$pth['home']['globals']		= $pth['home']['inc'].'globals.php';
$pth['home']['module']			= $pth['home']['root'].'module/';
$pth['home']['locale']			= $pth['home']['root'].'locale/';
$pth['home']['js']				= $pth['home']['root'].'js/';
$pth['home']['view']			= $pth['home']['root'].'view/';
	$pth['home']['structure']	= $pth['home']['view'].'master.html';
	$pth['home']['style']		= $pth['home']['view'].'master.css';

$pth['home']['media']			= $pth['app']['home'].'media/';
	$pth['home']['icon']		= $pth['home']['media'].'icon/';
	$pth['home']['flag']		= $pth['home']['media'].'flag/';

//	account & service data + global url

$pth['acc']['reservedAlt']		= $pth['app']['data'].'reserved.txt';
$pth['acc']['reserved']			= '';

//	account (ZERO)

$pth['zero']['root']			=$pth['acc']['root'].'(zero)/';

?>