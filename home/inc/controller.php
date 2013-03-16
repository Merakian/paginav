<?php
if (eregi('controller.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

ob_start();

// cache
header('Cache-control: no-cache');
header('Expires: 0');
date_default_timezone_set('Europe/Lisbon');

require('router.php');
require('functions.php');
require('i18n.php');

header('P3P: policyref="", CP=""');

clearstatcache();

$cf['acc']['lang'] = 'pt-PT';
$buffer = explode("-",$cf['acc']['lang']);
setlocale(LC_ALL, $cf['acc']['lang'], $txt['i18n'][$cf['acc']['lang']], $buffer[0].'_'.$buffer[1]);
require($pth['home']['locale'].$cf['acc']['lang'].'.php');

// load Modules
clearstatcache();
$dir = opendir($pth['home']['module']);
while(($file=readdir($dir))==true ){ if(is_file($pth['home']['module'].$file)) require($pth['home']['module'].$file); }
if($dir==true)closedir($dir);

?>