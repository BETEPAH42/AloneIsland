<?
error_reporting(E_ALL);
if (@$_GET["do_w"]) {
	include("watchers.php");
	exit;
}
require_once 'classes/sql.php';
include_once 'inc/functions.php';

if (isset($_GET["p"])) {
	$UNAME = $_GET["p"];
	$UNAME = str_replace("'", "", $UNAME);
}

if (@$_GET["id"])
	$pers = SQL::q1("SELECT * FROM `users` WHERE `uid`=" . intval($_GET["id"]) . "");
else
	$pers = SQL::q1("SELECT * FROM `users` WHERE `smuser`='" . strtolower($UNAME) . "'");
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
	echo "<LINK href=main.css rel=STYLESHEET type=text/css><font class=timef>Запрещёно. Ожидаем входа в игру данного персонажа.</font><SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";
	exit;
}

if (empty($pers["uid"])) {
	echo "<LINK href=main.css rel=STYLESHEET type=text/css><center class=but><center class=puns><br><br>Нет Такого персонажа.[" . $UNAME . "]<br><br><br></center></center><SCRIPT LANGUAGE=\'JavaScript\' SRC=\'js/c.js\'></SCRIPT>";
	exit;
}

if ((substr_count($you["rank"], "<pv>") or $you["sign"] == 'c2')) {

	// зашел сюда
	// echo "1<br>";

	echo '<title>[' . $pers["user"] . '] Одинокие земли</title><frameset rows="*,20" FRAMEBORDER=0 FRAMESPACING=2 BORDER=0 id="frmset">';
	echo '<frame src="info.php?p=' . $pers["user"] . '&no_watch=1" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0 style="border-bottom-width: 2px; border-bottom-style: solid; border-bottom-color: #666666" name="Fr1">';
	echo '<frame src="watchers.php?id=' . $pers["uid"] . '" scrolling=auto FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 MARGINWIDTH=0 MARGINHEIGHT=0 name="Fr2">';
	echo '</frameset>';
	exit;
} else echo '<script type="text/javascript" src="js/info.js?4"></script>';

if (empty($_GET["self"])) {
	// debag($pers['user']);
	include('info/game.php');
} else {
	// debag($pers['user']);
	include('info/self.php');
}
