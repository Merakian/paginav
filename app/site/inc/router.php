<?php
if (eregi('router.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$pth['lib']['root']				=$_SERVER['DOCUMENT_ROOT'].'/lib/';
$pth['lib']['phpmailer']		=$pth['lib']['root'].'phpmailer/class.phpmailer.php';

$pth['acc']['log']				=$_SERVER['DOCUMENT_ROOT'].'/_log/';

$pth['app']['bak']				=$_SERVER['DOCUMENT_ROOT'].'/_bak/';
$pth['app']['data']				=$_SERVER['DOCUMENT_ROOT'].'/data/';

$pth['data']['acc']				=$pth['app']['data'].'acc/';
$pth['data']['css']				=$pth['app']['data'].'css/';
$pth['data']['fan']				=$pth['app']['data'].'fan/';
$pth['data']['srv']				=$pth['app']['data'].'srv/';
$pth['data']['static']			=$pth['app']['data'].'static/';

$pth['acc']['data']				=$pth['data']['acc'].$cf['acc']['name'].'.php';
$pth['fan']['data']				=$pth['data']['fan'].$cf['acc']['name'].'.csv';
$pth['srv']['data']				=$pth['data']['srv'].$cf['acc']['name'].'.php';

$pth['acc']['root']				=$pth['data']['static'];

// APP PATH
$handle							=explode(".",$_SERVER['HTTP_HOST'],2);
$pth['app']['domain']			=$handle[1].'/';

$pth['app']['www']				="http://".$_SERVER['HTTP_HOST'].'/';
$pth['app']['base']				="http://".$_SERVER['HTTP_HOST'].'/app/site/';

$pth['app']['root']				=$_SERVER['DOCUMENT_ROOT'].'/app/site/';

$pth['app']['inc']				=$pth['app']['root'].'inc/';
	$pth['app']['globals']		=$pth['app']['inc'].'globals.php';
	$pth['app']['login'] 		=$pth['app']['inc'].'login.php';
$pth['app']['locale']      		=$pth['app']['root'].'locale/';
$pth['app']['template']			=$pth['app']['root'].'view/';
$pth['app']['media']			=$pth['app']['root'].'media/';
	$pth['app']['flags']		=$pth['app']['media'].'flag/';
	$pth['app']['icon']			=$pth['app']['media'].'icon/';
	$pth['app']['editbuttons']	=$pth['app']['media'].'editor/';
$pth['app']['module']			=$pth['app']['root'].'module/';

$pth['app']['js']				=$pth['app']['base'].'js/';
$pth['app']['template2']		=$pth['app']['base'].'view/';
$pth['app']['media2']			=$pth['app']['base'].'media/';
	$pth['app']['flags2']		=$pth['app']['media2'].'flag/';
	$pth['app']['icon2']		=$pth['app']['media2'].'icon/';
	$pth['app']['editbuttons2']	=$pth['app']['media2'].'editor/';

$pth['app']['sitemap']			= $_SERVER['DOCUMENT_ROOT'].'/sitemap1.xml';
$pth['app']['urllist']			= $_SERVER['DOCUMENT_ROOT'].'/urllist.txt';

$pth['app']['author'] 			= 'AUTHOR_URL';
$pth['app']['p3p'] 				= $pth['app']['domain'].'w3c/p3p.xml';
$pth['app']['privacy'] 			= 'http://privacy.YOUR_DOMAIN.com';
$pth['app']['tos'] 				= 'http://terms.YOUR_DOMAIN.com';

// ADMIN PATH

$pth['admin']['root']			=$pth['app']['root'].'admin/';
$pth['admin']['module']			=$pth['admin']['root'].'module/';
$pth['admin']['locale']        	=$pth['admin']['root'].'locale/';

$pth['admin']['base']			=$pth['app']['base'].'admin/';
$pth['admin']['module2']		=$pth['admin']['base'].'module/';
$pth['admin']['image']			=$pth['admin']['base'].'image/';
$pth['admin']['js']				=$pth['admin']['base'].'js/';

// SITE PATH + LANG + ...

$pth['site']['root']			=$pth['acc']['root'].$accName.'/';
$pth['site']['archive']			=$pth['site']['root'].'archive/';
$pth['site']['archive1']		='./archive/';							// path used by the editor iimage var
$pth['site']['counterHits']		=$pth['site']['root'].'counterHits.txt';

$pth['site']['customStyle']		=$pth['data']['css'].$accName.'.php';
$pth['site']['locale']        	=$pth['app']['locale'].$sl.'.php';

$pth['site']['content']			=$pth['site']['root'].'content.'.$sl.'.xml';

$pth['site']['template']		=$pth['app']['template'].$cf['site']['template'].'/';
$pth['site']['structure']		=$pth['site']['template'].'template.html';
$pth['site']['menubuttons']		=$pth['site']['template'].'menu/';
$pth['site']['templateimages']	=$pth['site']['template'].'images/';
$pth['site']['customTags']		=$pth['site']['template'].'customTags.txt';

$pth['site']['template2']		=$pth['app']['template2'].$cf['site']['template'].'/';
$pth['site']['stylesheet']		=$pth['site']['template2'].'stylesheet.css';
$pth['site']['layout']			=$pth['site']['template2'].'layout-'.$cf['site']['layout'].'.css';
$pth['site']['templateimages2']	=$pth['site']['template2'].'images/';

$pth['site']['base']			="http://".$_SERVER['HTTP_HOST'].'/'.$cf['acc']['name'].'/';
$pth['site']['archive2']		=$pth['site']['base'].'archive/';
$pth['site']['feed']			=$pth['site']['base'].'content.'.$sl.'.xml';

$pth['site']['intro']			='';
$pth['site']['introURL']		='';
$fd=@opendir($pth['site']['root']);
while(($p=@readdir($fd))==true ){
	if(@is_file($pth['site']['root'].$p)){
		if(preg_match('/^intro\.(gif|jpe?g|png|swf)$/',$p)){
			$pth['site']['intro']=$pth['site']['root'].$p;
			$pth['site']['introURL']=$pth['site']['base'].$p;
			$pth['site']['introINFO']=pathinfo($pth['site']['base'].$p);
		}
	}
}
if($fd==true)closedir($fd);

$pth['site']['log']				=$pth['acc']['log'].$cf['acc']['name'].'.txt';

$pth['park']['base']			=$pth['site']['base'].'park/';

?>