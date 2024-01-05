<?php
error_reporting(E_ALL);
####// Главная таблица
echo "<table border=0 style='width:100%;background-image:url(\"images/bg.png\");' cellspacing=0 cellpadding=0>
<tr><td valign=top align=center style='width:250px;background-color:#EEEEEE;'>";
####//
echo show_pers_in_f($pers, 3);

####//
echo "</td><td id='fight' valign=top style='background-color:#EEEEEE;'></td>
<td valign=top align=right style='width:250px;background-color:#EEEEEE;'>";

####//
function endBattle($id) {
	global $pers;
	SQL::q1("UPDATE fights SET type='f', turn='finish' WHERE id=".$id);
	SQL::q1("UPDATE users SET cfight=0 WHERE id=".$pers['id']);
}
if ($pers["chp"] > 0 and $persvs["chp"] > 0)
	echo show_pers_in_f($persvs, 1);
else
	echo "<img src='images/battle_art/" . ($fight["id"] % 12 + 1) . ".jpg'>";
####//
echo "</td></tr></table>";
$radius = SQL::q1("SELECT MAX(radius) as max FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='orujie'")['max'];
if ($radius < 1) 
	$radius = 1;

$NEAR = ($pers["fstate"] < 2 and $radius < (floor(sqrt(sqr($pers["xf"] - $persvs["xf"]) + sqr($pers["yf"] - $persvs["yf"])))) and intval($fight["bplace"])) ? 0 : 1 ;
####// Finished.
	### - JS\ON - ##
	// $arr = get_included_files();
	// echo "<pre>";
				// var_dump($persvs);
	// 	echo "</pre>";
	// if ($options[7] <> "no")
	// var arrow_name='" . ARROW_NAME . "';
	echo "<script>
		top.flog_set(); 
		var NEAR = ".$NEAR.";
		var _closed = " . intval($fight["closed"]) . ";
		var whatteam=" . $pers["fteam"] . ";
		var level=" . $pers["level"] . ";
		var logid = '" . $pers["cfight"] . "';
		var arrow_name='test';
		var x=" . intval($pers["xf"]) . ";var y=" . intval($pers["yf"]) . ";
		var mid='" . intval($fight["bplace"]) . "';
		var maxx=" . intval($fight["maxx"]) . ";
		var maxy=" . intval($fight["maxy"]) . ";
		var damage_get=" . intval($pers["damage_get"] - $pers["chp"]) . ";
		var damage_give=" . intval($pers["damage_give"]) . ";
		var persvs_id='" . base64_encode($persvs["id"] . $persvs["uid"]) . "';
		var ffreq = '" . $pers["fight_request"] . "';
		var your_img = '" . $pers["pol"] . "_" . $pers["obr"] . "';
		</script>";
		if ($persvs["invisible"])
			echo "<script>var vs_img = 'male_invisible';";
		else
			echo "<script>
			var vs_img = '" . $persvs["pol"] . "_" . $persvs["obr"] . "';";

		if ($pers["hp"] > $pers["chp"] and (($pers["hp"] - $pers["chp"]) <= $pers["cma"])) {
			echo "var up_health=" . ($pers["hp"] - $pers["chp"]) . ";
			</script>";
		}
		else {
			echo "var up_health=0;
			</script>";
		}

	$go_no_p = '|'; // -  Переменная определяющая клетки куда ходить нельзя.
	//////////////// Командs
	$p_t1 = SQL::q("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,invisible FROM users WHERE cfight=" . $pers["cfight"] . " and fteam=1 and chp>0");
	$b_t1 = SQL::q("SELECT user,chp,level,hp,id,xf,yf,cma,ma FROM bots_battle WHERE cfight=" . $pers["cfight"] . " and fteam=1 and chp>0");
	$p_t2 = SQL::q("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,invisible FROM users WHERE cfight=" . $pers["cfight"] . " and fteam=2 and chp>0");
	$b_t2 = SQL::q("SELECT user,chp,level,hp,id,xf,yf,cma,ma FROM bots_battle WHERE cfight=" . $pers["cfight"] . " and fteam=2 and chp>0");
	$LIFE1 = 0; // - Кол-во живых игроков в команде 1 включая ботов
	$LIFE2 = 0; // - Кол-во живых игроков в команде 2 влючая ботов
	$BOTS1 = 0; // Боты в 1ой команде
	$BOTS2 = 0; // боты во 2ой команде
	$_CAN_TURN = 0; // - Можно ли сходить
	#################################################################
	$cans = SQL::q("SELECT uid2 FROM turns_f WHERE uid1=" . $pers["uid"] . "");
	$uids = '';
	foreach ($cans as $c)
		$uids .= '<' . $c["uid2"] . '>';
	#################################################################
	$CAN_TURN = 0;
	/*
		Верхний блок нужен для получения строчки ИД персонажей кого ударил текущий персонаж. И если при переборе персонажей противоположной команды найдётся хотябы один чей ИД отсутствует в этой строке значит текущий персонаж может сходить.
		$CAN_TURN - отвечает за то может ли персонаж сходить. 0-нет 1-да.
	*/

	################# - Вывод команд  - ##################################################################################### 
	foreach ($p_t1 as $tmp) {
		$LIFE1++;
		if ($tmp["invisible"] > tme() and $tmp["uid"] <> $pers["uid"]) {
			$tmp["user"] = 'невидимка';
			$tmp["sign"] = 'none';
			$tmp["level"] = '??';
			$tmp["chp"] = 1;
			$tmp["hp"] = 1;
			$tmp["cma"] = 1;
			$tmp["ma"] = 1;
		}
		$str_p_1 .= $tmp["sign"] . "|" . $tmp["user"] . "|" . $tmp["level"] . "|" . $tmp["chp"] . "|" . $tmp["hp"] . "|" . $tmp["cma"] . "|" . $tmp["ma"] . "|" . $tmp["xf"] . "|" . $tmp["yf"] . "|" . base64_encode($tmp["uid"]) . "|@";
		$go_no_p .= $tmp["xf"] . "_" . $tmp["yf"] . "|";
		if ($pers["fteam"] <> 1 and !substr_count($uids, "<" . $tmp["uid"] . ">")) $CAN_TURN = 1;
	}
	foreach ($b_t1 as $tmp) {
		$LIFE1++;
		$BOTS1++;
		$str_p_1 .= "none|" . $tmp["user"] . "|" . $tmp["level"] . "|" . $tmp["chp"] . "|" . $tmp["hp"] . "|" . $tmp["cma"] . "|" . $tmp["ma"] . "|" . $tmp["xf"] . "|" . $tmp["yf"] . "|" . base64_encode($tmp["id"]) . "|@";
		$go_no_p .= $tmp["xf"] . "_" . $tmp["yf"] . "|";
		#####
		if ($pers["fteam"] <> 1) $CAN_TURN = 1;
		#####
	}
	$team2 = "";
	foreach ($p_t2 as $tmp) {
		$LIFE2++;
		if ($tmp["invisible"] > tme() and $tmp["uid"] <> $pers["uid"]) {
			$tmp["user"] = 'невидимка';
			$tmp["sign"] = 'none';
			$tmp["level"] = '??';
			$tmp["chp"] = 1;
			$tmp["hp"] = 1;
			$tmp["cma"] = 1;
			$tmp["ma"] = 1;
		}
		$team2 .= $tmp["sign"] . "|" . $tmp["user"] . "|" . $tmp["level"] . "|" . $tmp["chp"] . "|" . $tmp["hp"] . "|" . $tmp["cma"] . "|" . $tmp["ma"] . "|" . $tmp["xf"] . "|" . $tmp["yf"] . "|" . base64_encode($tmp["uid"]) . "|@";
		$go_no_p .= $tmp["xf"] . "_" . $tmp["yf"] . "|";
	#####
		if ($pers["fteam"] <> 2 and !substr_count($uids, "<" . $tmp["uid"] . ">")) $CAN_TURN = 1;
		#####
	}

