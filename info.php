<?php
error_reporting(E_ALL);
if (@$_GET["do_w"]) {
	include("watchers.php");
	exit;
}
require_once 'classes/sql.php';
include_once 'inc/functions.php';
$UNAME = '';

if (isset($_GET["p"])) {
	$UNAME = $_GET["p"];
	$UNAME = str_replace("'", "", $UNAME);
}

if (@$_GET["id"])
	$pers = SQL::q1("SELECT * FROM `users` WHERE `uid`=" . intval($_GET["id"]) . "");
else {

	$pers = SQL::q1("SELECT * FROM `users` WHERE `smuser`='" . strtolower($UNAME) . "'");
}

$locname = SQL::q1("SELECT * FROM `locations` WHERE `id`='" . $pers["location"] . "' ;");
#### Призраки для битвы на арене:
if ($pers["ctip"] == -1) {
	$pers["block"] = '';
	$pers["prison"] = '';
	$pers["online"] = 1;
	$pers["tire"] = 0;
	$pers["lastom"] = tme();
	$pers["timeonline"] = 3600;
	$pers["lastvisits"] = tme() - 800 - rand(100, 500);
	$pers["cfight"] = $pers["silence"];
	$pers["action"] = 0;
}

if (substr_count($pers["aura"], "invisible")) {
	$pers["online"] = 0;
	$pers["chp"] = $pers["hp"];
	$pers["cma"] = $pers["hp"];
	$pers["cfight"] = 0;
}
$you = catch_user(intval($_COOKIE["uid"]), $_COOKIE["hashcode"], 1);

if ($you["block"]) unset($you);
if ($you["pass"] == $_COOKIE["hashcode"]) {
	$_SESSION["sign"] = $you["sign"];
	$_SESSION["user"] = $you["user"];
	if ($you["diler"]) $you["rank"] .= "<diler><molch><pv>";
	$_SESSION["rank"] = $you["rank"];
}

if (($pers["action"] == -10 or $pers["action"] == -11) and $you["uid"] <> 5 and $you["sign"] <> 'c2') {
	echo "<LINK href=main.css rel=STYLESHEET type=text/css>
	<font class=timef>Запрещёно. Ожидаем входа в игру данного персонажа.</font>
	<SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";
	exit;
}

