<?php
if (eregi('sitemap.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

if(isset($sitemap))$f='sitemap';

if($f=='sitemap'){ 
	
	$title=$txt['title'][$f];
	
	$t=''; $ta=$hc; $st='sitemaplevel'; $j=0; $lf[0]=$lf[1]=$lf[2]=$lf[3]=false;
	
	for($i=0; $i<$hl; $i++){
		if(!isset($ta[$j])) break; 
		if($hc[$i]!=$ta[$j]) continue;
		$tf=($s!=$ta[$j]);
		$t.='<ul>
			<li class="'.$st.$l[$ta[$j]].' '; if(!$tf)$t.='s'; $t.='doc';
			if(isset($hc[$i+1])) if($l[$hc[$i+1]]>$l[$ta[$j]]) $t.='s';
			$t.='">'.a($ta[$j],'').$h[$ta[$j]].'</a></li>
		</ul>';
		$j++;
	}
	
	$o.='<h1>'.$title.'</h1><div id="sitemap">'.$t.'</div>';
	
}
?>