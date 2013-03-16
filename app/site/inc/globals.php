<?php
if (eregi('globals.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

global $accName, $adm, $c, $cf, $cl, $cPanel, $dataID, $docstart, $download, $e, $edit, $f, $file, $h, $hc, $hjs, $hl, $hs, $l, $lang, $login, $msg, $nav, $normal, $ns, $o, $onload, $print, $pth, $retrieve, $s, $search, $sl, $sn, $su, $t, $title, $txt, $u;

?>