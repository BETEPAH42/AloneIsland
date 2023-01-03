<?php

error_reporting(E_ALL);

include_once "../inc/functions.php";
$DONT_CHECK = 1;

include_once "../inc/prov.php";
include_once "../inc/locations/quest/quests.php";
echo $pers["name"];
if ($pers["waiter"] <= tme() and ($pers["action"] == -1 or $pers["cfight"] or $pers["refr"]))
	echo "<script>top.frames['main_top'].location='../main.php';</script>";
$qWitch = sql::q1("SELECT * FROM quest WHERE id = " . Q_WITCH . "");
$qFish = sql::q1("SELECT * FROM quest WHERE id = " . Q_FISH . "");
$ALIS = '';
#

$tmp = sql::q1("SELECT COUNT(*) as count FROM p_auras WHERE uid=" . $pers["uid"] . " and special=5 and esttime>" . tme());
// debag($tmp);
$_TRVM = ($tmp['count']) ? 0 : 0; //1:0 запрет передвижения при тяжёлой травме

#

echo "<div id=error>";
if (@$_GET["go_nature"] and $_TRVM) {
	echo "<center class=puns>Вы не можете перемещатся у вас тяжелая травма.</center>";
	unset($_GET["go_nature"]);
}

if (@$_GET["go_nature"] and $pers["gain_time"] > (tme() - 1200))
	unset($_GET["go_nature"]);

$t = time();
$GOED = 0;
if (@$_GET["go_nature"] and $pers["tire"] > 100) echo "<center class=hp>Вы слишком устали!</center>";
else
	if (@$_GET["go_nature"] and $t >= $pers["waiter"] and $pers["cfight"] == 0 and $pers["tire"] < 101 and !$_TRVM) {
	if (abs(10 + ($pers["sm3"] + $pers["s4"]) * 10) < $pers["weight_of_w"])
		echo "<center class=puns>Вы перегружены!</center>";
	else {
		$x = intval($_GET["gox"]);
		$y = intval($_GET["goy"]);
		if ((($x - $pers["x"]) * ($x - $pers["x"]) + ($y - $pers["y"]) * ($y - $pers["y"])) > 2) {
			$x = $pers["x"];
			$y = $pers["y"];
		}

		if ($pers["x"] <> $x or $pers["y"] <> $y) {
			$GOED = 1;
			$pers["x"] = $x;
			$pers["y"] = $y;
			$place = sql::q1("SELECT type FROM nature WHERE x=" . $x . " and y=" . $y . "");
			if (isset($place["type"])) {
				$tr = 1.3;
				if ($place["type"] == 0) $wait = 0;
				else {
					$wait = mtrunc(floor(($place["type"] * 10 + 10) - ($pers["sp8"] / 8)));
					if (WEATHER == 2) $wait += 5;
					if (WEATHER == 3) {
						$wait += 12;
						$tr += 2;
					}
					if (WEATHER == 4) {
						$wait -= 3;
						$tr += 1;
					}
					if (WEATHER == 6) {
						$wait *= 1.5;
						$tr += 0;
					}
					if (WEATHER == 7) {
						$wait += 60;
						$tr += 0;
					}
					if (WEATHER == 6) {
						$wait += 5;
						$tr += 0;
					}
					if ($wait < 2 and $place["type"] != 0) $wait = 2;
					if (WEATHER == 7 and rand(1, 100) < 10) {
						$zid = sql::q1("SELECT id FROM auras WHERE special=3 ORDER BY RAND()")['id'];
						$a = aura_on2($zid, $pers);
						$str =  '«<font class=red><B>' . $a["name"] . '.</B> <i>' . $a["describe"] . '</i></font>»';
						say_to_chat("s", "На вас обрушились огромные градины и вы получили травму:" . $str . ".", "1", $pers["user"], $pers["location"], date("H:i:s"));
					}
				}
				if ($tr < 1) $tr = 1;
				set_vars("tire=tire + " . $tr . ",x=" . $x . ",y=" . $y . ",waiter='" . ($wait + time()) . "',sp8=sp8+" . ($wait / ($pers["sp8"] * 2 + 1)) . "", UID);
				$pers["tire"] += $tr;
				$pers["waiter"] = $wait + time();
				$cell = sql::q1("SELECT * FROM nature WHERE x=" . $x . " and y=" . $y . "");
				if ($cell["belong"] != 0 and $cell["belong"] != $pers["uid"]) {
					say_to_chat("s", "Вашу местность[" . $x . "," . $y . "] посетил <b>" . $pers["user"] . "</b>.", "1", ($cell["belong"]), "*");
				}
			}
		}
		if ($pers["x"] == $qWitch["lParam"] && $pers["y"] == $qWitch["zParam"] && !$qWitch["finished"])
			$ALIS = "<center class=but><b class=user>Вы нашли ведьму Алису! <hr><img src=/images/design/warningblue.gif> <i>Обновите экран.</i></b></center>";
		if ($pers["x"] == $qFish["lParam"] && $pers["y"] == $qFish["zParam"] && $qFish["finished"] == 0)
			$ALIS = "<center class=but><b class=user>Вы нашли Рыбака! <hr><img src=/images/design/warningblue.gif> <i>Обновите экран.</i></b></center>";
	}
}