///////////// Выводит всех ботов в первой  команде::
foreach ($b_t2 as $tmp) {
	$LIFE2++;
	$BOTS2++;
	$team2 .= "none|" . $tmp["user"] . "|" . $tmp["level"] . "|" . $tmp["chp"] . "|" . $tmp["hp"] . "|" . $tmp["cma"] . "|" . $tmp["ma"] . "|" . $tmp["xf"] . "|" . $tmp["yf"] . "|" . base64_encode($tmp["id"]) . "|@";
	$go_no_p .= $tmp["xf"] . "_" . $tmp["yf"] . "|";
	#####
	if ($pers["fteam"] <> 2) $CAN_TURN = 1;
	#####
}
	echo "<script>

	var team1 = '".$str_p_1."';";

	// Выводит всех людей в первой команде:

	///////////// Выводит всех ботов в первой  команде::

	###########################################################################################################
	
	// Выводит всех людей в первой команде:

echo "var team2 = '{$team2}';
</script>";

#######################################################################################

if (($LIFE1 - $BOTS1) == 0 and ($LIFE2 - $BOTS2) == 0 and $LIFE1 and $LIFE2 and $fight["type"] != 'f') // в бою остались одни боты
	{

	include("inc/bots/bots_battle.php");
}
#############################################
echo "<script>
var go_no='" . $bplace["xy"] . $go_no_p . "';";
$timeout = mtrunc($fight["ltime"] + $fight["timeout"] - time());

