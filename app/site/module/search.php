<?php
if (eregi('search.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$function	= (isset($_POST['function']) AND $_POST['function']=='search') ? $_POST['function'] : '' ;
$search		= (isset($_POST['search'])) ? $_POST['search'] : '';

if($function=='search'){
    $title=$txt['title']['search'];
    $ta=array();
    for($i=0;$i<$hl;$i++){
        if(@preg_match('/'.preg_quote($search,'/').'/i',$c[$hc[$i]]))$ta[]=$hc[$i];
    }
    $o.='<h1>'.$txt['search']['result'].'</h1><p>"'.htmlspecialchars(stripslashes($search)).'" ';
    if(count($ta)==0)$o.=$txt['search']['notfound'].'.';
    else {
        $o.=$txt['search']['foundin'].' '.count($ta). ' ';
        if(count($ta)>1)$o.=$txt['search']['pgplural'];
        else $o.=$txt['search']['pgsingular'];
        $o.=':';
    }
    $o.='</p>'.li($ta,'search');
}

?>