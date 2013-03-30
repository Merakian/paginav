<?php
if (eregi('globals.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

global $accName, $account, $c, $cf, $cPanel, $dataID, $lang, $msg, $nav, $ns, $pth, $refID, $s, $txt;

?>