if ($OD_UDAR + 3 <= (round($pers["sb1"]) + 5))
	echo "var od = " . (round($pers["sb1"]) + 5) . ";";
else
	echo "var od = " . ($OD_UDAR + 3) . ";";
echo "var od_udar = " . ($OD_UDAR) . ";";
echo "show_fight_head(" . intval($fight["oruj"]) . "," . intval($fight["travm"]) . "," . $timeout . ");
</script>";

#############################################
$_FINISHED = 0;
if ($LIFE1 == 0 or $LIFE2 == 0 or $fight["type"] == 'f') {
	// echo "<script>console.log('183');";
	include('inc/inc/fights/finish.php');
	// echo "</script>";
	$_FINISHED = 1;
}
// echo "</script>";
// var_dump("hi");
// echo "<script>";
## Для завоевания. Если происходит завоевание и щёлкает таймаут - проигрыш и конец завоевания.
if ($pers["gain_time"] and $timeout == 0 and $BOTS1 == $LIFE1 and !$_FINISHED) {
	$LIFE2 = 0;
	include('inc/inc/fights/finish.php');
}
#############################################
## Таймаут с ботом.
/*
if ($timeout==0 and $BOTS1==$LIFE1 and !$_FINISHED)
{
	$LIFE2 = 0;
	include ('inc/inc/fights/finish.php');
}
*/
#############################################
elseif ($pers["chp"] > 0 and !$_FINISHED and $CAN_TURN) # - Твой ХОД
{
	################################### -  МАГИЯ - ################################################
	$kblast = 1;
	$kaura = 0;
	$kkid = 0;
	// $idall = defined(BOOK_INDEX) ? explode("|", BOOK_INDEX) : "";
	$img = [];
	$name = [];
	$idc = [];
	$arimg[0] = '';
	$arname[0] = '';
	$arid[0] = '';
	$kidimg[0] = '';
	$kidname[0] = '';
	$kidid[0] = '';
	if ($pers["fstate"] == 3) {	
		$bls = SQL::q("SELECT * FROM u_blasts WHERE uidp=" . $pers["uid"] . " and tlevel<=" . $pers["level"] . " and ts6<=" . $pers["s6"] . " and manacost<=" . $pers["cma"] . " and cur_colldown<=" . time() . " and cur_turn_colldown<=" . $pers["f_turn"]);
		foreach ($bls as $bl) {
			if ($bl["turn_colldown"])
				$colldown = 'Перезарядка: ' . $bl["turn_colldown"] . ' ход.';
			else
				$colldown = 'Перезарядка: ' . $bl["colldown"] . ' сек.';
			$bl["name"] = $bl["name"] . "|<b class=user>" . $bl["name"] . "</b><br><font class=ma>" . $bl["manacost"] . " MA</font><br>Удар: <b>" . $bl["udmin"] . "-" . $bl["udmax"] . "</b><br>" . $colldown . "<br>" . $bl["describe"];
			$img[$kblast] = $bl["image"];
			$name[$kblast] = $bl["name"];
			$idc[$kblast] = $bl["id"];
			$kblast++;
		}
		$as = SQL::q("SELECT * FROM u_auras WHERE uidp=" . $pers["uid"] . " and tlevel<=" . $pers["level"] . " and ts6<=" . $pers["s6"] . " and manacost<=" . $pers["cma"] . " and cur_colldown<=" . time() . " and cur_turn_colldown<=" . $pers["f_turn"]);
		$txt = '';
		foreach ($as as $a) {

			$txt .= $a["image"] . '#' . $a["id"] . '#<b class=user>' . $a["name"] . '</b>@';
			if ($a["turn_colldown"])
				$colldown = 'Перезарядка: <b>' . $a["turn_colldown"] . 'ход.</b>';
			else
				$colldown = 'Перезарядка: <b>' . $a["colldown"] . 'сек.</b>';
			if ($a["turn_esttime"])
				$esttime = 'Время действия: <b>' . $a["turn_esttime"] . 'ход.</b>';
			else
				$esttime = 'Время действия: <b>' . $a["esttime"] . 'сек.</b>';
			if ($a["targets"] < 1) $a["targets"] = 1;
			$targets = '@<i>Целей не более ' . $a["targets"] . "</i>";
			if ($a["forenemy"] == 0)
				$forenemy = "<i class=green>На свою команду</i>";
			elseif ($bl["forenemy"] == 1)
				$forenemy = "<i class=red><b>На чужую команду</b></i>";
			else
				$forenemy = "<i class=blue>На любую команду</i>";
			$txt .= $esttime . '@' . $colldown . $targets . "@" . $forenemy;
			$params = explode("@", $a["params"]);
			foreach ($params as $par) {
				if($par == "") continue;
					$p = explode("=", $par);
				$perc = '';
				if (substr($p[0], 0, 2) == 'mf') $perc = '%';

				if ($p[1])
					$txt .= '@' . name_of_skill($p[0]) . ':<b>' . plus_param((int)$p[1]) . $perc . '</b>';
				
			}
			$txt .= '|';
		}

	}
	echo '<script>
	var can_turn = ' . intval($CAN_TURN) . ';
	var n = ' . $kblast . ';
	img = new Array();
	id = new Array();
	nam = new Array();
	var auras = \'' . $txt . '\';
	var nk = ' . $kkid . ';
	arimg = new Array();
	arid = new Array();
	arnam = new Array();';

	for ($i = 1; $i <= $kblast; $i++) 
		echo 'img[' . $i . ']="' . $img[$i] . '";
			nam[' . $i . ']="' . $name[$i] . '";
			id[' . $i . ']="' . $idc[$i] . '";';

	for ($i = 1; $i <= $kkid; $i++) 
		echo 'kidimg[' . $i . ']="' . $kidimg[$i] . '";
			kidnam[' . $i . ']="' . $kidname[$i] . '";
			kidid[' . $i . ']="' . $kidid[$i] . '";';
	##########################################################################
	$bliz = '';
	$bliz_od = '';
	if ($pers["fstate"] == 1) {
		$bliz = 'Простой|Прицельный|Оглушающий';
		$bliz_od = '3|5|7';
		$spds = SQL::q("SELECT * FROM u_special_dmg WHERE uid=" . $pers["uid"] . " ORDER BY od ASC");
		foreach ($spds as $spd) {
			$bliz .= "|" . $spd["name"];
			$bliz_od .= "|" . $spd["od"];
		}
	}
	if ($pers["fstate"] == 3) {
		$bliz = 'Магия';
		$bliz_od = 'magic';
	}
	if ($pers["fstate"] == 4) {
		$bliz = 'Кинуть предмет';
		$bliz_od = 'kid';
	}
	$block = 'Простой|Усиленный|Крепчайший';
	$block_od = '1|2|5';
	##############################################################################

	echo "var bliz='" . $bliz . "';";
	echo "var bliz_od='" . $bliz_od . "';";
	echo "var block='" . $block . "';";
	echo "var block_od='" . $block_od . "';";
	echo "var speed=" . (1.5) . ";";
	echo "var before=" . (($pers["turn_before"]) ? 1 : 0) . ";";
	if (@$_GET["noone"] == 1 or substr_count($fight["nowhomvote"], "<" . $pers["user"] . ">"))
		echo "var noone=1;";
	else
		echo "var noone=0;";

	echo "show_boxes_and_form('" . $addon . "','" . $addon_od . "'," . $pers["fstate"] . ");";
	echo "</script><script>";
	$_CAN_TURN = 1;
	# Голосование за ничью:
	if (@$_GET["noone"] == 1 and !substr_count($fight["nowhomvote"], "<" . $pers["user"] . ">")) {
		$fight["nowhom"]++;
		if ($pers["invisible"] <= tme())
			$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . ">" . $pers["user"] . "</font>[" . $pers["level"] . "]";
		else
			$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . "><i>невидимка</i></font>[??]";
		$s = $nyou . " голосует за ничью! Ещё " . mtrunc(floor($fight["players"] / 2) - $fight["nowhom"] + 1) . " голосов до ничьи.";
		$fight["nowhomvote"] .= "<" . $pers["user"] . ">";
		$fight["all"] = '<font class=timef>' . date("H:i") . "</font>" . $s . ";" . $fight["all"];
		SQL::q("UPDATE `fights` SET `all`='" . addslashes($fight["all"]) . "' , `ltime`='" . time() . "' , nowhom=nowhom+1, nowhomvote='" . $fight["nowhomvote"] . "' WHERE `id`='" . $fight["id"] . "' ;");
		if (mtrunc(floor($fight["players"] / 2) - $fight["nowhom"] + 1) == 0) {
			$s = "Бой закончен голосованием. Ничья.";
			SQL::q("UPDATE users SET chp=0 WHERE cfight=" . $pers["cfight"]);
			add_flog($s, $pers["cfight"]);
			$fight["all"] = '<font class=timef>' . date("H:i") . "</font>" . $s . ";" . $fight["all"];
			SQL::q("UPDATE `fights` SET `all`='" . addslashes($fight["all"]) . "' , `ltime`='" . time() . "' WHERE `id`='" . $fight["id"] . "' ;");
			include('inc/inc/fights/finish.php');
			$fight["type"] = 'f';
			echo "location = 'main.php';";
		} else add_flog($s, $pers["cfight"]);
	}
}

