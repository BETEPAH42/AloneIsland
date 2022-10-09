<?
	error_reporting(0);
	include ("../configs/config.php");
	include ("../inc/functions.php");
 
	$q = "and width=".intval($_GET["width"])." ";
	if (@$_GET["height"]<>'*') $q .= "and height=".intval($_GET["height"])."";
	if (empty($_GET["width"])) $q = '';
	$sql = sql::q("SELECT address FROM images WHERE type=".intval($_GET["type"])." ".$q."");
	
	$check = 1;
	foreach($sql as $s) {
		$check++;
		echo $s["address"].'|';
	}
	
	if ($check==1) echo 'none';
