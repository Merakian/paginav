<?php
if (eregi('admin.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

require($pth['admin']['locale'].'pt.php'); rfc();

function editmenu(){
	global $pth; include($pth['app']['globals']);
	$t='';
	$t.='<div id="editMenu" class="clearfix">
	
	<p class="logo">&lsaquo; '.$txt['globar']['title'].' &rsaquo;</p>
	
	<ul>
		<li>';	
		if(isset($edit))$t.=a($s,amp().'normal').$txt['editmenu']['normal'].'</a>';
		else $t.=a($s,amp().'edit').$txt['editmenu']['edit'].'</a>';
	print $t.'
		</li>
		<li>&middot; '.$txt['editmenu']['organize'].' ( </li>
		<li><a href="'.$sn.'?'.amp().'pages">'.$txt['editmenu']['pages'].'</a></li>
		<li>&middot; <a href="'.$sn.'?'.amp().'archive">'.$txt['editmenu']['archive'].'</a></li>
		<li> )</li>
		<li>&middot; <a href="'.$sn.'?'.amp().'customize">'.$txt['editmenu']['customize'].'</a></li>
		<li>&middot; <a href="'.$sn.'?'.amp().'manage">'.$txt['editmenu']['manage'].'</a></li>
		<li>| <a href="http://blog.paginav.com" target="_blank">'.$txt['editmenu']['blog'].'</a></li>
		<li>| <a href="http://sos.paginav.com" target="_blank">'.$txt['editmenu']['help'].'</a></li>
		<li>| <a href="'.$sn.'?'.amp().'logout">'.$txt['editmenu']['logout'].'</a></li>
	</ul>
	</div>';
}

// HELP: WHAT'S THIS FOR?!
if($file)$f='file';

if(isset($pages) OR (isset($function) AND $function=='pages')){ $f='pages'; include('pages.php'); }
if(isset($archive) OR (isset($function) AND $function=='archive')){ $f='archive'; include('archive.php'); }
if(isset($customize)){ $f='customize'; include('customize.php'); }
if(isset($manage)){ $f='manage'; include('manage.php'); }
if(isset($goPro)){ $f='goPro'; include('goPro.php'); }
if(isset($logout)){ $f=$txt['logout']['title']; include('logout.php'); }

include('edit.php');

?>