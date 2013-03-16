<?php
if (eregi('pages.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }
 
$title	=$txt['menuPages']['title']; $o.='<h1>'.$title.'</h1>';
$plugin	="menumanager";

function eclip($x){ // truncates the text after 24 charaters and adds a title with the original
	if (strlen($x)>14){ return ' Title = "'. $x  .'">' . substr($x,0,16) . '..'; }
	else return '>'.$x;
}

// load css + lang
$hjs .= '<link rel="stylesheet" type="text/css" href="'.$pth['admin']['module2'].$plugin.'/style.css">';
if(@!include($pth['admin']['module'].$plugin.'/lang/'.$sl.'.php')) include($pth['admin']['module'].$plugin.'/lang/pt.php');

if(isset($_POST['text'])) $text = $_POST['text'];
elseif(isset($_GET['text'])) $text = $_GET['text'];
else $text = '';

if (isset($_POST['action']) AND $_POST['action']=='1'){

	if($fh=fopen($pth['site']['content'],"w")){
		$tops =  explode ( ',' , $text );

		$title=ucfirst($txt['filetype']['content']);
		for($i=0; $i < count($tops) ; $i++){ 
			$pageInfo=explode('^',$tops[$i]);

			if($pageInfo[0] == 'New' ) fwrite($fh,"<h$pageInfo[1]>$pageInfo[2]</h$pageInfo[1]>\n");
			else { 
				$leveledPage = preg_replace('/[1-3]>/', "$pageInfo[1]>" , $c[$pageInfo[0]]);
				fwrite($fh,rmnl($leveledPage));
			}
		}
		fclose($fh);
		rfc();
	}
	
}

/* show the Header */

$o.='<div id="pages">';

$o.='<script type="text/javascript" src="'.$pth['admin']['module2'].$plugin.'/wz_dragdrop.js"></script>'."\n";

$o.='<p>'.$plugin_txt['menumanager']['smmDesc'].'</p>';

$editor_height=105+count($h)*30;

$o.='<form action="'.$sn.'?&pages" id="menuPages" method="post">';
$o.='<div id="popwarn">'.$plugin_txt[$plugin]['smmSaving']."</div>\n";	
$o.='<input type="submit" class="submitmenu" onMouseDown="MM_showHideLayers(\'popwarn\',\'\',\'show\')" value="'.$plugin_txt[$plugin]['smmSave'].'" />';

$o.='<textarea class="hiddenArea" id="arrangeResult" rows="1" cols="20" name="text">';
for($i=0;$i < count($h);$i++){ if($i != 0) $o.=','; $o.="$i^$l[$i]^ "; }
$o.='</textarea>';

// Menu Manager Code
$list = '<div class="groupstyle1" id="group1" style="height:'.$editor_height.'px">';

$list.= '<div class="headerstyle1" id="header1">'.$plugin_txt[$plugin]['smmLevel1']."</div>\n";
$list.= '<div class="headerstyle2" id="header2">'.$plugin_txt[$plugin]['smmLevel2']."</div>\n";
$list.= '<div class="headerstyle3" id="header3">'.$plugin_txt[$plugin]['smmLevel3']."</div>\n";
$list.= '<div class="headerstyleDel" id="headerDel">'.$plugin_txt[$plugin]['smmDelete']."</div>\n";
$tops='';

$list .= '<div class="liststyle1" id="fakeid">fake</div>'."\n";	

for($i=0;$i < count($h);$i++){	
	$tops.=',"'."top$i".'"';
	if ($l[$i] == 1) $list.= '<div class="liststyle1" id="top'.$i.'" '.eclip($h[$i]).'</div>'."\n";
	if ($l[$i] == 2) $list.= '<div class="liststyle2" id="top'.$i.'" '.eclip($h[$i]).'</div>'."\n";
	if ($l[$i] == 3) $list.= '<div class="liststyle3" id="top'.$i.'" '.eclip($h[$i]).'</div>'."\n";
}

$list.= '<div class="liststyleNew" id="topNew">'.$plugin_txt[$plugin]['smmNewPage'].'</div>'."\n";

$o.=$list;

$o.='<input type="hidden" name="function" value="pages" />';	// come back to normal view
$o.='<input type="hidden" name="action" value="1" />';

$o.='
<script type="text/javascript"> 
<!--
DELAYED_SET_DHTML(CURSOR_MOVE,SCROLL,NO_ALT,"topNew"'.$tops.');
var smmPromptText="'.$plugin_txt[$plugin]['smmPromptText'].'";
var smmText="'.$plugin_txt[$plugin]['smmText'].'";
//-->
</script>';

$o.="</div></form><p>&nbsp;</p></div>";
	
?>