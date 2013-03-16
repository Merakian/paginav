<?php
if (eregi('master.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$cf['uri']['seperator']			= "/";
$cf['uri']['length']			= "200";
$cf['xhtml']['endtags']			= "true";
$cf['xhtml']['amp']				= "true";

$cf['editor']['height']			= "(screen.availHeight-500)";
$cf['editor']['external']		= "";

$cf['backup']['numberoffiles']	= "10";

$cf['disk']['quota']			= ($cf['acc']['type']=='0') ? "131072" : "20971520";
$cf['archive']['imgMaxSize']	= "262144";
$cf['archive']['docMaxSize']	= "10485760";

$cf['intro']['size']			= "1048576";
$cf['avatar']['size']			= "262144";

$cf['acc']['type0']				= 'BASE';
$cf['acc']['type1']				= 'PRO';
$cf['acc']['typeName']			= ($cf['acc']['type']=='1') ? $cf['acc']['type1'] : $cf['acc']['type0'];

$cf['goPro']['fee']				= "50 &euro;";
$cf['fan']['fee']				= "1";

?>