if (empty($pers["uid"])) {
	echo "<LINK href=main.css rel=STYLESHEET type=text/css>
	<center class=but>
		<center class=puns>
			<br>
			<br>Нет Такого персонажа.[" . $UNAME . "]<br>
			<br>
			<br>
		</center>
	</center>
	<SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";
	exit;
}
// var_dump($pers);
if (substr_count($you["rank"], "<pv>") || $you["sign"] == 'c2') {
	?>
	<title>Персонаж <?=$pers["user"];?></title>
		<div style="width:90%;min-height:250px;border:1px;border-radius:5px; margin: 10px auto;">
			<div  style="width:100%; text-align:center;margin: 10px 0;"><?=$pers["user"];?> [<?=$pers["level"]?>]</div>
			<div style="display: flex;flex-wrap: nowrap;justify-content: center;">
				<div style="width: 250px;display: flex;flex-direction: column;">
					<div style="width: 100%; height:30px;background-image: url('images/DS/blackbg.jpg'); position: relative;">
						<div id="chp" style="width:100%;height:15px;position:absolute; top:5px; left:0; text-align:center; color:#FFFFFF; font-size:8px; font-family: Verdana;overflow:hidden;z-index:2;font-weight: bold;"><?=$pers["chp"];?>/<?=$pers["hp"];?></div>
						<div id="cma" style="width:100%;height:15px;position:absolute; top:18px; left:0; text-align:center; color:#FFFFFF; font-size:8px; font-family: Verdana;overflow:hidden;z-index:2;font-weight: bold;"><?=$pers["cma"];?>/<?=$pers["ma"];?></div>
					</div>
					<div style="width: 100%;display:flex;flex-direction: row;justify-content: space-between;">
						<div style="width: 62px;">
							<img src="images/weapons/slots/slot1.gif" width="62" height="20">
							<img src="images/weapons/slots/pob9.gif" width="62" height="40">
							<img src="images/weapons/slots/pob10.gif" width="62" height="40">
							<img src="images/weapons/slots/pob3.gif" width="62" height="91">
							<img src="images/weapons/slots/pob14.gif" width="62" height="90">
							<div style="display: flex;">
								<img src="images/weapons/slots/pob15.gif" width="31" height="31">
								<img src="images/weapons/slots/pob16.gif" width="31" height="31">
							</div>
						</div>
						<div style="width: 126px;display:flex;flex-direction: column;align-items: center;justify-content: flex-end;">
							<div >
								<img src="images/persons/<?=$pers["pol"];?>_<?=$pers["obr"];?>.gif" title="BETEPAH" width="126">
							</div>	
							<div style="height: 31px;">
								<img src="images/weapons/slots/pob12.gif" width="31" height="31">
								<img src="images/weapons/slots/pob13.gif" width="31" height="31">
							</div>
						</div>
						<div style="width: 62px;">
							<img src="images/weapons/slots/pob1.gif" width="62" height="65">
							<img src="images/weapons/slots/pob2.gif" width="62" height="35">
							<img src="images/weapons/slots/pob11.gif" width="62" height="91">
							<img src="images/weapons/slots/pob4.gif" width="62" height="30">
							<img src="images/weapons/slots/pob8.gif" width="62" height="60">
							<div style="display: flex;">
								<img src="images/weapons/slots/pob17.gif" width="31" height="31">
								<img src="images/weapons/slots/pob18.gif" width="31" height="31">
							</div>
						</div>
					</div>	
				</div>
				<div style="display: flex;flex-direction: column;align-content: center;width: 350px;">
					<div style="text-align: center;">
						Характеристики персонажа
					</div>
					<div style="width: 90%;margin: 5px auto;">
						<div style="font-family: Tahoma, 'Trebuchet MS', Trebuchet, Verdana, Arial;font-size: 16px; color: #D1FC4E;text-align:center;">Основные:</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s1.png"></div>
							<div style="width:100%;">Сила</div>
							<div style="width:30px;"><?=$pers["s1"]?></div>
						</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s2.png"></div>
							<div style="width:100%;">Реакция</div>
							<div style="width:30px;"><?=$pers["s2"]?></div>
						</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s3.png"></div>
							<div style="width:100%;">Удача</div>
							<div style="width:30px;"><?=$pers["s3"]?></div>
						</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s4.png"></div>
							<div style="width:100%;">Здоровье</div>
							<div style="width:30px;"><?=$pers["s4"]?></div>
						</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s5.png"></div>
							<div style="width:100%;">Интелект</div>
							<div style="width:30px;"><?=$pers["s5"]?></div>
						</div>
						<div style="display:flex;margin: 2px 0;flex-direction: row;align-content: center;align-items: center;">
							<div style="width:30px;"><img src="images/DS/stats_s6.png"></div>
							<div style="width:100%;">Сила Воли</div>
							<div style="width:30px;"><?=$pers["s6"]?></div>
						</div>
						<div style="font-family: Tahoma, 'Trebuchet MS', Trebuchet, Verdana, Arial;font-size: 16px; color: #D1FC4E;text-align:center;">Дополнительные:</div>
						<div>Сила</div>
						<div>Звание</div>
						<div>Сила</div>
					</div>
				</div>
			</div>
		</div>
	<?php

	// echo '<title>[' . $pers["user"] . '] Одинокие земли</title><frameset rows="*,20" FRAMEBORDER=0 FRAMESPACING=2 BORDER=0 id="frmset">';
	// echo '<frame src="info.php?p=' . $pers["user"] . '&no_watch=1" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0 style="border-bottom-width: 2px; border-bottom-style: solid; border-bottom-color: #666666" name="Fr1">';
	// echo '<frame src="watchers.php?id=' . $pers["uid"] . '" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0 name="Fr2">';
	// echo '</frameset>';

	exit;
} else {
	echo '<script type="text/javascript" src="js/info.js?4"></script>';

}

if (empty($_GET["self"])) {
	//debag($pers['user']);
	include('info/game.php');
} else {		
	//var_dump($pers);
	include('info/self.php');
}
