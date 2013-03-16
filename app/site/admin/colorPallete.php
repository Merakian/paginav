<?php
if (eregi('colorPallete.php', $_SERVER['PHP_SELF'])){ header('location: http://'.$_SERVER['HTTP_HOST'].'/'); exit; }

$red='0'; $green='0'; $blue='0'; $color=''; $counter='5';

$output.='<table id="colorPallete" cellspacing="0"><tr>';

// WHITE TO [ BLACK ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($red<255){$red=$red+$counter;$green=$blue=$red;}
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ RED ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($red<255){$green=$blue='0'; $red=$red+$counter;}
	else {$green=$green+$counter; $blue=$green;}
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ MAGENTA ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($red<255){$green='0'; $red=$red+$counter; $blue=$red; }
	else {$green=$green+$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ BLUE ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($blue<255){$red=$green='0'; $blue=$blue+$counter;}
	else {$red=$red+$counter; $green=$red;}
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ CYAN ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($green<255){$red='0'; $green=$green+$counter; $blue=$green; }
	else {$red=$red+$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ GREEN ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($green<255){$red=$blue='0'; $green=$green+$counter;}
	else {$red=$red+$counter; $blue=$red;}
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLACK TO [ YELLOW ] TO WHITE
$color=''; $red=$green=$blue='0';
while ($color!='rgb(255,255,255)'){
	if ($green<255){$blue='0'; $green=$green+$counter; $red=$green; }
	else {$blue=$blue+$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// RED TO [ YELLOW ] TO GREEN
$color=''; $red='255'; $green='0'; $blue='0';
while ($color!='rgb(0,255,0)'){
	if ($green<255){$red='255'; $blue='0'; $green=$green+$counter; }
	else {$red=$red-$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLUE TO [ CYAN ] TO GREEN
$color=''; $red='0'; $green='0'; $blue='255';
while ($color!='rgb(0,255,0)'){
	if ($green<255){$red='0'; $blue='255'; $green=$green+$counter; }
	else {$blue=$blue-$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}
$output.='</tr><tr>';

// BLUE TO [ MAGENTA ] TO RED
$color=''; $red='0'; $green='0'; $blue='255';
while ($color!='rgb(255,0,0)'){
	if ($red<255){$green='0'; $blue='255'; $red=$red+$counter; }
	else {$blue=$blue-$counter; }
	$color='rgb('.$red.','.$green.','.$blue.')';
	$output.='<td onclick="prompt(\''.$txt['menuCustom']['styleSelectColor'].'\',\''.$color.'\')" class="colorBox" style="background-color:'.$color.'"></td>';
}

$output.='</tr><tr>';

$output.='</tr></table>';

?>