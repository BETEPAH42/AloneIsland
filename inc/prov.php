<?php
include_once '../classes/sql.php';
function make_seed()
{
	list($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
}
srand(make_seed());

$lt = date("H:i:s d.m.y");
$fulltime = date("H:i:s d.m.y");

define("UID", intval($_COOKIE["uid"]));
define("PASS", $_COOKIE["hashcode"]);
define("OPTIONS", $_COOKIE["options"]);
define("SPASS", $_COOKIE["spass"]);
$images = "images";

$pers = catch_user(UID);
define("USER", $pers["user"]);
$lastom_old = $pers["lastom"];
echo '<script src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/yourpers.js"></script>
<LINK href=/css/main.css rel=STYLESHEET type=text/css>
<LINK href=/css/selectbox.css rel=STYLESHEET type=text/css>
<script language=javascript src="/js/pers.js"></script>
<script language=javascript src="/js/statsup.js"></script>
<script language=javascript src="/js/sell.js"></script>
<script language=javascript src="/js/w.js"></script>
<script src="/js/fightn.js"></script>
<script src="/js/tools/scrollto.js"></script>
<script src="/js/newup.js"></script>';


// <script src='js/fight.js'></script>
// 	<script src='js/pers.js'></script>
// 	<script src='js/yourpers.js'></script>
// 	<script src='js/newup.js'></script>
// 	<script src='js/fightn.js'></script>";
//if (@!$DONT_CHECK)
if ($pers["action"] != -1)
	$lastom_new = tme();
else
	$lastom_new = update_user(UID);

if (@!$DONT_CHECK)
	detect_user($pers["uid"], $pers["pass"], $pers["block"], $pers["action"], $pers["waiter"], $pers["flash_pass"]);

define("GD_HUMANHEAL", 1); // - мгновенное выздравление после боев с людьми

#
$tmp = SQL::q1("SELECT COUNT(*) as count FROM p_auras WHERE uid=" . $pers["uid"] . " and special=5 and esttime>" . tme() . "");
$_TRVM = ($tmp['count']) ? 0 : 0; //1:0 запрет выхода из закладки персонаж или рюкзак при тяжёлой травме 
$tmp = SQL::q1("SELECT COUNT(*) as count, esttime FROM p_auras WHERE uid=" . $pers["uid"] . " and special=14 and esttime>" . tme());
$_MINE = ($tmp['count']) ? mtrunc($tmp['esttime'] - tme()) : 0;
$tmp = SQL::q1("SELECT COUNT(*) as count, esttime FROM p_auras WHERE uid=" . $pers["uid"] . " and special=15 and esttime>" . tme());
$_UMINE = ($tmp['count']) ? mtrunc($tmp['esttime'] - tme()) : 0;
$tmp = SQL::q1("SELECT COUNT(*) as count, esttime FROM p_auras WHERE uid=" . $pers["uid"] . " and special=17 and esttime>" . tme());
$_STUN = ($tmp['count']) ? mtrunc($tmp['esttime'] - tme()) : 0;
# праздники 
$GOOD_DAY = 0;
if (date("m") == 1 && date("d") <= 7)	$GOOD_DAY = GD_HUMANHEAL;
if (date("m") == 2 && date("d") == 23)	$GOOD_DAY = GD_HUMANHEAL;
if (date("m") == 3 && date("d") == 8)	$GOOD_DAY = GD_HUMANHEAL;
if (date("m") == 5 && date("d") == 1)	$GOOD_DAY = GD_HUMANHEAL;
if (date("m") == 5 && date("d") == 9)	$GOOD_DAY = GD_HUMANHEAL;
$_NG = 0;
if ((date("m") == 10 and date("d") == 03) or (date("m") == 1 and date("d") <= 15))
	$_NG = 1;
#

/// Защита от двубраузерки
/*if (@!$DONT_CHECK)
{
setcookie("AloneHashCode",md5($lastom_new));
if (empty($_COOKIE["AloneHashCode"]) or $_COOKIE["AloneHashCode"]<>md5($lastom_old))
{
	$_POST=array();
	$_GET=array();
}
}*/
////

$world = SQL::q1("SELECT weather, weatherchange FROM world");
define("WEATHER", $world["weather"]);
$d = date("H");
if ($d > 22 or $d < 6) define("DAY_TIME", 0);
elseif ($d < 12) define("DAY_TIME", 1);
elseif ($d < 18) define("DAY_TIME", 2);
else define("DAY_TIME", 3);
unset($d);

if (@!$DONT_CHECK) {
	include("goloc.php");
	include("opveschenie.php");
	include("tournirs.php");
	include("inc/ap.php");
	include("inc/econom.php");
	include("inc/wears.php");
	remove_all_auras();
}

if (@!$DONT_CHECK) {
	if ($pers["cfight"] > 10 and $pers["curstate"] <> 4) 
		set_vars("cfight=0,refr=1", UID);
	if ($pers["cfight"] < 10 and $pers["curstate"] == 4) 
		set_vars("curstate=2,cfight=0,refr=1", UID);
	if ($pers["curstate"] == 4) 
		include('inc/inc/battle.php');
}
// ^ Нападения бота
//Вытаскивание из бага

$f = explode("|", $_COOKIE["filter1"]);
$_FILTER["lavkatype"] = $f[0];
$_FILTER["lavkaminlevel"] = $f[1];
$_FILTER["lavkamaxlevel"] = $f[2];
$_FILTER["lavkamaxcena"] = $f[3];
$_FILTER["lavkasort"] = $f[4];
$_FILTER["sort"] = $f[5];
$_FILTER["h_zn_show"] = $f[6];
$_FILTER["show_z"] = $f[7];
$_FILTER["sorti"] = $f[8];
$_FILTER["sortp"] = $f[9];
$_FILTER["apps"] = $f[10];
$_FILTER["cat"] = $f[11];
$_FILTER["ar_loc"] = intval($f[12]);
$_FILTER["filter_f6"] = intval($f[13]);
$_FILTER["pers_sort"] = $f[14];
unset($f);

if ($pers["priveleged"]) 
	$priv = SQL::q1("SELECT * FROM priveleges WHERE uid=" . UID);

########Новый год
if ($pers["level"] > 0 and $pers["new_year"] > 1 and $pers["new_year"] < (tme()) and $pers["curstate"] != 4) {
	$bts = SQL::q1("SELECT * FROM bots WHERE level=" . ($pers["level"]) . " and special=1;");
	$bb = "bot=" . $bts["id"];
	begin_fight($pers["user"], $bb, "Нападение деда мороза", 50, 900, 1, 0, 0, 1, 1);
	set_vars("new_year=0", $pers["uid"]);
}
