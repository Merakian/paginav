<?php
if (eregi('edit.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($_POST['function']) AND $_POST['function']=='save') $f='save';

if($f=='save'){

	$text=$_POST['text'];
    $ss=$s=$_POST['s'];
	$c[$s]=preg_replace("/<h[1-3][^>]*>(\&nbsp;| )?<\/h[1-3]>/i","",stripslashes($text));
	$c[$s]=preg_replace("/<br>/i","<br/>",$c[$s]);
	
	if($s==0) if(!preg_match("/^(\n)?<h1[^>]*>.*<\/h1>/i",rmnl($c[0])) AND !preg_match("/^(<p[^>]*>)?(\&nbsp;| |<br \/>)?(<\/p>)?(\n)?$/i",rmnl($c[0]))) $c[0]='<h1>'.$txt['toc']['missing'].'</h1>'.$c[0];
	
	if($fh=@fopen($pth['site']['content'],"wb")){
	
		$date	= date(DATE_ATOM);
		
		$output = '<?xml version="1.0" encoding="'.$txt['meta']['codepage'].'"?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="'.$sl.'" xmlns:base="http://ns.baselocal.com/2008/">
<id>tag:'.$date.':'.$_SERVER['HTTP_HOST'].'/'.$cf['acc']['name'].'/'.$sl.'/</id>
<link rel="self" href="'.$pth['site']['feed'].'" type="application/atom+xml" />
<link rel="alternate" href="'.$pth['site']['base'].$sl.'/" type="text/html" />
<author><name>'.$cf['acc']['name'].'</name></author>
<generator>Segundo Criativo: '.$txt['app']['name'].'</generator>
<rights>2008 © Todos os direitos reservados.</rights>
<title>'.decodeUTF8($cf['site'][$sl]['title']).'</title>
<subtitle>'.decodeUTF8($cf['site'][$sl]['description']).'</subtitle>
<updated>'.$date.'</updated>
<entry><id>tag:'.$date.':'.$_SERVER['HTTP_HOST'].'/'.$cf['acc']['name'].'/'.$sl.'/1</id><link rel="alternate" href="'.$pth['site']['base'].$sl.'/"/><title>'.decodeUTF8($cf['site'][$sl]['title']).'</title><content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">';
		
		fwrite($fh, $output."\n");
		foreach($c as $i){ fwrite($fh,rmnl($i)."\n"); }
		fwrite($fh, '</div></content><published>'.$date.'</published><updated>'.$date.'</updated></entry></feed>');
		fclose($fh);
		rfc();
	}
	else e('cntwriteto','content',$pth['site']['content']);
	
}

if(isset($edit) && (!$f || $f=='save') && !$download){

	if($s<0 AND isset($ss) AND $ss<$cl) $s=$ss;
	if(!$s OR $s<0) $s=0;
	
	if($s>-1){
	
		$su=$u[$s]; $iimage='';
	
		if($cf['editor']['external']!=''){ if(!@include($pth['admin']['root'].$cf['editor']['external'].'.php'))$e.='<ul><li>External editor missing</li></ul>'; }
		else {

			// OEdit (BUILT IN EDITOR)
			// OEdit Ver. 3.6 - © 2004 Peter Andreas Harteg - http://www.harteg.dk

			if ($txt['editor']['buttons']!=''){

				// GET IMAGES + DOCUMENTS FROM ARCHIVE
				
				$ilink='';
				for($i=0; $i<$cl; $i++){
					if($ilink!='')$ilink.=',';
					$ilink.='["'.$sn.'?'.$u[$i].'","'.substr(str_replace('"','&quot;',$h[$i]),0,30).'"]';
				}

				if(@is_dir($pth['site']['archive'])){
					$fs=sortdir($pth['site']['archive']);
					foreach($fs as $p){
						if(preg_match("/\.gif$|\.jpg$|\.jpeg$|\.png$/i",$p)){
							if($iimage!='')$iimage.=',';
							$iimage.='["'.$pth['site']['archive1'].urlencode($p).'","'.substr($p,0,30).'"]';
						} else if(preg_match("/^[^\.]/i",$p)){
							if($ilink!='')$ilink.=',';
							$ilink.='["'.$sn.'?&download='.rawurlencode($p).'","(File '.(round((filesize($pth['site']['archive'].'/'.$p))/102.4)/10).' KB)'.' '.substr($p,0,25).'"]';
						}
					}
				} else { 
					$iimage.='["","'.$txt['error']['cntopen'].' '.$pth['site']['archive'].'"]';
					$ilink.=$iimage;
				}

				if($iimage=='')$iimage.='["","'.$txt['editor']['noimages'].'"]';

				// GET EDIT BUTTONS

				if(@is_dir($pth['app']['editbuttons']))$getimage='"'.$pth['app']['editbuttons2'].'"+image+".gif"';
				else $getimage='"'.$sn.'?image="+image';

				$onload.=' onload="init()"';
				$hjs='<script type="text/javascript">

				var copyright="CMSimple - http://www.cmsimple.dk";
				var changemode="'.$txt['editor']['changemode'].'";
				var btns='.$txt['editor']['buttons'].';
				var iimage=['.$iimage.'];
				var ilink=['.$ilink.'];
				var format="HTML";
				var isNav=(navigator.appName=="Netscape");

				function getimage(image){return '.$getimage.'}

				function init(){
					document.getElementById("f").contentWindow.document.designMode="on";
					document.getElementById("f").contentWindow.focus();
					window.status=copyright
				}

				function chmode(){
					if(format=="HTML"){
						if(isNav){
							var html=document.createTextNode(document.getElementById("f").contentWindow.document.body.innerHTML);
							with(document.getElementById("f").contentWindow.document.body){
								innerHTML="";
								appendChild(html)
							}
						}
						else{
							with(document.getElementById("f").contentWindow){
								with(document.body){
									innerText=innerHTML
								}
								focus();
								document.body.createTextRange().collapse(false)
							}
						}
						document.getElementById("html").src=img2.src;
						format="Text"
					}
					else{
						if(isNav){
							var html=document.getElementById("f").contentWindow.document.body.ownerDocument.createRange();
							html.selectNodeContents(document.getElementById("f").contentWindow.document.body);
							document.getElementById("f").contentWindow.document.body.innerHTML=html.toString()
						}
					else{
						with(document.getElementById("f").contentWindow){
							with(document.body){
								innerHTML=innerText;
								style.fontSize=""
							}
							focus();
							document.body.createTextRange().collapse(false)
						}
					}
					document.getElementById("html").src=img1.src;
					format="HTML"
					}
				}

				function cmd(c){
					if(c=="save"){
						if(format=="HTML"){
							document.getElementById("text").value=document.getElementById("f").contentWindow.document.body.innerHTML;
							document.getElementById("ta").submit()
						}
						else if(confirm(changemode))chmode()
					}
					else if(c=="selectall")document.getElementById("f").contentWindow.document.execCommand(c,false,null);
					else if(c=="html")chmode();
					else{
						if(format=="HTML"||(c=="cut"||c=="copy"||c=="paste"||c=="undo"||c=="redo")){
							var t=null;
							if(c=="iimage"){
								t=document.forms[c].iimage.value;
								c="insertimage"
							}
							if(c=="ilink"){
								t=document.forms[c].ilink.value;
								c="createlink"
							}
							if((c.search(/h[1-4]/)!=-1)||c=="p"){
								t="<"+c+">";
								c="formatblock"
							}
							document.getElementById("f").contentWindow.focus();
							if(t==null&&c=="createlink"){
								if(isNav){
									t=prompt("Enter URL:","");
									document.getElementById("f").contentWindow.document.execCommand("CreateLink",false,t)
								}
								else document.getElementById("f").contentWindow.document.selection.createRange().execCommand(c,true,t)
							}
							else if(c=="cut"||c=="copy"||c=="paste")document.getElementById("f").contentWindow.document.selection.createRange().execCommand(c,false,null);
							else document.getElementById("f").contentWindow.document.execCommand(c,false,t);
							document.getElementById("f").contentWindow.focus();
						}
					}
				}

				function tables(){
					for(var i=0;i<btns.length;i++){
						if(btns[i][0]=="ilink")sb(i,ilink);
						if(btns[i][0]=="iimage")sb(i,iimage);
						if(btns[i][0]=="tr")document.write("<div class=\"break\"></div>");
						else{
							if(btns[i][0]!="") document.write("<img src=\""+getimage(btns[i][0])+"\" id=\""+btns[i][0]+"\" alt=\""+btns[i][1]+"\" title=\""+btns[i][1]+"\" onclick=\"cmd(\'"+btns[i][0]+"\')\" onmouseover=\"this.style.border=\'inset 1px\';window.status=\'"+btns[i][2]+"\'\" onmouseout=\"this.style.border=\'outset 1px\';window.status=\'"+copyright+"\'\">");
							else document.write("<span class=\"sep\"></span>")
						}
					}
				}

				function sb(i,t){
					document.write("<form id=\""+btns[i][0]+"\"><select id=\""+btns[i][0]+"\">");
					for(var j=0;j<t.length;j++)document.write("<option value=\""+ t[j][0]+"\">"+t[j][1]+"</option>");
					document.write("</select></form>")
				}

				function bloker(){return false}

				document.ondragstart=bloker;
				img1=new Image();
				img1.src=getimage("html");
				img2=new Image();
				img2.src=getimage("layout");
				</script>';

				// PUBLISH THE EDITOR

				$o.='<div id="editButtons">
						<script type="text/javascript">tables();</script>
					</div>
					
					<div id="editArea">
						<script type="text/javascript">
							document.write(\'<iframe id="f" src="'.$sn.'?'.$su.'&retrieve" width="100%" height="\'+('.$cf['editor']['height'].')+\'"></iframe>\');
						</script>
					</div>

					<form method="post" id="ta" action="'.$sn.'">
						<input type="hidden" name="s" value="'.$s.'"/>
						<input type="hidden" name="selected" value="'.$u[$s].'"/>
						<input type="hidden" name="function" value="save"/>
						<textarea name="text" id="text"></textarea>
					</form>';
		
			}
		
		}
	
	} 
	else $o=msgBox($txt['error']['cntlocateheading']);

}

?>