######################################## - Не твой ХОД
if ($timeout == 0 and @$_GET["battle"] == "nowhom" and $CAN_TURN == 0 and $pers["chp"] > 0) {
	if ($pers["invisible"] <= tme())
		$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . ">" . $pers["user"] . "</font>[" . $pers["level"] . "]";
	else
		$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . "><i>невидимка</i></font>[??]";
	$s = "Бой закончен по таймауту. Ничья (" . $nyou . ").";
	add_flog($s, $pers["cfight"]);
	$fight["all"] = '<font class=timef>' . date("H:i") . "</font>" . $s . ";" . $fight["all"];
	SQL::q1("UPDATE `fights` SET `all`='" . addslashes($fight["all"]) . "' , `ltime`='" . time() . "' WHERE `id`='" . $fight["id"] . "' ;");
	include('inc/inc/fights/finish.php');
} elseif ($timeout == 0 and @$_GET["battle"] == "finish" and $CAN_TURN == 0 and $pers["chp"] > 0) {
	$not_turned = sql::q1("SELECT COUNT(*) as count FROM users WHERE cfight=" . $pers["cfight"] . " and fteam=" . $pers["fteam"] . " and chp>0 and can_turn=1")['count']; //Кол-во не сходивших в вашей команде
	if (!$not_turned) {
		if ($pers["invisible"] <= tme())
			$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . ">" . $pers["user"] . "</font>[" . $pers["level"] . "]";
		else
			$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . "><i>невидимка</i></font>[??]";
		$s = "Бой закончен по таймауту. Победа. (" . $nyou . ").";
		$fight['travm'] = 100;
		if ($pers["fteam"] == 1) 
			$LIFE2 = 0;
		else 
			$LIFE1 = 0;
		$pt = SQL::q("SELECT * FROM users WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0");
		SQL::q1("UPDATE users SET chp=0 WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0");
		foreach ($pt as $_persvs) {
			include('inc/inc/fights/travm.php');
			$s .= $str;
		}
		add_flog($s, $pers["cfight"]);
		$fight["all"] = '<font class=timef>' . date("H:i") . "</font>" . $s . ";" . $fight["all"];
		SQL::q1("UPDATE `fights` SET `all`='" . addslashes($fight["all"]) . "' , `ltime`='" . time() . "' WHERE `id`='" . $fight["id"] . "' ;");
		include('inc/inc/fights/finish.php');
	}
} elseif ($timeout > 0 and !$_FINISHED and $CAN_TURN == 0) {
	if ($pers["chp"] > 0)
		echo "show_message_in_f('<div align=center class=title>Ожидаем хода соперника.</div>');";
	else
		echo "show_message_in_f('<div align=center class=title>Ожидаем конца боя.</div>');";
} elseif ($timeout == 0 and !$_FINISHED and $CAN_TURN == 0) {
	$not_turned = sql::q1("SELECT COUNT(*) as count FROM users WHERE cfight=" . $pers["cfight"] . " and fteam=" . $pers["fteam"] . " and chp>0 and can_turn=1")['count']; //Кол-во не сходивших в вашей команде
	if (!$not_turned and $pers["chp"] > 0)
		echo "show_message_in_f('<center class=return_win><b>Время хода противника истекло.</b><br> Чтобы прекратить бой нажмите на любую кнопку.Чтобы дать сопернику ещё время, просто ничего не нажимайте.<br><input type=button class=login value=Ничья onclick=\"location=\'main.php?battle=nowhom\'\"> | <input type=button class=login value=\"Завершить бой по таймауту\" onclick=\"location=\'main.php?battle=finish\'\"></center>');";
	else
		echo "show_message_in_f('<div align=center class=title>Ожидаем хода соперника.</div>');";
}
echo "</script>";

echo "<script>";
set_vars("can_turn=" . intval($_CAN_TURN) . "", $pers["uid"]);

$log = SQL::q("SELECT time,log FROM fight_log WHERE cfight=" . $pers["cfight"] . " ORDER BY turn DESC LIMIT 0,3");
$_LOG = '';
$i = 0;
foreach ($log as $l) {
	if ($i > 0)
		$_LOG .= "<hr>";
	$i++;
	if ($i % 2)
		$_LOG .= "<div style=\"background-color: #FFFFFF;\">";
	$_LOG .= str_replace("'", '"', str_replace("%", "<br><br><font class=timef>" . $l["time"] . "</font> ", "<font class=timef>" . $l["time"] . "</font> " . $l["log"]));
	if ($i % 2)
		$_LOG .= "</div>";
}
echo "show_message_in_f('<div class=but>" . $_LOG . "</div>');";
########################
if (!$_FINISHED)
	echo "show_finish(" . $pers["fexp"] . "," . $pers["exp_in_f"] . "," . $pers["f_turn"] . ");";
## - JS\OFF - ##
echo "</script>";
###########