if ($t < $pers["waiter"]) {
	$resources = "<hr>";
	$www = 0;
}
echo "</div>";

$x = $pers["x"];
$y = $pers["y"];
$wwid = WEATHER;
if (date("H") > 21 or date("H") < 7) $wwid += 10;
$ww = sql::q1("SELECT * FROM weather WHERE id=" . WEATHER . "");

include("map/moving.php");
include("map/cell.php");
$bd_str = '';
#part1
echo "<div id=d1>";
echo "<div class=but><script>";
echo "var go_str = '" . $maked_str . "';";
echo "var bd_str = '" . $bd_str . "';";
echo "</script>	  " . $ALIS . "
<font class=green>Усталость: <b>" . floor($pers["tire"]) . "%</b></font>
<a class=bg href=\"javascript:show_mmap(" . $pers["x"] . "," . $pers["y"] . ")\">Миникарта</a>
</div>
<div class=but>
";
$all_weight = sql::q1("SELECT SUM(weight) as w FROM `wp` WHERE uidp=" . $pers["uid"] . " AND in_bank=0")['w'];
if (intval($all_weight) <> $pers["weight_of_w"])
	set_vars("weight_of_w=" . intval($all_weight), UID);
$pers["weight_of_w"] = intval($all_weight);
if (abs(10 + ($pers["sm3"] + $pers["s4"]) * 10) < ($all_weight))
	echo " <font class=hp>Вы перегружены!</font>";
$weight = "Масса вещей: [" . ($pers["weight_of_w"]) . "/" . abs(10 + ($pers["sm3"] + $pers["s4"]) * 10) . "]";
$money = "Деньги: <b>" . $pers["money"] . "LN</b> [" . $ww["name"] . "]";
$user_str = "<img src=images/p.gif title=\"Приват\" onclick=\"top.say_private(\\'" . $pers["user"] . "\\',1)\" style=\"cursor:pointer\"><b class=Luser>";
$user_str .= substr($pers["user"], 0, 20) . "</b> <font class=Llvl>[" . $pers["level"] . "]</font> - <font class=Lgreen title=Усталость>" . round($pers["tire"]) . "%</font>";
$user_str .= "[<b class=small style=color:#FF9999>" . $pers["chp"] . "/" . $pers["hp"] . "</b> | ";
$user_str .= "<b class=small style=color:#9999FF>" .  $pers["cma"] . "/" . $pers["ma"] . "</b>]";

// if ($pers["priveleged"]) {
// 	echo "</div><hr><div>
// <i>" . debag($cell_type) . "</i>
// </div><br><div id=weeether>
// <img border=0 src='/images/weather/top/" . $wwid . ".gif' width=145 height=145 style='border-style: outset; border-width: 3; border-color:#FFFFFF;'>
// </div>";
// 	if ($priv["emap"]) {
// 		echo "<li><a class=bga href=main.php?go=map_edit>Редактор карты</a></li>";
// 	}
// }
echo "</div>";
#########

#part2
echo "<div id=d2>";

$bdg = sql::q1("SELECT x,y,type,name FROM buildings WHERE x=" . ($x) . " and y=" . ($y) . "");

if ($bdg) {
	echo "<div class='gg' style='width:80px;height:80px;cursor:pointer;background-image: url(\"images/map/" . ($x + 22) . "_" . ($y + 26) . ".jpg\");'><img src=images/buildings/" . $bdg["type"] . ".gif></div>";
}

$win_button = '<div class=but2><center><a class=but href=main.php?wcell=start>Отвоевать</a></center>Стоимость: 50 LN. Требуется 20 минут не сходить с этой местности и побеждать все бои.</div>';

if ($cell["buildable"]) {
	echo "<center class=but2>Эта местность пригодна для строительства!</center>";
}
if ($cell["teleport"]) {
	$TPs = sql::q("SELECT name,x,y FROM nature WHERE teleport>0 and not (x=" . $x . " and y=" . $y . ")");
	$SEL = '';
	foreach ($TPs as $TP) {
		$SEL .= "<option value='" . $TP["x"] . "_" . $TP["y"] . "'>" . $TP["name"] . "[" . $TP["x"] . ":" . $TP["y"] . "]</option>";
	}
	echo "<center class=but2>Вы нашли телепорт!<br/>
		<form action=main.php method=post>
		<select name=teleport style='width:200px'>
		" . $SEL . "
		</select><br>";
	if ($pers["money"] >= $cell["teleport"])
		echo "<input type=submit value='Телепортироваться[" . $cell["teleport"] . " LN]' class=login  style='width:200px'>";
	else
		echo "<input type=submit value='Телепортироваться[" . $cell["teleport"] . " LN]' class=login DISABLED  style='width:200px'>";
	echo "</form></center>";
}

if ($cell["winnable"] and $cell["belong"] != $pers["uid"]) {
	echo "<center class=but>Эта местность пригодна для завоевания.</center>" . $win_button;
}
if (!$cell["belong"] and $cell["winnable"])
	echo "<center class=but><p class=green>Никому не принадлежит.</p></center>";
elseif ($cell["winnable"]) {
	$belong = catch_user($cell["belong"]);
	echo "<center class=but>Принадлежит: <br><img src='images/signs/" . $belong["sign"] . ".gif'  title='" . $belong["clan_name"] . "'><b class=user>" . $belong["user"] . "</b><font class=lvl>[" . $belong["level"] . "]</font> <a target=_blank href='info.php?" . $belong["user"] . "'><img src=images/i.gif></a></center>";
}

$free_for_work = false;
if ($cell["belong"] == $pers["uid"] or !$cell["winnable"]) {
	$free_for_work = true;
}


echo "<div class=but>";
echo "<b><i>Ресурсы:</i></b>" . $resources . "<br>";

if ($cell["fishing"] and $free_for_work)
	// 
	echo "<span class=login value='Рыбалка' onclick=\"location='main.php?fishing=on&" . tme() . "';\" style='width:90%; padding: 0 10px;margin-left:15px; cursor:pointer; border-radius:3px;'>Рыбалка</span>";
include_once './inc/locations/cariers/fishing.php';
// if ($cell["fishing"] and $free_for_work)
// 	echo "<span class=login  onclick=\"location='main.php?fishingnew=on&" . tme() . "'\" style='width:90%; padding: 0 10px;margin-left:15px; cursor:pointer; border-radius:3px;'>Рыбалка (новаяS)</span>";
// include_once '../inc/locations/cariers/fishing_new.php';
if ($cell["herbal"] and $free_for_work) echo "<span class=login onclick=\"location='main.php?herbal=on&" . tme() . "';document.getElementById('outer').style.display = 'block';\" style='width:90%; padding: 0 10px;margin-left:15px; cursor:pointer; border-radius:3px;'>Осмотр трав</span>";
if ($cell["wood"] <> 9 and $cell["wood"] <> 0 and $free_for_work) echo "<span class=login value='Осмотр деревьев' onclick=\"location='main.php?wood=on&" . tme() . "'\" style='width:90%; padding: 0 10px;margin-left:15px; cursor:pointer; border-radius:3px;'>Осмотр деревьев</span>";
elseif ($cell["wood"] == 9 and $free_for_work and $pers["quest_NY"] == '1_1') echo "<span class=login value='Осмотр деревьев' onclick=\"location='main.php?wood=on&" . tme() . "'\" style='width:90%; padding: 0 10px;margin-left:15px; cursor:pointer; border-radius:3px;'>Осмотр деревьев</span>";

echo "</div>";
if ($free_for_work) {
	include("../inc/locations/inc/bot_cell.php");
	echo "<center class=inv_but style='overflow-x:hidden;height:120px;'>" . $TXT . "</center>";
}

if ($pers["priveleged"]) {
	echo "<center><a class=wbut href='main.php?go=map_edit'>Редактор</a></center>";
}

echo "</div>";

#########

echo "<script>";
if ($cell["go_id"])
	$out = SQL::q1("SELECT name FROM `locations` WHERE `id`='" . $cell["go_id"] . "'");
else
	$out["name"] = '';
echo "var m_name = '" . $out["name"] . "';";
echo "var m_code = '" . addslashes(build_go_string($cell["go_id"], $pers["lastom"])) . "';";
echo "top.frames[\"main_top\"].aX = " . $x . ";";
echo "top.frames[\"main_top\"].aY = " . $y . ";";
echo "var _W = '" . $weight . "';";
echo "var _M = '" . $money . "';";
echo "var _U = '" . $user_str . "';";
echo "</script>";

/*
echo "alert('".$x."_".$y."');";
*/
?>
<script>
	<?
	if ($GOED)
		echo "top.frames['main_top'].waiterSPEC(" . mtrunc($pers["waiter"] - $t) . ");";
	if ($pers["gain_time"] > (tme() - 1200))
		echo "top.frames['main_top'].waiterSPEC(" . mtrunc($pers["gain_time"] - (tme() - 1200)) . ",'Завоевание местности...<br><a class=button href=main.php?wcell=abort>Отменить</a>');";
	if (date("H") > 6 and date("H") < 22) $day = 1;
	else $day = 0;
	echo "top.frames['main_top'].ready_nature(" . $pers["chp"] . "," . $pers["hp"] . "," . $pers["cma"] . "," . $pers["ma"] . "," . intval($sphp) . "," . intval($spma) . "," . $day . ");";
	?>
</script>