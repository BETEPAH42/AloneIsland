<?php
include_once 'classes/sql.php';

$sql_queries_counter = 0;
$sql_queries_timer = 0;
$sql_longest_query_t = 0;
$sql_longest_query = '';
$last_say_to_chat = 0;
$sql_all[0] = '';
$GLOBAL_TIME = time();
$battle_log = '';

foreach ($_POST as $key => $value) $_POST[$key] = filter($value);
foreach ($_GET  as $key => $value) $_GET[$key]  = filter($value);
foreach ($_COOKIE  as $key => $value) $_COOKIE[$key]  = filter($value);
function tme()
{
	global $GLOBAL_TIME;
	return $GLOBAL_TIME;
}
function filter($v)
{
	return str_replace("'", "", str_replace("\\", "", htmlspecialchars($v)));
}

function bit_icon($type, $size = 0)
{
	if ($size == 0) $size = 60;
	if ($type == 's') return "<img src=images/arena/bits/s/a" . rand(1, 13) . ".gif height=" . $size . " title=Сокрушительный>";
	elseif ($type == 'd') return "<img src=images/arena/bits/d/a" . rand(1, 7) . ".gif height=" . $size . " title=Уворот>";
	else return "<img src=images/arena/bits/t/a" . rand(1, 11) . ".gif height=" . $size . " title=Точный>";
}
// Боевые
// Функция удара от человека.
function human_udar($point, $_pers, $_persvs, $req, $en, $delta)
{
	if ($_pers["udmin"] < 1) $_pers["udmin"] = 1;
	if ($_pers["udmax"] < 1) $_pers["udmax"] = 1;
	if ($delta < 1) $delta = 1;
	global $colors, $fight, $kl, $die;
	if ($_pers["invisible"] > tme()) {
		$_pers["user"] = '<i>невидимка</i>';
		$invyou = 1;
		$_pers["pol"] = 'female';
	} else $invyou = 0;
	if ($_persvs["invisible"] > tme()) {
		$_persvs["user"] = '<i>невидимка</i>';
		$invvs = 1;
		$_persvs["pol"] = 'female';
	} else $invvs = 0;

	if (!$invvs)
		$nvs = "<font class=bnick color=" . $colors[$_persvs["fteam"]] . ">" . $_persvs["user"] . "</font>[" . $_persvs["level"] . "]";
	else
		$nvs = "<font class=bnick color=" . $colors[$_persvs["fteam"]] . "><i>невидимка</i></font>[??]";

	if ($_pers["pol"] == 'female') $male = 'а';
	else $male = '';
	if ($male == 'а')
		$pitalsa = 'пыталась';
	else
		$pitalsa = 'пытался';

	if ($_persvs["pol"] == 'female') {
		$pogib = 'погибла';
		$malevs = 'а';
		$yvvs = 'увернулась';
	} else {
		$pogib = 'погиб';
		$malevs = '';
		$yvvs = 'увернулся';
	}

	if (!$invyou)
		$nyou = "<font class=bnick color=" . $colors[$_pers["fteam"]] . ">" . $_pers["user"] . "</font>[" . $_pers["level"] . "]";
	else
		$nyou = "<font class=bnick color=" . $colors[$_pers["fteam"]] . "><i>невидимка</i></font>[??]";

	switch ($point) {
		case ("ug"): {
				$bpoint = "bg";
				$ypoint = "удар в голову";
				break;
			}
		case ("ut"): {
				$bpoint = "bt";
				$ypoint = "удар в грудь";
				break;
			}
		case ("uj"): {
				$bpoint = "bj";
				$ypoint = "удар по животу";
				break;
			}
		case ("un"): {
				$bpoint = "bn";
				$ypoint = "удар по ногам";
				break;
			}
	}
	//echo $_persvs[$bpoint]."-",$_persvs["user"]." ".$bpoint."<br>";

	$_W = Weared_Weapons($_pers["uid"]);
	if ($req[$point] != 'magic')
		$req[$point] -= $_W["OD"];

	$_pers["udmin"] +=	$_pers["udmin"] * $_pers["sb2"] / 100 +
		$_W["noji"]["udmin"] * $_pers["sb3"] / 200 +
		$_W["mech"]["udmin"] * $_pers["sb5"] / 200 +
		$_W["topo"]["udmin"] * $_pers["sb6"] / 200 +
		$_W["drob"]["udmin"] * $_pers["sb7"] / 200;

	$_pers["udmax"] +=	$_pers["udmax"] * $_pers["sb2"] / 100 +
		$_W["noji"]["udmax"] * $_pers["sb3"] / 200 +
		$_W["mech"]["udmax"] * $_pers["sb5"] / 200 +
		$_W["topo"]["udmax"] * $_pers["sb6"] / 200 +
		$_W["drob"]["udmax"] * $_pers["sb7"] / 200;

	if ($_persvs["uid"] and $_persvs["sb4"])
		$_persvs["kb"] += sql::q1("SELECT SUM(kb) as sum FROM wp WHERE uidp=" . intval($_persvs["uid"]) . " and weared=1 and stype='shit'")['sum'] * $_persvs["sb4"] / 33;

	$ud_name = '';
	if ($req[$point] == 3) $ud_name = 'простой ';
	elseif ($req[$point] == 5) $ud_name = 'прицельный ';
	elseif ($req[$point] == 7) $ud_name = 'оглушающий ';
	else {
		$spd = sql::q1("SELECT * FROM u_special_dmg WHERE uid=" . $_pers["uid"] . " and od=" . intval($req[$point]) . "");
		$ud_name = "<b>" . $spd["name"] . "</b> ";
	}

	if ($ud_name == '') return false;

	$ud_name .= $ypoint;
	$fall = '';

	if (!$_persvs["uid"]) $_persvs[$bpoint] = mtrunc(rand(-2, 1));

	if (!empty($req[$point]) and ($req[$point] <> 'magic' or ($req[$point] == 'magic' and !empty($req[$point . "p"]))) and ($req[$point] <> 'kid' or ($req[$point] == 'kid' and !empty($req[$point . "p"]))) and (intval($req[$point]) > 0 or $req[$point] == 'kid' or $req[$point] == 'magic')) {
		if ($_persvs["chp"] > 0) {
			$zakname = '';
			$kl = 1;
			$block = '';
			$blocked = 0;
			if ($_pers["fstate"] == 2) {
				$f_wp = sql::q1("SELECT * FROM wp WHERE uidp=" . $_pers["uid"] . " and weared=1 and stype='kid'");
				$_pers["udmin"] = $f_wp["udmin"];
				$_pers["udmax"] = $f_wp["udmax"];
				$an = $f_wp["arrow_name"];
				$ap = $f_wp["arrow_price"] / 10;
				$ud_name = "[<font class=time>" . $an . " :: " . $ap . " LN</font>]" . $ud_name;
				SQL::q("UPDATE wp SET arrows=arrows-1 WHERE id='" . $f_wp["id"] . "'");
				$promax = rand(1, 10) - $_pers["mf3"] / 100;
			}
			if (@$spd) {
				if ($spd["type"] == 1) {
					$_pers["udmin"] *= 1 + $spd["value"] / 100;
					$_pers["udmax"] *= 1 + $spd["value"] / 100;
				}
			}

			$ydar = ydar($_pers, $_persvs) / $delta;
			if ($req[$point] == 5) $ydar *= 1.1;
			if ($req[$point] == 7) $ydar *= 1.2;

			$ylov = ylov($_pers, $_persvs);
			$sokr = sokr($_pers, $_persvs);
			$yar  = yar($_pers, $_persvs);

			if ($_persvs["is_art"] < 1) $_persvs["is_art"] = 1;
			if ($_pers["is_art"] < 1) $_pers["is_art"] = 1;
			$yar  *= $_pers["is_art"];
			$ylov *= $_persvs["is_art"];
			$sokr *= $_pers["is_art"];
			$ydar *= $_pers["is_art"];
			$ydar = floor($ydar);

			if ($ylov > 70) $ylov = 70;
			if ($sokr > 70) $sokr = 70;

			if (@$spd) {
				if ($spd["type"] == 1) {
					$_pers["udmin"] = $_pers["udmin"] / (1 + $spd["value"] / 100);
					$_pers["udmax"] = $_pers["udmax"] / (1 + $spd["value"] / 100);
				}
				if ($spd["type"] == 2) $sokr = $sokr + $sokr * $spd["value"] / 70;
				if ($spd["type"] == 3) $ylov = $ylov - $ylov * $spd["value"] / 70;
			}

			if ($yar > rand(0, 100)) {
				$ydar *= 1.7;
				$ydar = round($ydar);
				if ($block == '') $block = ',';
				$block .= '<font color=green>нанося яростный удар</font>,';
			}

			$ksokr = 2;


			$CRITISISED = 0;
			if (rand(0, 100) < $sokr) {
				$ydar = round($ydar * $ksokr);
				$CRITISISED = 1;
			}

			if ($_persvs[$bpoint] == 1) {
				if ($ydar / (mtrunc($_persvs["kb"]) / 3 + 1) > 2) {
					$ydar *= 0.3;
					$block = ", пробивая простой блок ,";
				} else {
					$ydar = 0;
					$blocked = 1;
				}
			}
			if ($_persvs[$bpoint] == 2) {
				if ($ydar / (mtrunc($_persvs["kb"]) / 3 + 1) > 3) {
					$ydar *= 0.2;
					$block = ", пробивая усиленный блок ,";
				} else {
					$ydar = 0;
					$blocked = 1;
				}
			}

			if ($_persvs[$bpoint] == 5) {
				if ($ydar / (mtrunc($_persvs["kb"]) / 3 + 1) > 5) {
					$ydar *= 0.1;
					$block = ", пробивая крепчайший блок ,";
				} else {
					$ydar = 0;
					$blocked = 1;
				}
			}

			unset($zid);

			if ($req[$point] == 'magic')
				$zid = $req[$point . "p"];

			$z = 1;
			if ($zid)
				include('inc/inc/magic.php');

			$ydar = floor($ydar);

			if ($blocked and $z == 1) {
				$z = 0;
				$s = $nvs . " <b>заблокировал" . $malevs . "</b> <font class=timef>«" . $ud_name . "»</font>";
			}
			if ($z == 1 and rand(0, 100) < $promax) {
				$z = 0;
				$s = $nyou . " промах";
				$ydar = 0;
			}
			if ($z == 1 and rand(0, 100) < $ylov) {
				$z = 0;
				$s = bit_icon("d", 16) . $nyou . " " . $pitalsa . " поразить соперника, но " . $nvs . " <b>" . $yvvs . "</b> от <font class=timef>«" . $ud_name . "»</font>";
				$ydar = 0;
			}
			if ($z == 1 and $CRITISISED) {
				$z = 0;
				$s = bit_icon("s", 16) . $nyou . " " . $block . " поразил" . $male . " " . $nvs . " на	<font class=bnick color=#CC0000><b>-" . $ydar . "</b></font> <font class=timef>«cокрушительный " . $ud_name . "»</font>";
			}
			if ($z == 1) {
				$z = 0;
				$s = bit_icon("t", 16) . $nyou . " " . $block . " поразил" . $male . " " . $nvs . " на <b class=user>-" . $ydar . "</b> <font class=timef>«" . $ud_name . "»</font>";
			}

			if (@$spd and $spd["type"] == 4 and !$blocked) {
				$_persvs["cma"] -= $spd["value"];
				$_persvs["cma"] = mtrunc($_persvs["cma"]);
				$s .= "(<font class=ma>-" . $spd["value"] . " МАНЫ</font>)";
			}
			if ($z == 0) {
				$_persvs["chp"] -= $ydar;
				$_pers["fexp"] += $ydar;
				if (!$invvs)
					$s .= "<font class=hp_in_f>[" . mtrunc($_persvs["chp"]) . "/" . $_persvs["hp"] . "]</font>";
			}

			if ($MAGIC_LOG) $s = $MAGIC_LOG;

			if ($_persvs["chp"] <= 0 and $z <> 2) {
				$_pers["fexp"] += $_persvs["chp"];
				$ydar += $_persvs["chp"];
				$_persvs["chp"] = 0;
				if (($_persvs["uid"] or $_persvs["bid"] < 0 or ($_persvs["level"] > ($_pers["level"] + 1) and $_persvs["rank_i"] > ($_pers["rank_i"] - 20 * $_pers["is_art"]) and rand(0, 100) < 10)) and $_persvs["level"] > ($_pers["level"] - 2) and $fight["travm"] >= 10) {
					$die = $nvs . " <b>" . $pogib . "</b> , " . $nyou . " опыт <font class=green>+" . ($_pers["level"] * 10) . "</font>.%" . $die;
					$_pers["kills"]++;
				} else
					$die = $nvs . " <b>" . $pogib . "</b>.%" . $die;
				$str = '';
				if (!$_persvs["uid"]) include('inc/inc/bots/drop.php');
				else include('inc/inc/fights/travm.php');
				$die .= $str;
			}
			if ($z <> 2) {
				if (!$_persvs["id_skin"])
					$_pers["exp_in_f"] += experience(
						$ydar,
						$_pers["level"],
						$_persvs["level"],
						$_persvs["uid"],
						$_persvs["rank_i"]
					);
				else
					$_pers["exp_in_f"] += experience(
						$ydar * 0.3,
						$_pers["level"],
						$_persvs["level"],
						$_persvs["uid"],
						$_persvs["rank_i"]
					);
				$_pers["damage_give"] = $ydar;
			}
			if ($_pers["chp"] <= 0) $_pers["chp"] = 0;
			if ((strpos($_pers["aura"], 'vampire') > 0 and round($ydar / 10) > 0 and $no_mana == false and $_pers["chp"] > 0 and $z <> 2) or $_pers['sign'] == 'c1') {
				if (DAY_TIME == 0) {
					$_pers["chp"] += round($ydar / 8);
					$s .= ".Вампиризм <font class=hp>+" . round($ydar / 8) . "HP</font>";
				} else {
					$_pers["chp"] += round($ydar / 16);
					$s .= ".Вампиризм <font class=hp>+" . round($ydar / 16) . "HP</font>";
				}
			}

			$fall = $fall . $s;
			if ($z <> 2) {
				if ($_persvs["uid"]) {
					if (!$en) SQL::q("UPDATE `users` SET `chp`='" . $_persvs["chp"] . "' ,`cma`='" . $_persvs["cma"] . "' ,`refr`=1	WHERE `uid`='" . $_persvs["uid"] . "'");
					else
						SQL::q("UPDATE `users` SET `chp`='" . $_persvs["chp"] . "' ,`cma`='" . $_persvs["cma"] . "' WHERE `uid`='" . $_persvs["uid"] . "'");
				} else
					SQL::q("UPDATE `bots_battle` SET `chp`='" . $_persvs["chp"] . "' ,`cma`='" . $_persvs["cma"] . "'	WHERE `id`= " . $_persvs["id"] . "");
			}
			SQL::q("UPDATE `users` SET
		`fexp`='" . $_pers["fexp"] . "',
		`chp`='" . $_pers["chp"] . "' ,
		`exp_in_f` = '" . $_pers["exp_in_f"] . "',
		`damage_give` = " . $_pers["damage_give"] . ",
		kills = " . $_pers["kills"] . "
		WHERE `uid`='" . $_pers["uid"] . "'");
		} else
			$fall = $nyou . " сделал" . $malevs . " контрольный удар по трупу";
	}

	if ($fall) $fall = $fall . ". &nbsp;";

	global $pers, $persvs;
	$pers = catch_user($pers["uid"]);
	if ($persvs["chp"] > 0) {
		if ($persvs["uid"])
			$persvs = catch_user($persvs["uid"]);
		else
			$persvs = sql::q1("SELECT * FROM bots_battle WHERE id= " . $persvs["id"] . "");
	} else {
		$persvs = sql::q1("SELECT * FROM users WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0");
		if (!$persvs["uid"])
			$persvs = sql::q1("SELECT * FROM bots_battle WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0");
	}

	return $fall;
}

function newbot_udar($point, $_botU)
{
	global $persvs, $pers, $colors, $fight, $kl, $die, $PVS_NICK, $USER_NICK, $pitalsa, $yvvs, $male, $malevs, $pogib, $promax, $_persvs;

	if ($_botU[$point] == 0 or !$pers or !$persvs) return;

	//var_dump($persvs);

	if ($persvs["invisible"] > tme()) {
		$persvs["user"] = '<i>невидимка</i>';
		$invvs = 1;
		$persvs["pol"] = 'female';
	} else
		$invvs = 0;

	$_SHIT_PLUS = 0;
	if ($persvs["uid"] and $persvs["sb4"])
		$_SHIT_PLUS += sql::q1("SELECT SUM(kb) as sum FROM wp WHERE uidp=" . intval($_persvs["uid"]) . " and weared=1 and stype='shit'")['sum'] * $persvs["sb4"] / 33;

	$persvs["kb"] += $_SHIT_PLUS;

	if (!$invvs)
		$nvs = "<font class=bnick color=" . $colors[$persvs["fteam"]] . ">" . $persvs["user"] . "</font>[" . $persvs["level"] . "]";
	else
		$nvs = "<font class=bnick color=" . $colors[$persvs["fteam"]] . "><i>невидимка</i></font>[??]";
	$nyou = "<font class=bnick color=" . $colors[$pers["fteam"]] . ">" . $pers["user"] . "</font>[" . $pers["level"] . "]";

	if ($pers["pol"] == 'female') $male = 'а';
	else $male = '';
	if ($male == 'а')
		$pitalsa = 'пыталась';
	else
		$pitalsa = 'пытался';

	if ($persvs["pol"] == 'female') {
		$pogib = 'погибла';
		$malevs = 'а';
		$yvvs = 'увернулась';
	} else {
		$pogib = 'погиб';
		$malevs = '';
		$yvvs = 'увернулся';
	}
	switch ($point) {
		case ("ug"): {
				$bpoint = "bg";
				$ypoint = "удар в голову";
				break;
			}
		case ("ut"): {
				$bpoint = "bt";
				$ypoint = "удар в грудь";
				break;
			}
		case ("uj"): {
				$bpoint = "bj";
				$ypoint = "удар по животу";
				break;
			}
		case ("un"): {
				$bpoint = "bn";
				$ypoint = "удар по ногам";
				break;
			}
	}
	if ($_botU[$point] == 1) $ud_name = 'простой ';
	if ($_botU[$point] == 2) $ud_name = 'прицельный ';
	if ($_botU[$point] == 5) $ud_name = 'оглушающий ';
	$ud_name .= $ypoint;
	$ud_name = $ud_name;
	$fall = '';
	//var_dump($_botU);
	if (isset($_botU[$point]) and $persvs["chp"] > 0) {
		$kl = 1;
		$block = '';
		$blocked = 0;
		$ydar = ydar($pers, $persvs);
		if ($_botU[$point] == 2) $ydar *= 1.1;
		if ($_botU[$point] == 5) $ydar *= 1.2;
		if ($persvs[$bpoint] == 1) {
			if ($ydar / (mtrunc($persvs["kb"]) + 1) > 2) {
				$ydar *= 0.3;
				$block = ", пробивая простой блок ,";
			} else {
				$ydar = 0;
				$blocked = 1;
			}
		}
		if ($persvs[$bpoint] == 2) {
			if ($ydar / (mtrunc($persvs["kb"]) + 1) > 3) {
				$ydar *= 0.2;
				$block = ", пробивая усиленный блок ,";
			} else {
				$ydar = 0;
				$blocked = 1;
			}
		}

		if ($persvs[$bpoint] == 5) {
			if ($ydar / (mtrunc($persvs["kb"]) + 1) > 5) {
				$ydar *= 0.1;
				$block = ", пробивая крепчайший блок ,";
			} else {
				$ydar = 0;
				$blocked = 1;
			}
		}
		$ydar = floor($ydar);
		$ylov = ylov($pers, $persvs);
		$sokr = sokr($pers, $persvs);
		$yar  = yar($pers, $persvs);

		if ($persvs["is_art"] < 1) $persvs["is_art"] = 1;
		if ($pers["is_art"] < 1) $pers["is_art"] = 1;

		$ylov *= $persvs["is_art"];

		if ($ylov > 70) $ylov = 70;
		if ($sokr > 70) $sokr = 70;

		if ($yar > rand(0, 100)) {
			$ydar *= 1.4;
			if ($block == '') $block = ',';
			$block .= '<font color=green>нанося яростный удар</font>,';
		}

		$ydar = floor($ydar);

		$ksokr = 2;

		$z = 1;
		if ($blocked and $z == 1) {
			$z = 0;
			$s = $nvs . " <b>заблокировал" . $malevs . "</b> <font class=timef>«" . $ud_name . "»</font>";
		}
		if ($z == 1 and rand(0, 100) < $promax) {
			$z = 0;
			$s = $nyou . " промах";
		}
		if ($z == 1 and rand(0, 100) < $ylov) {
			$z = 0;
			$s = bit_icon("d", 16) . $nyou . " " . $pitalsa . " поразить соперника, но " . $nvs . " <b>" . $yvvs . "</b> от <font class=timef>«" . $ud_name . "»</font>";
		}
		if ($z == 1 and rand(0, 100) < $sokr) {
			$z = 0;
			$ydar = round($ydar * $ksokr);
			$persvs["chp"] -= $ydar;
			$pers["fexp"] += $ydar;
			if (!$invvs) $hpvs = "<font class=hp_in_f>[" . mtrunc($persvs["chp"]) . "/" . $persvs["hp"] . "]</font>";
			else $hpvs = '';
			$s = bit_icon("s", 16) . $nyou . " " . $block . " поразил" . $male . " " . $nvs . " на	<font class=bnick color=#CC0000><b>-" . $ydar . "</b></font> <font class=timef>«cокрушительный " . $ud_name . "»</font>" . $hpvs;
		}
		if ($z == 1) {
			$z = 0;
			$persvs["chp"] -= $ydar;
			if (!$invvs) $hpvs = "<font class=hp_in_f>[" . mtrunc($persvs["chp"]) . "/" . $persvs["hp"] . "]</font>";
			else $hpvs = '';
			$s = bit_icon("t", 16) . $nyou . " " . $block . " поразил" . $male . " " . $nvs . " на <b class=user>-" . $ydar . "</b> <font class=timef>«" . $ud_name . "»</font>" . $hpvs;
		}
		$pers["exp_in_f"] += experience(
			$ydar,
			$pers["level"],
			$persvs["level"],
			$persvs["uid"],
			$persvs["rank_i"]
		);
		if ($persvs["chp"] <= 0 and $z <> 2) {
			$persvs["chp"] = 0;
			$die = $nvs . " <b>" . $pogib . "</b>.%" . $die;
			if ($persvs["uid"])
				include('inc/inc/fights/travm.php');
			$die .= $str;
		}
		$fall = $fall . $s;
		if ($persvs["uid"])
			sql::q("UPDATE `users` SET `chp`='" . $persvs["chp"] . "'	WHERE `uid`='" . $persvs["uid"] . "'");
		else
			sql::q("UPDATE `bots_battle` SET `chp`='" . $persvs["chp"] . "'	WHERE `id`='" . $persvs["id"] . "'");
		sql::q("UPDATE `bots_battle` SET `exp_in_f`='" . $pers["exp_in_f"] . "' WHERE `id`='" . $pers["id"] . "'");
	} elseif ($persvs["chp"] <= 0)
		$fall = $nyou . " сделал контрольный удар по трупу";
	if ($fall) $fall = $fall . ". &nbsp;";
	$persvs["kb"] -= $_SHIT_PLUS;
	return $fall;
}

function end_battle($pers)
{
	global $GOOD_DAY, $options;

	// В $pers["f_turn"] хранится переменная победа. =1 - победили. =0 - проиграли.
	$fight = sql::q1("SELECT * FROM `fights` WHERE `id`='" . $pers["cfight"] . "'");
	if ($fight["turn"] == "finish" and $fight["type"] == 'f') {
		if (($pers["lb_attack"] - 40) < tme())
			$pers["lb_attack"] = tme() - 40;
		$curstate = 0;
		$win = ($pers["f_turn"] == 1) ? "Победа!" : "Поражение.";
		######Праздник
		if ($fight["special"] == 1) {
			include("holyday/new_year.php");
		}
		######Турниры
		if ($pers["tour"] == 1) {
			$t1 = sql::q1("SELECT * FROM quest WHERE id = 2");
			if ($pers["f_turn"] != 1) {
				set_vars("tour=0", $pers["uid"]);
				say_to_chat('s', "Вы проиграли турнир...", 1, $pers["user"], '*', 0);
			} elseif ($t1["type"] == 2) {
				say_to_chat('s', "Вы прошли во вторую стадию турнира!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='" . $pers["uid"] . "' ");
				sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme());
				$pers["chp"] = $pers["hp"];
				$pers["cma"] = $pers["ma"];
			} elseif ($t1["type"] == 3) {
				set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100", $pers["uid"]);
				say_to_chat('s', "Вы выиграли турнир!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE quest SET finished=1,time=" . tme() . " WHERE id = 2");
			}
		}
		if ($pers["tour"] == 2) {
			$t1 = sql::q1("SELECT * FROM quest WHERE id = 3");
			if ($pers["f_turn"] == 0) {
				set_vars("tour=0", $pers["uid"]);
				say_to_chat('s', "Вы проиграли турнир...", 1, $pers["user"], '*', 0);
			} elseif ($t1["type"] == 2) {
				say_to_chat('s', "Вы прошли во вторую стадию турнира!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='" . $pers["uid"] . "' ");
				sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme());
				$pers["chp"] = $pers["hp"];
				$pers["cma"] = $pers["ma"];
			} elseif ($t1["type"] == 3) {
				set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100", $pers["uid"]);
				say_to_chat('s', "Вы выиграли турнир!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE quest SET finished=1,time=" . tme() . " WHERE id = 3");
			}
		}
		if ($pers["tour"] == 3) {
			$t1 = sql::q1("SELECT * FROM quest WHERE id = 4");
			if ($pers["f_turn"] == 0) {
				set_vars("tour=0", $pers["uid"]);
				say_to_chat('s', "Вы проиграли турнир...", 1, $pers["user"], '*', 0);
			} elseif ($t1["type"] == 2) {
				say_to_chat('s', "Вы прошли во вторую стадию турнира!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='" . $pers["uid"] . "' ");
				sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme());
				$pers["chp"] = $pers["hp"];
				$pers["cma"] = $pers["ma"];
			} elseif ($t1["type"] == 3) {
				set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100", $pers["uid"]);
				say_to_chat('s', "Вы выиграли турнир!", 1, $pers["user"], '*', 0);
				sql::q("UPDATE quest SET finished=1,time=" . tme() . " WHERE id = 4");
			}
		}
		####### Турниры кончились
		say_to_chat('s', "<b>Поединок завершен. " . $win . "</b> Нанесено урона: <b>" . $pers["fexp"] . "</b> , получено <font class=hp>боевого опыта: <b>" . $pers["exp_chat"] . "</b></font>. Убийства людей: <b>" . $pers["kills"] . "</b> <a href=\"fight.php?id=" . $pers["cfight"] . "\" target=_blank class=timef>Лог боя</a>.", 1, $pers["user"], '*', 0);
		if ($pers["kills"] > 0) {
			$pers["coins"] += $pers["kills"];
			say_to_chat('s', "<i><b>+" . $pers["kills"] . " пергамент.</b></i>", 1, $pers["user"], '*', 0);
		}
		if ($pers["gain_time"] > (tme() - 1200)) {
			$curstate = 2;
			if ($pers["f_turn"] != 1) set_vars("gain_time=0", $pers["uid"]);
		}
		if ($pers["f_turn"] != 1)
			set_vars("tour=0", $pers["uid"]);
		sql::q("UPDATE `users` SET `curstate`=" . $curstate . " ,`cfight`=0 , `chp`=`chp`+2 , `od_b`=0 ,`fexp`=0 ,`exp_in_f`=0,f_turn=0,exp_chat=0,apps_id=0,kills=0,coins=coins+" . $pers["kills"] . ",lb_attack=" . $pers["lb_attack"] . " WHERE `uid`='" . $pers["uid"] . "' ");
		$pers["cfight"] = 0;
		$pers["curstate"] = $curstate;
		$pers["chp"] += 2;
		$pers["fexp"] = 0;
		$pers["exp_in_f"] = 0;
		$pers["f_turn"] = 0;
		$pers["od_b"] = 0;
		$pers["kills"] = 0;

		if ($options[7] <> "no")
			echo "<script>top.flog_unset();</script>";
		echo "<script>top.flog_clear();</script>";

		sql::q("UPDATE `u_blasts` SET cur_turn_colldown=0 WHERE uidp=" . $pers["uid"]);
		sql::q("UPDATE `u_auras` SET cur_turn_colldown=0 WHERE uidp=" . $pers["uid"]);
		sql::q("UPDATE `p_auras` SET turn_esttime=0 WHERE uid=" . $pers["uid"]);

		$tmp = sql::q1("SELECT esttime FROM p_auras
	WHERE uid=" . $pers["uid"] . " and special=16 and esttime>" . tme())['esttime'];

		$_REGEN = mtrunc($tmp - tme());
		if ($_REGEN || ($GOOD_DAY & GD_HUMANHEAL)) {
			//SQL::q("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='".$pers["uid"]."' ");
			SQL::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme());
			/*$pers["chp"]=$pers["hp"];
		$pers["cma"]=$pers["ma"];*/
		}
	}
	return $pers;
}

function name_of_skill($skill)
{
	if ($skill == 'ma') return "Запас маны";
	elseif ($skill == 'hp') return "Запас жизни";
	elseif ($skill == 'cma') return "Мана";
	elseif ($skill == 'chp') return "Жизнь";
	elseif ($skill == 'kb') return "Класс брони";
	elseif ($skill == 'mf1') return "Сокрушение";
	elseif ($skill == 'mf2') return "Уловка";
	elseif ($skill == 'mf3') return "Точность";
	elseif ($skill == 'mf4') return "Стойкость";
	elseif ($skill == 'mf5') return "Ярость";
	elseif ($skill == 'udmin') return "Минимальный удар";
	elseif ($skill == 'udmax') return "Максимальный удар";
	elseif ($skill == 'rank_i') return "Ранк";

	if ($skill == 'stats') {
		$r = array("Сила", "Реакция", "Удача", "Здоровье", "Интеллект", "Сила Воли");
		return $r;
	}
	if ($skill == 'skillsb') {
		$r = array("Очки действия", "Колкий удар", "Владение ножами", "Владение щитами", "Владение мечами", "Владение топорами", "Владение булавами", "Чтение книг", "Усиление магии", "Сопротивление Магии", "Сопротивление Физическим повреждениям", "Сопротивление Отравам", "Сопротивление Электричеству", "Сопротивление Огню", "Сопротивление Холоду");
		return $r;
	}
	if ($skill == 'skillsm') {
		$r = array("Атлетизм", "Эрудиция", "Тяжеловес", "Скорость", "Обаяние", "Регенерация жизни", "Регенерация маны");
		return $r;
	}
	if ($skill == 'skillsp') {
		$r = array("Целитель", "Темное искусство", "Удар в спину", "Воровство", "Кузнец", "Рыбак", "Шахтер", "Ориентирование на местности", "Экономист", "Охотник", "Алхимик", "Добыча камней", "Дровосек", "Выделка кожи");
		return $r;
	}
	$r = array("Сила", "Реакция", "Удача", "Здоровье", "Интеллект", "Сила Воли");
	$num = 0;
	if ($skill == 's1') $num = 1;
	if ($skill == 's2') $num = 2;
	if ($skill == 's3') $num = 3;
	if ($skill == 's4') $num = 4;
	if ($skill == 's5') $num = 5;
	if ($skill == 's6') $num = 6;
	if ($num <> 0) return $r[$num - 1];
	$r = array("Атлетизм", "Эрудиция", "Тяжеловес", "Скорость", "Обаяние", "Регенерация жизни", "Регенерация маны");

	if (substr_count($skill, "sm"))
		return $r[str_replace("sm", "", $skill) - 1];

	$r = array("Очки действия", "Колкий удар", "Владение ножами", "Владение щитами", "Владение мечами", "Владение топорами", "Владение булавами", "Чтение книг", "Усиление магии", "Сопротивление Магии", "Сопротивление Физическим повреждениям", "Сопротивление Отравам", "Сопротивление Электричеству", "Сопротивление Огню", "Сопротивление Холоду");

	if (substr_count($skill, "sb"))
		return $r[str_replace("sb", "", $skill) - 1];

	$r = array("Целитель", "Темное искусство", "Удар в спину", "Воровство", "Кузнец", "Рыбак", "Шахтер", "Ориентирование на местности", "Экономист", "Охотник", "Алхимик", "Добыча камней", "Дровосек", "Выделка кожи");

	if (substr_count($skill, "sp"))
		return $r[str_replace("sp", "", $skill) - 1];

	$r = array("Вертлявость", "Бронебойность", "Толстая кожа", "Расчётливость", "Быстрота", "Любовник", "Пиротехник", "Электрик");

	if (substr_count($skill, "a") and $skill <> 'ma' and $skill <> 'udmax' and $skill <> 'сma')
		return $r[str_replace("a", "", $skill) - 1];

	$r = array("Религия", "Некромантия", "Стихийная магия", "Магия порядка", "Вызовы существ");
	$num = 0;
	if ($skill == 'm1') $num = 1;
	if ($skill == 'm2') $num = 2;
	if ($skill == 'm3') $num = 3;
	if ($skill == 'm4') $num = 4;
	if ($skill == 'm5') $num = 5;
	if ($num <> 0) return $r[$num - 1];

	if ($skill == 'level') return "Уровень";
	if ($skill == 'colldown') return "Перезарядка(сек)";
	if ($skill == 'turn_colldown') return "Перезарядка(ходы)";
	if ($skill == 'esttime') return "Время действия";
	if ($skill == 'manacost') return "Стоимость маны";
	if ($skill == 'targets') return "Кол-во целей";
	return $skill;
}

function _StateByIndex($a)
{
	if ($a == 'g') return 'Глава клана';
	if ($a == 'z') return 'Заместитель главы';
	if ($a == 'c') return 'Казначей';
	if ($a == 'k') return 'Отдел кадров';
	if ($a == 'b') return 'Боевой отдел';
	if ($a == 'p') return 'Производственный отдел';
	return 'Член клана';
}

function aq($arr)
{
	$pconnect = SQL::q1("SELECT * FROM `users` WHERE `uid`='" . $arr . "'");
	global $resault_aq;
	$res = "";
	foreach ($pconnect as $key => $value)
		if ($pconnect[$key] <> $arr[$key] and $key <> 'user' and $key <> 'smuser' and $key <> 'uid' and $key <> 'refr' and $key <> 'cfight' and $key <> 'lastom' and $key <> 'pol' and !is_integer($key) and $key <> '')
			$res .= "`" . $key . "`='" . $arr[$key] . "',";
	$res = substr($res, 0, strlen($res) - 1);
	$resault_aq = $res;
	return $res;
}

function tp($l)
{
	$l = mtrunc($l);
	$n = '';
	if ((floor($l / 86400)) <> 0) {
		$n = $n . (floor($l / 86400)) . "д&nbsp;";
		$l = $l % 86400;
	}
	if ((floor($l / 3600)) <> 0) $n = $n . (floor($l / 3600)) . "ч&nbsp;";
	if ((floor(($l % 3600) / 60)) <> 0) $n = $n . (floor(($l % 3600) / 60)) . "м&nbsp;";
	$n = $n . (($l % 3600) % 60) . "с";
	return $n;
}

function str_once_delete($sub, $str)
{
	$p = strpos(" " . $str, $sub);
	if ($p > 0) {
		$p--;
		$sl = strlen($sub);
		$sl_str = strlen($str);
		$part1 = substr($str, 0, $sl + $p);
		$part2 = substr($str, $sl + $p, $sl_str - ($sl + $p));
		$part1 = str_replace($sub, "", $part1);
		$str = $part1 . $part2;
	}
	return $str;
}

function str_once_replace($sub, $sub_replacement, $str)
{
	$p = strpos(" " . $str, $sub);
	if ($p > 0) {
		$p--;
		$sl = strlen($sub);
		$sl_str = strlen($str);
		$part1 = substr($str, 0, $sl + $p);
		$part2 = substr($str, $sl + $p, $sl_str - ($sl + $p));
		$part1 = str_replace($sub, $sub_replacement, $part1);
		$str = $part1 . $part2;
	}
	return $str;
}

function say_to_chat($whosay, $chmess, $priv, $towho, $location)
{
	$time_to_chat = 0;

	global $pers, $last_say_to_chat;

	if ($location == 0 and $location <> '*')
		$location = $pers["location"];
	if ($time_to_chat == 0 or empty($time_to_chat))
		$time_to_chat = date("H:i:s");

	if ($last_say_to_chat == 0)
		$last_say_to_chat = time() + microtime(true);
	else
		$last_say_to_chat += 0.1;

	$color = '000000';

	if ($location == '*') $color = '220000';
	if (SQL::q("INSERT INTO `chat` (`user`,`time2`,`message`,`private`,`towho`,`location`,`time`,`color`) VALUES ('" . $whosay . "'," . $last_say_to_chat . ",'" . $chmess . "','" . $priv . "','" . $towho . "','" . $location . "','" . $time_to_chat . "','" . $color . "')"))
		return true;
	else
		return false;
}

function hp_ma_up($chealth, $health, $cmana, $mana, $shp, $sma, $lastom, $tire = -1, $battle = 0)
{
	global $sphp, $spma, $hp, $ma;
	$spma = (700 - $sma * 10);
	$sphp = (700 - $shp * 3.5);
	if ($sphp < 2) $sphp = 2;
	if ($spma < 2) $spma = 2;

	if ($chealth < 0) $chealth = 0;
	if ($cmana < 0) $cmana = 0;

	$p = mtrunc(tme() - $lastom);

	$hp = $p * $health / $sphp + $chealth;
	if ($hp > $health) $hp = $health;
	$ma = $p * $mana / $spma + $cmana;
	if ($ma > $mana) $ma = $mana;
	$hp = floor($hp);
	$ma = floor($ma);

	if (!$battle) {
		$battle = ',`refr`=0';
	} else
		$battle = '';
	$tireout = mtrunc($tire - $p / 30);
	if ($tire > 0)
		return "`chp` = '" . $hp . "',`cma` = '" . $ma . "',`tire`=" . $tireout . ",online=1,`lastom`=" . tme() . "" . $battle;
	else
		return "`chp` = '" . $hp . "',`cma` = '" . $ma . "',online=1,`lastom`=" . tme() . "" . $battle;
}

function catch_user($uid, $passwd = '', $check = 0)
{
	echo $uid;
	if (!$passwd)
		$passwd = filter($passwd);
	if (!$check)
		return SQL::q1("SELECT * FROM `users` WHERE `uid` = " . intval($uid) . "");
	else
		return SQL::q1("SELECT * FROM `users` WHERE `uid` = " . intval($uid) . " and pass='" . $passwd . "';");
}

function update_user($uid)
{
	global $lastom_old, $pers;
	$t = tme();
	if (rand(1, 200) < 2 and !$pers["priveleged"] and $pers["level"] > 5)
		SQL::q("UPDATE `users` SET online=1,`refr`=0,`lastom`=" . $t . ",action=-1 WHERE `uid`=" . $uid);
	elseif ($pers["cfight"] != 0 or $pers["action"] == -1)
		SQL::q("UPDATE `users` SET online=1,`refr`=0,`lastom`=" . $t . " WHERE `uid`=" . $uid);
	return $t;
}

function detect_user($uid, $pass, $block, $action, $waiter, $spass)
{
	//GLOBAL $memcache;
	global $R;
	$t = time();
	global $lastom_new;
	global $lastom_old;
	global $pers;

	/*$LOCK = $memcache->get('LOCK'.$uid);
	$LOCKR = $memcache->get('LOCKR'.$uid);

	## Too fast

if ($LOCK and $LOCKR and intval($LOCKR*10000)!=intval($R*10000))
{
	echo '<script type="text/javascript" src="js/newup.js?2"></script>';
	echo '<script type="text/javascript">too_fast(\'Конфликт с '.$LOCKR.'. Наш поток: '.$R.'\');</script>';
	exit;
}
	##
	*/
	if ($action == -1) {
		//$memcache->set('LOCK'.$uid, 0, false, time()+20);
	}
	if (empty($_POST["code_img"]) and $action == -1 and $waiter < $t) {
		echo '<LINK href=main.css rel=STYLESHEET type=text/css><center class=return_win>Извините пожалуйста, но в связи с появлением программ , позволяющих управлять персонажем без участия игрока, мы вводим защиту против этих программ.<br> Чтобы пройти тест, пожалуйста, введите цифры которые вы видите на картинке в поле для ввода, и нажмите "ОК".<br><script type="text/javascript" src="js/imgcode.js?1"></script><script> imgcode(\'' . md5($lastom_new) . '\') </script></center>';
		exit;
	} elseif (@$_POST["code_img"] and $action == -1) {
		if (uncrypt(md5($lastom_old)) == $_POST["code_img"]) {
			set_vars("action=0", $uid);
		} else {
			if ($waiter < $t) {
				set_vars("waiter=" . ($t + 10) . "", $uid);
				echo '<script type="text/javascript" src="js/newup.js?1"></script>';
				echo "<center class=return_win>Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr><center id=waiter class=inv></center><script>waiter(" . (10) . ");</script>";
				exit;
			} else {
				echo '<script type="text/javascript" src="js/newup.js?1"></script>';
				echo "<center class=return_win>Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr><div id=waiter class=items align=center></div><script>waiter(" . ($waiter - $t) . ");</script>";
				exit;
			}
		}
	}

	// if (UID<>$uid or PASS<>$pass or USER=='' or SPASS<>$spass)
	//  {
	// 	include ("./error.html");
	// 	exit;
	//  }
	if ($block <> '') {
		echo "<script>top.location='index.php';</script>";
		exit;
	}
}

function begin_fight($names, $namesvs, $type, $travm, $timeout, $oruj, $loc, $battle_type = 0, $closed = 0, $special = 0)
{
	global $pers;
	$closed = intval($closed);
	$bots_in = 0;
	$loc = intval($loc);
	#Отключаем тактические бои.
	$b = SQL::q1("SELECT boi FROM users WHERE uid=" . $pers['uid'] . "");
	if ($b["boi"] == "clask" and $loc > 0) {
		$loc = 0;
	}
	// $loc=0; 
	if ($loc == 0) {
		$maxx = 1;
		$maxy = 1;
	} elseif ($loc < 6) {
		$maxx = 15;
		$maxy = 5;
	}

	if ($loc) $bplace = SQL::q1("SELECT * FROM battle_places WHERE id=" . $loc);
	global $k, $main_conn;

	$idf = 0;
	$help_param = 0;
	while (($idf < 11) and ($help_param < 100)) {
		$help_param++;
		$idf = SQL::qi("INSERT INTO `fights` (`oruj`,`travm`,`timeout`,`ltime`,`bplace`,`maxx`,`maxy`,`stones`,`closed`,`special`)
		VALUES ('" . $oruj . "','" . $travm . "'," . $timeout . " ," . tme() . "," . $loc . "," . $maxx . "," . $maxy . "," . intval($battle_type) . "," . $closed . "," . intval($special) . ")");
	}
	$bot_id_max = $idf * 100;

	if ($names[strlen($names) - 1] == '|') $names = substr($names, 0, strlen($names) - 1);
	if ($namesvs[strlen($namesvs) - 1] == '|') $namesvs = substr($namesvs, 0, strlen($namesvs) - 1);

	$all = 'Бой между ';
	unset($turns);
	$turns[0] = '';
	unset($exps);
	$exps[0] = 0;
	$n = -1;
	$i = 0;
	$PLAYERS = 0;
	$tmp1 = explode("|", $names);
	$T1_count = count($tmp1);
	$xf = 4 - intval($T1_count / $maxy);
	$yf = floor($maxy / 2) - 1;
	$persons = array();
	foreach ($tmp1 as $tmp) {
		if ($loc > 0)
			while (substr_count($bplace["xy"], "|" . $xf . "_" . $yf . "|") and $xf > 0) {
				$yf++;
				if ($yf % $maxy == 0) {
					$yf = 0;
					$xf--;
				}
			}
		$PLAYERS++;
		$bplace["xy"] .= "|" . $xf . "_" . $yf . "|";
		if (strpos(" " . $tmp, "bot=") > 0) {
			$e = explode("=", $tmp);
			$p = SQL::q1("SELECT * FROM `bots` WHERE `id`='" . $e[1] . "'");

			if (@$p["id"]) {
				$p["rank_i"] = ($p["s1"] + $p["s2"] + $p["s3"] + $p["s4"] + $p["s5"] + $p["s6"] + $p["kb"]) * 0.3 + ($p["mf1"] + $p["mf2"] + $p["mf3"] + $p["mf4"]) * 0.03 + ($p["hp"] + $p["ma"]) * 0.04 + ($p["udmin"] + $p["udmax"]) * 0.3;
				$bot_id_max++;
				SQL::q("INSERT INTO `bots_battle` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `chp` , `cma` , `id` , `pol` , `obr` , `wears` , `rank_i` , `cfight` , `fteam` , `xf` , `yf` , `bid`, `id_skin` , `droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`,`special`) VALUES ('" . $p["user"] . "', '" . $p["level"] . "', 'none', '" . $p["s1"] . "', '" . $p["s2"] . "', '" . $p["s3"] . "', '" . $p["s4"] . "', '" . $p["s5"] . "', '" . $p["s6"] . "', '" . $p["kb"] . "', '" . $p["mf1"] . "', '" . $p["mf2"] . "', '" . $p["mf3"] . "', '" . $p["mf4"] . "', '" . $p["mf5"] . "', '" . $p["udmin"] . "', '" . $p["udmax"] . "', '" . $p["hp"] . "', '" . $p["ma"] . "', '" . $p["hp"] . "', '" . $p["ma"] . "', '" . (-1 * $bot_id_max) . "' , 'male', '" . $p["obr"] . "', '', '" . $p["rank_i"] . "', '" . $idf . "', '1', '" . $xf . "', '" . $yf . "', '" . $p["id"] . "'," . $p["id_skin"] . "," . intval($p["droptype"]) . "," . intval($p["dropvalue"]) . "," . intval($p["dropfrequency"]) . "," . intval($p["magic_resistance"]) . "," . intval($p["special"]) . ");");
				$bots_in = 1;
			} else {
				array_splice($tmp, $i, 1);
			}
		} else {
			$p = SQL::q1("SELECT user,level,sign,rank_i,chp,hp,cma,ma,sm6,sm7,lastom,uid,invisible,tire FROM `users` WHERE `user`='" . $tmp . "'");
			SQL::q("UPDATE `users` SET `xf`=" . $xf . ",`yf`=" . $yf . "," . hp_ma_up($p["chp"], $p["hp"], $p["cma"], $p["ma"], $p["sm6"], $p["sm7"], $p["lastom"], $p["tire"], 1) . ",`cfight`='" . $idf . "' ,`curstate`=4 , `refr`=1 , damage_get=chp , damage_give=0 , fteam = 1 WHERE `uid`='" . $p["uid"] . "'");
			$p["lib"] = $p["user"];
			if ($p["invisible"] > tme()) {
				$p["user"] = 'невидимка';
				$p["sign"] = 'none';
				$p["level"] = '??';
			}
			$persons[] = $p["uid"];
		}

		$all .= "<img src=images/signs/" . $p['sign'] . ".gif><font class=bnick color=#087C20>" . $p["user"] . "</font>[<font class=lvl>" . $p["level"] . "</font>] ,";
		$i++;
	}

	if ($PLAYERS == 0) return false;

	$all = substr($all, 0, strlen($all) - 1);
	$all .= 'и ';
	$tmp2 = explode("|", $namesvs);
	$i = 0;
	$T2_count = count($tmp2);
	$xf = $maxx - (4 - intval($T2_count / $maxy));
	$yf = floor($maxy / 2) - 1;
	foreach ($tmp2 as $tmp) {
		if ($loc > 0)
			while (substr_count($bplace["xy"], "|" . $xf . "_" . $yf . "|") and $xf < $maxx) {
				$yf++;
				if ($yf % $maxy == 0) {
					$yf = 0;
					$xf++;
				}
			}
		$PLAYERS++;
		$bplace["xy"] .= "|" . $xf . "_" . $yf . "|";
		if (strpos(" " . $tmp, "bot=") > 0) {
			$e = explode("=", $tmp);
			$p = SQL::q1("SELECT * FROM `bots` WHERE `id`='" . $e[1] . "'");
			if (@$p["id"]) {
				$p["rank_i"] = ($p["s1"] + $p["s2"] + $p["s3"] + $p["s4"] + $p["s5"] + $p["s6"] + $p["kb"]) * 0.3 + ($p["mf1"] + $p["mf2"] + $p["mf3"] + $p["mf4"]) * 0.03 + ($p["hp"] + $p["ma"]) * 0.04 + ($p["udmin"] + $p["udmax"]) * 0.3;
				$bot_id_max++;
				SQL::q("INSERT INTO `bots_battle` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `chp` , `cma` , `id` , `pol` , `obr` , `wears` , `rank_i` , `cfight` , `fteam` , `xf` , `yf` , `bid`, `id_skin` , `droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`,`special`)
		VALUES (
		'" . $p["user"] . "', '" . $p["level"] . "', 'none', '" . $p["s1"] . "', '" . $p["s2"] . "', '" . $p["s3"] . "', '" . $p["s4"] . "', '" . $p["s5"] . "', '" . $p["s6"] . "', '" . $p["kb"] . "', '" . $p["mf1"] . "', '" . $p["mf2"] . "', '" . $p["mf3"] . "', '" . $p["mf4"] . "', '" . $p["mf5"] . "', '" . $p["udmin"] . "', '" . $p["udmax"] . "', '" . $p["hp"] . "', '" . $p["ma"] . "', '" . $p["hp"] . "', '" . $p["ma"] . "', '" . (-1 * $bot_id_max) . "' , 'male', '" . $p["obr"] . "', '', '" . $p["rank_i"] . "', '" . $idf . "', '2', '" . $xf . "', '" . $yf . "', '" . $p["id"] . "'," . $p["id_skin"] . "," . intval($p["droptype"]) . "," . intval($p["dropvalue"]) . "," . intval($p["dropfrequency"]) . "," . intval($p["magic_resistance"]) . "," . intval($p["special"]) . ");");
				$bots_in = 1;
			} else
				array_splice($tmp2, $i, 1);
		} else {
			$p = sql::q1("SELECT user,level,sign,rank_i,chp,hp,cma,ma,sm6,sm7,lastom,uid,invisible,tire FROM `users` WHERE `user`='" . $tmp . "'");
			sql::q("UPDATE `users` SET `xf`=" . $xf . ",`yf`=" . $yf . "," . hp_ma_up($p["chp"], $p["hp"], $p["cma"], $p["ma"], $p["sm6"], $p["sm7"], $p["lastom"], $p["tire"], 1) . ",`cfight`='" . $idf . "' ,`curstate`=4 , `refr`=1 , damage_get=chp , damage_give=0 , fteam = 2 WHERE `uid`='" . $p["uid"] . "'");
			$p["lib"] = $p["user"];
			if ($p["invisible"] > tme()) {
				$p["user"] = 'невидимка';
				$p["sign"] = 'none';
				$p["level"] = '??';
			}
			$persons[] = $p["uid"];
		}

		$all .= "<img src=images/signs/" . $p['sign'] . ".gif><font class=bnick color=#0052A6>" . $p["user"] . "</font>[<font class=lvl>" . $p["level"] . "</font>] ,";
		$i++;
	}

	if ($i == 0) return false;

	$bots_in = ($bots_in) ? 0 : 1;
	$all = addslashes(substr($all, 0, strlen($all) - 1) . ".(" . $type . ")");

	sql::q("UPDATE fights SET players=" . $PLAYERS . " , nobots=" . intval($bots_in) . ", closed=" . $closed . " WHERE id=" . $idf . "");
	add_flog($all, $idf);


	$names = $tmp1;
	$namesvs = $tmp2;
	$query1 = '';
	$query2 = '';
	foreach ($names as $n)
		$query1 .= "`user`='" . $n . "' or";
	foreach ($namesvs as $n)
		$query2 .= "`user`='" . $n . "' or";
	$query1 = substr($query1, 0, strlen($query1) - 2);
	$query2 = substr($query2, 0, strlen($query2) - 2);

	foreach ($persons as $p) {
		SQL::q("INSERT INTO `battle_logs` (`uid` ,`time` ,`cfight` ,`text` )
	VALUES ('" . $p . "', '" . tme() . "', '" . $idf . "', '" . $all . "');");
	}

	return $idf;
}

function set_vars($vars, $uid)
{
	if (!$uid) {
		global $pers;
		$uid = $pers["uid"];
	}
	if ($vars) {
		// SQL::q("UPDATE users SET ".$vars." WHERE uid=".intval($uid)."");
		return sql::q("UPDATE users SET " . $vars . " WHERE uid=" . intval($uid) . "");
	} else
		return false;
}

function aura_on($aid, $pers, $persto, $get_mana = 1)
{
	$a = sql::q1("SELECT * FROM u_auras WHERE id=" . intval($aid) . "");
	if (
		$a and $a["manacost"] <= $pers["cma"] and $a["tlevel"] <= $pers["level"]
		and $a["ts6"] <= $pers["s6"] and $a["tm1"] <= $pers["m1"] and $a["tm2"] <= $pers["m2"] and $a["cur_colldown"] <= tme() and $a["cur_turn_colldown"] <= $pers["f_turn"]
	) {
		$params = explode("@", $a["params"]);
		$nparams = '';
		foreach ($params as $par) {
			if (!$par) continue;
			$p = explode("=", $par);
			if ($p[1][strlen($p[1]) - 1] == '%') {
				$res = floor((intval($p[1]) / 100) * $persto[$p[0]]);
				if ($res) {
					$persto[$p[0]] += $res;
					$nparams .= $p[0] . '=' . $res . '@';
				}
			} else {
				$persto[$p[0]] += $p[1];
				$nparams .= $p[0] . '=' . $p[1] . '@';
			}
		}
		if ($a["special"] == 1) {
			$silence = time() + $a["esttime"];
			if ($persto["silence"] < $silence) $persto["silence"] = $silence;
		}
		if ($a["special"] == 2) {
			$inv = time() + $a["esttime"];
			if ($persto["invisible"] < $inv) $persto["invisible"] = $inv;
		}
		if ($persto["chp"] > $persto["hp"]) $persto["chp"] = $persto["hp"];
		if ($persto["cma"] > $persto["ma"]) $persto["cma"] = $persto["ma"];
		if ($persto["chp"] < 0) $persto["chp"] = 0;
		if ($persto["cma"] < 0) $persto["cma"] = 0;
		if ($pers["uid"] == $persto["uid"]) $pers = $persto;
		set_vars(aq($persto), $persto["uid"]);
		sql::q("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'" . $persto["uid"] . "', '" . (time() + $a["esttime"]) . "', '" . ($persto["f_turn"] + $a["turn_esttime"]) . "', '" . $a["name"] . "', '" . $a["image"] . "', '" . $nparams . "' , " . $a["special"] . "
		);
		");
		if ($a["autocast"] and $pers["uid"] == $persto["uid"]) {
			$autocast = $a["id"];
			sql::q("INSERT INTO `p_auras`
			( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `autocast`)
			VALUES (
			'" . $persto["uid"] . "', '" . (tme() + $a["colldown"] + 5) . "', '0', '" . $a["name"] . " [Автокаст]', '" . $a["image"] . "', '', " . $autocast . "
			);
			");
		}
		if ($get_mana) {
			$pers["cma"] -= $a["manacost"];
			$pers["m" . $a["type"]] += 1 / ($pers["m" . $a["type"]] + 1);
			set_vars("cma=" . $pers["cma"] . ",m1=" . $pers["m1"] . ",m2=" . $pers["m2"], $pers["uid"]);
			if ($pers["curstate"] == 4)
				$cur_turn_colldown = ",cur_turn_colldown=turn_colldown+" . $pers["f_turn"] . "";
			else
				$cur_turn_colldown = "";
			sql::q("UPDATE `u_auras` SET cur_colldown=" . tme() . "+colldown" . $cur_turn_colldown . " WHERE id=" . $a["id"]);
			//echo "UPDATE `u_auras` SET cur_colldown=".tme()."+colldown".$cur_turn_colldown." WHERE id=".$a["id"];
		}
	}
	return $a;
}

function aura_on2($aid, $persto, $koef = 1)
{
	$a = sql::q1("SELECT * FROM auras WHERE id=" . intval($aid) . "");
	if (is_scalar($persto))
		$persto = catch_user($persto);
	if ($a) {
		$params = explode("@", $a["params"]);
		$nparams = '';
		foreach ($params as $par) {
			if (!$par) continue;
			$p = explode("=", $par);
			if ($p[1][strlen($p[1]) - 1] == '%') {
				$res = floor((intval($p[1]) / 100) * $persto[$p[0]]) * $koef;
				$persto[$p[0]] += $res;
				$nparams .= $p[0] . '=' . $res . '@';
			} else {
				$res = $p[1] * $koef;
				$persto[$p[0]] += $res;
				$nparams .= $p[0] . '=' . $res . '@';
			}
		}
		if ($a["special"] == 1) {
			$silence = time() + $a["esttime"];
			if ($persto["silence"] < $silence) $persto["silence"] = $silence;
		}
		if ($a["special"] == 2) {
			$inv = time() + $a["esttime"];
			if ($persto["invisible"] < $inv) $persto["invisible"] = $inv;
		}
		if ($persto["chp"] > $persto["hp"]) $persto["chp"] = $persto["hp"];
		if ($persto["cma"] > $persto["ma"]) $persto["cma"] = $persto["ma"];
		if ($persto["chp"] < 0) $persto["chp"] = 0;
		if ($persto["cma"] < 0) $persto["cma"] = 0;
		set_vars(aq($persto), $persto["uid"]);
		sql::q1("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'" . $persto["uid"] . "', '" . (time() + $a["esttime"]) . "', '" . ($persto["f_turn"] + $a["turn_esttime"]) . "', '" . $a["name"] . "', '" . $a["image"] . "', '" . $nparams . "', " . $a["special"] . "
		);
		");
	}
	return $a;
}

function light_aura_on($a, $uid)
{
	global $persto;
	if (intval($uid) == 0) return;
	sql::q("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'" . intval($uid) . "', '" . (time() + $a["esttime"]) . "', '" . ($persto["f_turn"] + $a["turn_esttime"]) . "', '" . $a["name"] . "', '" . $a["image"] . "', '" . $a["params"] . "', " . $a["special"] . "
		);
		");
}

function show_pers_in_f($_pers, $inv)
{
	$s = '<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top width=221 colspan=3><script>';
	global $sh, $oj, $or1, $or2, $sa, $na, $po, $pe, $br, $kam1, $kam2, $kam3, $kam4, $z1, $z2, $z3, $ko1, $ko2, $pers;
	if ($_pers["uid"] <> UID) {
		$perst = $pers;
		$pers = $_pers;
		include('inc/inc/p_clothes.php');
		$pers = $perst;
		unset($perst);
	}
	if ($_pers["invisible"] > tme() and $_pers["uid"] <> $_COOKIE["uid"]) {
		$wears = array();
		for ($i = 0; $i < 18; $i++) {
			$m = array();
			$m["image"] = 'slots/pob' . ($i + 1);
			$m["id"] = "0";
			$wears[$i] = $m;
		}

		$sh = $wears[0];
		$na = $wears[8];
		$oj = $wears[1];
		$pe = $wears[9];
		$or1 = $wears[2];
		$or2 = $wears[10];
		$po = $wears[3];
		$z1 = $wears[4];
		$z2 = $wears[5];
		$z3 = $wears[6];
		$sa = $wears[7];
		$ko1 = $wears[11];
		$ko2 = $wears[12];
		$br = $wears[13];
		$kam1 = $wears[14];
		$kam2 = $wears[15];
		$kam3 = $wears[16];
		$kam4 = $wears[17];
		$_pers["obr"] = 'invisible';
		$_pers["user"] = '<i>невидимка</i>';
		$_pers["sign"] = 'none';
		$_pers["level"] = '??';
		$_pers["aura"] = '';
		$_pers["s1"] = '??';
		$_pers["s2"] = '??';
		$_pers["s3"] = '??';
		$_pers["s4"] = '??';
		$_pers["s5"] = '??';
		$_pers["s6"] = '??';
		$_pers["kb"] = '??';
		$_pers["mf1"] = '??';
		$_pers["mf2"] = '??';
		$_pers["mf3"] = '??';
		$_pers["mf4"] = '??';
		$_pers["mf5"] = '??';
		$_pers["hp"] = '1';
		$_pers["chp"] = '1';
		$_pers["ma"] = '1';
		$_pers["cma"] = '1';
	}
	$s .= "InFight=1;";
	$s .= "show_pers_new('" . $sh["image"] . "','" . $sh["id"] . "','" . $oj["image"] . "','" . $oj["id"] . "','" . $or1["image"] . "','" . $or1["id"] . "','" . $po["image"] . "','" . $po["id"] . "','" . $z1["image"] . "','" . $z1["id"] . "','" . $z2["image"] . "','" . $z2["id"] . "','" . $z3["image"] . "','" . $z3["id"] . "','" . $sa["image"] . "','" . $sa["id"] . "','" . $na["image"] . "','" . $na["id"] . "','" . $pe["image"] . "','" . $pe["id"] . "','" . $or2["image"] . "','" . $or2["id"] . "','" . $ko1["image"] . "','" . $ko1["id"] . "','" . $ko2["image"] . "','" . $ko2["id"] . "','" . $br["image"] . "','" . $br["id"] . "','" . $_pers["pol"] . "_" . $_pers["obr"] . "'," . $inv . ",'" . $_pers["sign"] . "','" . $_pers["user"] . "','" . $_pers["level"] . "','" . $_pers["chp"] . "','" . $_pers["hp"] . "','" . $_pers["cma"] . "','" . $_pers["ma"] . "'," . intval($_pers["tire"]) . ",'" . $kam1["image"] . "','" . $kam2["image"] . "','" . $kam3["image"] . "','" . $kam4["image"] . "','" . $kam1["id"] . "','" . $kam2["id"] . "','" . $kam3["id"] . "','" . $kam4["id"] . "');";
	$s .= '</script></td></tr><tr><td>';

	if ($_pers["invisible"] < tme() or $pers["uid"] == $_pers["uid"]) {
		if ($_pers["uid"])
			//$s .= "<div id=prs".$_pers["uid"]." class=aurasc></div>";
			$s .= '<br><script>document.write(sbox2b(1,1));</script><div id=prs' . $_pers["uid"] . ' class=aurasc style="text-align:center;"></div><script>document.write(sbox2e());</script>';
		$s .= "<table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td valign=top>";
		$r = all_params();
		$r[12] = 'rank_i';
		for ($i = 0; $i < 13; $i++) {
			//if ($_pers[$r[$i]]==0) continue;
			if ($r[$i][0] == 's') {
				$td_class = 'user';
				$img = '<img src="images/DS/stats_s' . $r[$i][1] . '.png">';
			} else {
				$td_class = 'mf';
				$img = '';
			}
			$s .= '<tr>';
			$s .= '<td class=' . $td_class . ' width=150 nowrap>' . $img . name_of_skill($r[$i]);
			$s .= '</td>';
			if ($i < 6) {
				if ($_pers["uid"] == UID || $pers[$r[$i]] == $_pers[$r[$i]])
					$s .= '<td class=user align=right>' . $_pers[$r[$i]] . '</td>';
				elseif ($pers[$r[$i]] > $_pers[$r[$i]])
					$s .= '<td class=user align=right><b style="color:#990000">' . $_pers[$r[$i]] . '</b></td>';
				else
					$s .= '<td class=user align=right><b style="color:#009900">' . $_pers[$r[$i]] . '</b></td>';
			} elseif ($i == 6 or $i == 12) {
				if ($_pers["uid"] == UID || $pers[$r[$i]] == $_pers[$r[$i]])
					$s .= '<td class=mfb align=right><b>' . $_pers[$r[$i]] . '</b></td>';
				elseif ($pers[$r[$i]] > $_pers[$r[$i]])
					$s .= '<td class=mfb align=right><b style="color:#990000">' . $_pers[$r[$i]] . '</b></td>';
				else
					$s .= '<td class=mfb align=right><b style="color:#009900">' . $_pers[$r[$i]] . '</b></td>';
			} else {
				if ($_pers["uid"] == UID || $pers[$r[$i]] == $_pers[$r[$i]])
					$s .= '<td class=mfb align=right><b>' . $_pers[$r[$i]] . '%</b></td>';
				elseif ($pers[$r[$i]] > $_pers[$r[$i]])
					$s .= '<td class=mfb align=right><b style="color:#990000">' . $_pers[$r[$i]] . '%</b></td>';
				else
					$s .= '<td class=mfb align=right><b style="color:#009900">' . $_pers[$r[$i]] . '%</b></td>';
			}
			$s .= '</tr>';
		}
		$s .= '</table>';
		$s .= '</td></tr></table>';

		if ($_pers["uid"]) {
			$as = SQL::q1("SELECT * FROM p_auras WHERE uid=" . $_pers["uid"] . "");
			$txt = '';
			foreach ($as as $a) {
				$txt .= $a["image"] . '#<b>' . $a["name"] . '</b>@';
				$txt .= 'Осталось <i class=timef>' . tp($a["esttime"] - time()) . '</i>';
				$params = explode("@", $a["params"]);
				foreach ($params as $par) {
					$p = explode("=", $par);
					$perc = '';
					if (substr($p[0], 0, 2) == 'mf') $perc = '%';
					if ($p[1] and $p[0] <> 'cma' and $p[0] <> 'chp')
						$txt .= '@' . name_of_skill($p[0]) . ':<b>' . plus_param($p[1]) . $perc . '</b>';
				}
				$txt .= '|';
			}
			$s .= "<script>view_auras('" . $txt . "','prs" . $_pers["uid"] . "');</script>";
		}
	} else {
		$s .= '</td></tr></table>';
	}
	return $s;
}

function build_go_string($locid, $time)
{
	$str = md5(strtoupper($time . $locid . count($locid)));
	$str = "onclick=\"top.goloc('" . $locid . "','" . $str . "')\"";
	return $str;
}



function sqla($q)
{
	return SQL::q1($q);
}

function sqlr($q, $count = 0) // получаем 1 строку
{
}

function sql($q)
{
	return SQL::q($q);
}

function showWeapon(array $id)
{
	$weapons = SQL::q("SELECT * FROM weapons WHERE id = ?", $id);
	// var_dump($id);
	$showWindows = '';
	foreach ($weapons as $w) {
		$showWindows .= "
		<div class=weapons_box>
			<div class=weapon_name>{$w['name']}</div>
			<div class=weapon_img_xap>
				<div><img src='images/weapons/{$w['image']}.gif'></div>
				<div>Характеристики:</div>
			</div>
			<div>Кнопки:</div>
		</div>";
	}
	// if ($z==1 and $napad=='' and ($v["type"]=='shlem' or $v["type"]=='orujie' or $v["type"]=='kolco' or $v["type"]=='bronya' or $v["type"]=='naruchi' or $v["type"]=='perchatki' or $v["type"]=='ojerelie' or $v["type"]=='sapogi' or $v["type"]=='poyas' or $v["type"]=='kam') and ($pers["sign"]==$v["tsign"] or $v["tsign"]=='none')) $buttons .= "<td><img title='Надеть' src=images/icons/upload.png onclick=\"location='main.php?wear=".$vesh["id"]."'\" style='cursor:pointer'></td>";
	return $showWindows;
}


function mtrunc($q)
{
	if ($q < 0) $q = 0;
	return $q;
}

function show_ip()
{
	if ($ip_address = getenv("HTTP_CLIENT_IP"));
	elseif ($ip_address = getenv("HTTP_X_FORWARDED_FOR"));
	else $ip_address = getenv("REMOTE_ADDR");
	return $ip_address;
}
function sqr($x)
{
	return $x * $x;
}

function mod_st_start($name, $string)
{
	global $module_statisticks, $module_statisticks_counter, $sql_queries_counter, $sql_queries_timer;
	$i = $module_statisticks_counter + 1;
	$module_statisticks[$i]["name"] = $name;
	$module_statisticks[$i]["strings"] = $string;
	$module_statisticks[$i]["sql_queries"] = $sql_queries_counter;
	$module_statisticks[$i]["sql_time"] = $sql_queries_timer;
	$module_statisticks[$i]["all_exec_time"] = time() + microtime();
}

function mod_st_fin()
{
	global $module_statisticks, $module_statisticks_counter, $sql_queries_counter, $sql_queries_timer;
	$i = $module_statisticks_counter + 1;
	$module_statisticks[$i]["sql_queries"] = $sql_queries_counter -
		$module_statisticks[$i]["sql_queries"];
	$module_statisticks[$i]["sql_time"] = $sql_queries_timer -
		$module_statisticks[$i]["sql_time"];
	$module_statisticks[$i]["all_exec_time"] = time() + microtime() -
		$module_statisticks[$i]["all_exec_time"];
	$module_statisticks_counter++;
}

function insert_wp($id, $uid, $durability = -1, $weared = 0, $user = '', $weight = -1) // внести изменения дл клановых шмоток на сервер v[tsign]
{
	$uid = intval($uid);
	if (is_scalar($id))
		$v = sql::q1("SELECT * FROM weapons WHERE id='" . $id . "'");
	else
		$v = $id;
	$id = $v["id"];
	if ($durability == -1) $durability = $v["max_durability"];
	if ($weight == -1) $weight = $v["weight"];
	if (empty($v["id"])) return 0;
	global $main_conn, $pers;
	$user = sql::q1("SELECT user FROM users WHERE uid=" . $uid);
	$user = $user['user'];
	$_colls = '';
	$_params = '';
	$r = all_params();
	foreach ($r as $param) {
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
		$param = 't' . $param;
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
	}
	$result = sql::qi("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` , `present` , `clan_sign` , `clan_name` ,`radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` , `tsign` ,`p_type` , `user`, `material_show`, `material` " . $_colls . ")
VALUES (0, '" . $uid . "', '" . $weared . "','" . $id . "','" . $v["price"] . "', '" . $v["dprice"] . "', '" . $v["image"] . "', '" . $v["index"] . "', '" . $v["type"] . "', '" . $v["stype"] . "', '" . $v["name"] . "', '" . $v["describe"] . "', '" . $weight . "', '" . $v["where_buy"] . "', '" . $v["max_durability"] . "', '" . $durability . "', '" . $v["present"] . "', '', '', '" . $v["radius"] . "', '" . $v["slots"] . "', '" . $v["arrows"] . "', '" . $v["arrows_max"] . "', '" . $v["arrow_name"] . "', '" . $v["arrow_price"] . "', '" . $v["tlevel"] . "', '" . $v["tsign"] . "', '" . $v["p_type"] . "', '" . $user . "', '" . $v["material_show"] . "', '" . $v["material"] . "' " . $_params . ");");

	return $result;
}


function buy_prim_mayk($id, $uid, $durability)
{
	global $weared;
	$uid = intval($uid);
	if (is_scalar($id))
		$v = sql::q1("SELECT * FROM weapons WHERE id='" . $id . "'");
	else
		$v = $id;
	$id = $v["id"];
	if ($durability == -1) $durability = $v["max_durability"];
	if (empty($v["id"])) return 0;
	global $main_conn, $pers;
	$user = sql::q1("SELECT user FROM users WHERE uid=" . $uid);
	$user = $user['user'];
	$_colls = '';
	$_params = '';
	$r = all_params();
	foreach ($r as $param) {
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
		$param = 't' . $param;
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
	}
	$result = sql::qi("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` , `present` , `clan_sign` , `clan_name` ,`radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` ,`p_type` , `user`, `material_show`, `material` " . $_colls . ")
VALUES (0, '" . $uid . "', '" . $weared . "','" . $id . "','" . $v["price"] . "', '" . $v["dprice"] . "', '" . $v["image"] . "', '" . $v["index"] . "', '" . $v["type"] . "', '" . $v["stype"] . "', '" . $v["name"] . "', '" . $v["describe"] . "', '" . $v["weight"] . "', '" . $v["where_buy"] . "', '" . $durability . "', '" . $durability . "', '" . $v["present"] . "', '', '', '" . $v["radius"] . "', '" . $v["slots"] . "', '" . $v["arrows"] . "', '" . $v["arrows_max"] . "', '" . $v["arrow_name"] . "', '" . $v["arrow_price"] . "', '" . $v["tlevel"] . "','" . $v["p_type"] . "', '" . $user . "', '" . $v["material_show"] . "', '" . $v["material"] . "' " . $_params . ");");
	return $result;
}

function insert_wp_new($uid, $teta, $user = '')
{
	$v = sql::q1("SELECT * FROM wp WHERE " . $teta . " LIMIT 1;");
	if (!$v["id"]) return false;
	$_colls = '';
	$_params = '';
	$r = all_params();
	foreach ($r as $param) {
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
		$param = 't' . $param;
		if ($v[$param] != 0) {
			$_colls .= ',`' . $param . '`';
			$_params .= ",'" . $v[$param] . "'";
		}
	}
	global $main_conn;
	$user = sql::q1("SELECT user FROM users WHERE uid=" . $uid);
	$user = $user['user'];
	$rees = sql::qi("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` , `present` , `clan_sign` , `clan_name` ,`radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` ,`p_type`,`timeout` , `user`,`material_show`,`material` " . $_colls . ")
	VALUES (0, '" . $uid . "', 0,'" . $v["id_in_w"] . "','" . $v["price"] . "', '" . $v["dprice"] . "', '" . $v["image"] . "', '" . $v["index"] . "', '" . $v["type"] . "', '" . $v["stype"] . "', '" . $v["name"] . "', '" . $v["describe"] . "', '" . $v["weight"] . "', '" . $v["where_buy"] . "', '" . $v["max_durability"] . "', '" . $v["durability"] . "', '" . $v["present"] . "', '', '', '" . $v["radius"] . "', '" . $v["slots"] . "', '" . $v["arrows"] . "', '" . $v["arrows_max"] . "', '" . $v["arrow_name"] . "', '" . $v["arrow_price"] . "', '" . $v["tlevel"] . "','" . $v["p_type"] . "','" . $v["timeout"] . "', '" . $v["user"] . "', '" . $v["material_show"] . "', '" . $v["material"] . "' " . $_params . ");");

	$v["id"] = $rees;
	$v["uidp"] = $uid;
	return $v;
}

function insert_blast($id, $uid)
{
	$z = sql::q1("SELECT * FROM blasts WHERE `id`=" . intval($id));
	if (!$z) return false;
	$q = 'INSERT INTO `u_blasts` ( `id` , `id_in_w`';
	$v = ")VALUES ('0', '" . $z["id"] . "'";
	foreach ($z as $key => $value) {
		if (is_string($key) and $key <> "id" and $key <> "learnall") {
			$q .= ',`' . $key . '`';
			$v .= ",'" . $value . "'";
		}
	}
	$q .= ',`uidp`';
	$v .= ',' . intval($uid) . ');';
	$result = sql::qi($q . $v);
	return $result;
}

function insert_aura($id, $uid)
{
	$z = sql::q1("SELECT * FROM auras WHERE `id`=" . intval($id));
	if (!$z) return false;
	$q = 'INSERT INTO `u_auras` ( `id` , `id_in_w`';
	$v = ")VALUES ('0', '" . $z["id"] . "'";
	foreach ($z as $key => $value) {
		if (is_string($key) and $key <> "id" and $key <> "learnall") {
			$q .= ',`' . $key . '`';
			$v .= ",'" . $value . "'";
		}
	}
	$q .= ',`uidp`';
	$v .= ',' . intval($uid) . ');';
	$result = sql::qi($q . $v);
	return $result;
}
//снимаем вещи с перса
function remove_weapon($id, $v)
{
	global $pers;
	if (!is_array($v)) $v = SQL::q1("SELECT * FROM `wp` WHERE `id` = '" . $id . "' and weared=1 and uidp=" . $pers["uid"] . "");
	if ($v) {
		$r = all_params();
		foreach ($r as $a)
			if ($v[$a]) $pers[$a] -= $v[$a];
		$pers["hp"] -= 5 * $v["s4"];
		$pers["ma"] -= 9 * $v["s6"];
		if ($aq = aq($pers))
			SQL::q("UPDATE `users` SET " . $aq . " WHERE `uid` = " . UID . " ;");
		SQL::q("UPDATE wp SET weared=0 WHERE id=" . $v["id"] . "");
	}
}

function remove_all_weapons()
{
	global $pers;
	$res = "SELECT * FROM `wp` WHERE `weared` = 1 and uidp=" . $pers["uid"] . "";
	foreach (SQL::q($res) as $v) {
		$r = all_params();
		foreach ($r as $a)
			if ($v[$a]) $pers[$a] -= $v[$a];
		$pers["hp"] -= 5 * $v["s4"];
		$pers["ma"] -= 9 * $v["s6"];
	}
	if ($aq = aq($pers))
		SQL::q("UPDATE `users` SET " . $aq . " WHERE `uid` = " . $pers["uid"] . " ;");
	SQL::q("UPDATE wp SET weared=0 WHERE uidp=" . $pers["uid"] . "");
}

function remove_all_auras()
{
	global $pers;
	$as = "SELECT * FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . tme() . " and (turn_esttime<=" . $pers["f_turn"] . ")";

	$count = 0;
	//$autoAS = Array();
	$modified = 0;
	foreach (SQL::q($as) as $a) {
		$count++;
		$params = explode("@", $a["params"]);
		foreach ($params as $par) {
			$p = explode("=", $par);
			if ($p[0] <> 'cma' and $p[0] <> 'chp' and intval($p[1]) != 0) {
				$pers[$p[0]] -= $p[1];
				$modified = 1;
			}
		}
		if ($a["special"] == 14) {
			$a["image"] = 68;
			$a["params"] = '';
			$a["esttime"] = 1800;
			$a["name"] = 'Отдышка после шахты';
			$a["special"] = 15;
			light_aura_on($a, $pers["uid"]);
		}
		/*	if($a["autocast"])
			$autoAS[] = $a["autocast"];*/
	}
	if ($modified) {
		if (set_vars(aq($pers), $pers["uid"]))
			SQL::q("DELETE FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . tme() . " and (turn_esttime<=" . $pers["f_turn"] . ") and autocast=0");
	} elseif ($count)
		SQL::q("DELETE FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . tme() . " and (turn_esttime<=" . $pers["f_turn"] . ") and autocast=0");
	/*
if(!$pers["cfight"])
	foreach($autoAS as $a)
	{
		aura_on($a,$pers,$pers);
		SQL::q("DELETE FROM p_auras WHERE uid=".$pers["uid"]." and autocast=".intval($a));
	}*/
}

function dress_weapon($id_of_weapon, $checker)
{
	global $pers;
	$i = 5;
	$v = SQL::q1("SELECT * FROM `wp` WHERE `id`= " . $id_of_weapon . " and uidp=" . $pers["uid"] . " and weared=0");
	if (@$v["id"]) {
		$z = 1;
		if ($pers["level"] < $v["tlevel"])
			$z = 0;
		if (!$checker)
			foreach ($v as $key => $value) {
				if ($key[0] == 't' and $key <> 'timeout')
					if ($pers[substr($key, 1, strlen($key) - 1)] < $value and $value > 0)
						$z = 0;
				if ($z == 0)
					break;
			}

		if ($z == 1) {
			$r = all_params();
			foreach ($r as $a)
				if ($v[$a]) $pers[$a] += $v[$a];
			$pers["hp"] += 5 * $v["s4"];
			$pers["ma"] += 9 * $v["s6"];
			//Снимаем то что было
			$z = 0;
			if ($v["type"] == 'orujie') {

				$tmp = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='orujie';");
				if ($tmp['count'] >= 2) {
					if ($v["stype"] == 'noji' or $v["stype"] == 'shit') {
						$w_for_remove = SQL::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='orujie' and (stype='noji' or stype='shit')");
						if (@$w_for_remove["id"])
							remove_weapon($w_for_remove["id"], $w_for_remove);
					} else {
						$w_for_remove = SQL::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='orujie' and stype<>'noji' and stype<>'shit'");
						if (@$w_for_remove["id"])
							remove_weapon($w_for_remove["id"], $w_for_remove);
					}
				} elseif ($tmp == 1) {
					$w_for_remove = SQL::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='orujie'");
					if ($v["stype"] <> 'noji' and $v["stype"] <> 'shit' and $w_for_remove["stype"] <> 'noji' and $w_for_remove["stype"] <> 'shit')
						remove_weapon($w_for_remove["id"], $w_for_remove);
				}
			} elseif ($v["type"] == 'kolco') {
				$tmp = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='kolco'");
				if ($tmp['count'] >= 2) {
					$w_for_remove = SQL::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='kolco'");
					if (@$w_for_remove["id"])
						remove_weapon($w_for_remove["id"], $w_for_remove);
				}
			} elseif ($v["type"] == 'kam') {
				$tmp = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='kam'");
				if ($tmp['count'] == 4) {
					$w_for_remove = sql::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='kam'");
					if (@$w_for_remove["id"])
						remove_weapon($w_for_remove["id"], $w_for_remove);
				}
			} else {
				$w_for_remove = SQL::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and type='" . $v["type"] . "'");
				if (@$w_for_remove["id"])
					remove_weapon($w_for_remove["id"], $w_for_remove);
			}
			SQL::q("UPDATE wp SET weared=1 WHERE id=" . $v["id"] . "");
			if ($aq = aq($pers))
				SQL::q("UPDATE `users` SET " . $aq . " WHERE `uid` = " . $pers["uid"] . " ;");
		}
	}
}

function add_flog($txt, $cfight)
{
	global $battle_log;
	if (empty($cfight)) {
		global $pers;
		$cfight = $pers["cfight"];
	}
	if ($txt[strlen($txt) - 1] == '%') $txt = substr($txt, 0, strlen($txt) - 1);
	SQL::q("INSERT INTO `fight_log` ( `time` , `log` , `cfight` , `turn` )
VALUES (
'" . date("H:i") . "', '" . addslashes($txt) . "', '" . $cfight . "', '" . round((time() + microtime()), 2) . "'
);");
	$txt = "<font class=timef>" . date("H:i") . "</font> " . $txt;
	$txt = str_replace("%", "<br><font class=timef>" . date("H:i") . "</font> ", $txt);
	$battle_log .= $txt;
	SQL::q("UPDATE `fights`
SET `all`=CONCAT('" . addslashes($txt) . ";',`all`) , `ltime`='" . time() . "'
WHERE `id`='" . $cfight . "' ;");
}

function signum($x)
{
	if ($x > 0) return 1;
	if ($x == 0) return 0;
	if ($x < 0) return -1;
}

function uncrypt($value)
{
	$a = 0;
	$key = 754;
	for ($i = 0; $i < strlen($value); $i++)
		$a += (ord($value[$i]) << (($i + 23) >> 1) << 1) ^ ($key ^ 9 + $i);
	$a %= 10000;
	$a = abs($a);
	if ($a < 1000) $a += 2343;
	return $a;
}

function plus_param($param)
{
	if ($param > 0) return "+" . $param;
	elseif ($param < 0) return "-" . abs($param);
	else return "0";
}

function all_params()
{
	$r = array();
	for ($i = 1; $i < 7; $i++) $r[] = 's' . $i;
	$r[] = 'kb';
	for ($i = 1; $i < 6; $i++) $r[] = 'mf' . $i;
	$r[] = 'hp';
	$r[] = 'ma';
	$r[] = 'udmin';
	$r[] = 'udmax';
	for ($i = 1; $i < 15; $i++) {
		$r[] = 'sp' . $i;
	}
	for ($i = 1; $i < 15; $i++) {
		$r[] = 'sb' . $i;
	}
	for ($i = 1; $i < 8; $i++) {
		$r[] = 'sm' . $i;
	}
	for ($i = 1; $i < 9; $i++) {
		$r[] = 'a' . $i;
	}
	for ($i = 1; $i < 6; $i++) {
		$r[] = 'm' . $i;
	}
	return $r;
}

function experience($damage, $yourlvl, $vslvl, $notnpc, $rank)
{
	if ($notnpc)
		$koeff = 1.9;
	else
		$koeff = 0.6 * sqrt(sqrt(($rank + 1) / 3));
	if ($yourlvl <= 2)
		$koeff += 1.7;
	if ($yourlvl < 5) $koeff += 0.7;
	if ($notnpc or $yourlvl < 4) $koeff *= sqrt(sqrt($vslvl + 1.1));
	if ($notnpc) {
		if ($yourlvl >= ($vslvl + 3)) $koeff *= 0.2 * (($vslvl + 1) / ($yourlvl + 1));
		if ($yourlvl == ($vslvl + 2)) $koeff *= 0.5;
		if ($yourlvl == ($vslvl + 1)) $koeff *= 0.7;
		if ($yourlvl == ($vslvl))   $koeff *= 1;
		if ($yourlvl == ($vslvl - 1)) $koeff *= 1.4;
		if ($yourlvl == ($vslvl - 2)) $koeff *= 1.8;
		if ($yourlvl == ($vslvl - 3)) $koeff *= 2.6;
		if ($yourlvl < ($vslvl - 3))  $koeff *= 3.0 * (($vslvl + 1) / ($yourlvl + 5));
	} else {
		if ($yourlvl >= ($vslvl + 3)) $koeff *= 0.2 * (($vslvl + 1) / ($yourlvl + 1));
		if ($yourlvl == ($vslvl + 2)) $koeff *= 0.5;
		if ($yourlvl == ($vslvl + 1)) $koeff *= 0.7;
		if ($yourlvl == ($vslvl))   $koeff *= 1;
		if ($yourlvl == ($vslvl - 1)) $koeff *= 1.2;
		if ($yourlvl == ($vslvl - 2)) $koeff *= 1.4;
		if ($yourlvl == ($vslvl - 3)) $koeff *= 1.6;
		if ($yourlvl < ($vslvl - 3))  $koeff *= 2.0 * (($vslvl + 1) / ($yourlvl + 5));
	}
	$koeff *= mtrunc(0.9 + ($vslvl - $yourlvl) * 0.10) + 0.1;
	return floor($damage * $koeff);
}

function transfer_log($type, $uid, $user, $money1, $money2, $title, $ip1, $ip2)
{
	SQL::q("INSERT INTO `transfer` ( `date` , `type` , `uid` , `who` , `transfer_in` , `transfer_out` , `title` , `ip1` , `ip2`)
VALUES (
'" . time() . "', " . $type . " ,'" . $uid . "', '" . $user . "', '" . $money1 . "', '" . $money2 . "', '" . $title . "', '" . $ip1 . "' , '" . $ip2 . "'
);");
}

function ylov($_pers, $_persvs)
{
	$vsR = mtrunc($_persvs["s2"] * ($_persvs["mf2"] / 100 + 1));
	$yoR = mtrunc($_pers["s2"] * ($_pers["mf3"] / 100 + 1));
	$ylov = 50 * mtrunc(1 - $yoR / $vsR) * sqrt($vsR / 4);
	$ylov *= mtrunc($_persvs["level"] - $_pers["level"]) * 0.20 + 1;
	if ($ylov > 70)
		$ylov = 70;
	if ($ylov < 1)
		$ylov = 0;
	return $ylov;
}

function sokr($_pers, $_persvs)
{
	$vsR = mtrunc($_persvs["s3"] * ($_persvs["mf4"] / 100 + 1));
	$yoR = mtrunc($_pers["s3"] * ($_pers["mf1"] / 100 + 1));
	$ylov = 50 * mtrunc(1 - $vsR / $yoR) * sqrt($yoR / 4);
	$ylov *= mtrunc($_pers["level"] - $_persvs["level"]) * 0.20 + 1;
	if ($ylov > 70)
		$ylov = 70;
	if ($ylov < 1)
		$ylov = 0;
	return $ylov;
}

function yar($_pers, $_persvs)
{
	$yar = (3 + $_pers["mf5"] / 5 - $_pers["mf4"] / 20) / 5;
	$yar *= mtrunc($_pers["level"] - $_persvs["level"]) * 0.20 + 1;
	if ($yar < 2)
		$yar = 2;
	if ($yar > 90)
		$yar = 90;
	return $yar;
}

function ydar($_pers, $_persvs)
{
	$ydar = rand($_pers["udmin"] * 10, $_pers["udmax"] * 10 + 10) / 20;
	$ydar = $ydar * sqrt($ydar);
	$ydar *= mtrunc($_pers["level"] - $_persvs["level"]) * 0.20 + 1;
	$kb = mtrunc($_persvs["kb"] + $_persvs["sb11"]);
	$ydar = mtrunc($_pers["sb2"] + $_pers["s1"] + $ydar);
	if ($kb < 1) $kb = 1;
	$ydar = $ydar * (pow(0.89, sqrt($kb)) + 0.1);
	$ydar = mtrunc(rand($ydar - 3, $ydar + 3));
	return floor($ydar);
}

function DecreaseDamage($pers)
{
	$kb = mtrunc($pers["kb"] + $pers["sb11"]);
	if ($kb < 1) $kb = 1;
	return round(100 - (pow(0.9, sqrt($kb)) + 0.1) * 100);
}

function time_echo($l)
{
	$d = 0;
	$h = 0;
	$m = 0;
	$s = 0;
	if ((floor($l / 86400)) <> 0) {
		$d = (floor($l / 86400));
		$l = $l % 86400;
	}
	if ((floor($l / 3600)) <> 0) $h = (floor($l / 3600));
	if ((floor(($l % 3600) / 60)) <> 0) $m = (floor(($l % 3600) / 60));
	if ((($l % 3600) % 60) <> 0) $s = (($l % 3600) % 60);
	if (!$d and !$h and !$m) $r = 'только что';
	if (!$d and !$h and $m % 10 == 1) $r = $m . ' минуту назад';
	if (!$d and !$h and $m % 10 > 1) $r = $m . ' минуты назад';
	if (!$d and !$h and ($m > 4 and $m < 21)) $r = $m . ' минут назад';
	if (!$d and $h % 10 == 1) $r = $h . ' час назад';
	if (!$d and $h % 10 == 2) $r = $h . ' часа назад';
	if (!$d and $h % 10 == 3) $r = $h . ' часа назад';
	if (!$d and $h % 10 == 4) $r = $h . ' часа назад';
	if (!$d and ($h > 4 and $h < 21)) $r = $h . ' часов назад';
	if ($d == 1) $r = 'вчера';
	if ($d == 2) $r = 'позавчера';
	if ($d / 7 < 1 and ($d == 3 or $d == 4)) $r = $d . ' дня назад';
	if ($d / 7 < 1 and $d > 4) $r = $d . ' дней назад';
	if ($d >= 7 and $d < 14) $r = 'неделю назад';
	if (floor($d / 7) < 5 and $d >= 14) $r = floor($d / 7) . ' недели назад';
	if (floor($d / 7) >= 5) $r = floor($d / 7) . ' недель назад';
	return $r;
}

function HIWORD($a)
{
	return $a >> 16;
}
function LOWORD($a)
{
	return ($a << 16) >> 16;
}
function TOHIWORD($a)
{
	return $a << 16;
}

function EqualValueOfSkill($skill)
{
	// Для статов = 1, для мф = 10, для хп = 10, для кб = 10, для маны = 12, для умений = 1, для мирных умений = 5 , для удара  = 3
	if ($skill[0] == 's' and strlen($skill) == 2) return 1;
	if ($skill[0] == 'm' and strlen($skill) == 3) return 10;
	if ($skill == 'kb') return 10;
	if ($skill == 'hp') return 10;
	if ($skill == 'ma') return 12;
	if ($skill[0] == 's' and ($skill[1] == 'b' or $skill[1] == 'm') and strlen($skill) == 3) return 1;
	if ($skill[0] == 's' and $skill[1] == 'p' and strlen($skill) == 3) return 5;
	if ($skill == 'udmin' or $skill == 'udmax') return 3;
	return 0;
}

function IsWearing($v)
{
	// Одеваемая ли это вещь
	if ($v["type"] == 'shlem' or $v["type"] == 'orujie' or $v["type"] == 'kolco' or $v["type"] == 'bronya' or $v["type"] == 'naruchi' or $v["type"] == 'perchatki' or $v["type"] == 'ojerelie' or $v["type"] == 'sapogi' or $v["type"] == 'poyas' or $v["type"] == 'kam') return 1;
	return 0;
}

function _UserByUid($uid = 0)
{
	if ($uid)
		return sql::q1("SELECT user FROM users WHERE uid=" . intval($uid))['user'];
	else
		return false;
}
function _UidByUser($user = '')
{
	if ($user) {
		$user = str_replace("'", "", $user);
		$user = str_replace("\\", "", $user);
		return sql::q1("SELECT uid FROM users WHERE smuser=LOWER('" . $user . "')")['uid'];
	} else
		return false;
}

function Weared_Weapons($uid = 0)
{
	if (!$uid) {
		global $pers;
		$uid = $pers["uid"];
	}
	$array = "SELECT stype,udmin,udmax,kb FROM wp WHERE uidp=" . intval($uid) . " and weared=1 and type='orujie';";
	$_W["noji"] = 0;
	$_W["mech"] = 0;
	$_W["topo"] = 0;
	$_W["drob"] = 0;
	$_W["shit"] = 0;
	foreach (SQL::q($array) as $a) {
		$_W[$a["stype"]] += 1;
		$_W[$a["stype"]]["udmin"] = $a["udmin"];
		$_W[$a["stype"]]["udmax"] = $a["udmax"];
		$_W[$a["stype"]]["kb"] = $a["kb"];
	}
	$_W["OD"] = $_W["noji"] * 1 +
		$_W["mech"] * 2 +
		$_W["topo"] * 3 +
		$_W["drob"] * 4 +
		$_W["shit"] * 1;
	return $_W;
}

function types()
{
	$r = array();
	$r['orujie'] = 'Оружие';
	$r['shlem'] = 'Шлемы';
	$r['ojerelie'] = 'Ожерелья';
	$r['poyas'] = 'Пояса';
	$r['sapogi'] = 'Сапоги';
	$r['naruchi'] = 'Наручи';
	$r['perchatki'] = 'Перчатки';
	$r['kolco'] = 'Кольца';
	$r['bronya'] = 'Брони';
	$r['napad'] = 'Свитки нападения';
	$r['zakl'] = 'Свитки заклинаний';
	$r['teleport'] = 'Свитки телепорта';
	$r['zelie'] = 'Зелья/камни';
	$r['kam'] = 'Зелья восстановления';
	$r['potion'] = 'Снадобье';
	$r['herbal'] = 'Травы';
	$r['fishing'] = 'Рыболовные снасти';
	$r['fish'] = 'Рыба';
	$r['resources'] = 'Ресурсы';
	$r['rune'] = 'Руны';
	$r['insrument'] = 'Инструмент';
	$r['quest'] = 'Квестовая вещь';
	return $r;
}

function type_names($tp)
{
	$r = types();
	return $r[$tp];
}

function stypes()
{
	$r = array();
	$r['orujie'] = 'Оружие';
	$r['shle'] = 'Шлем';
	$r['kylo'] = 'Кулон(ожерелье)';
	$r['poya'] = 'Пояс';
	$r['sapo'] = 'Сапоги';
	$r['naru'] = 'Наручи';
	$r['perc'] = 'Перчатки';
	$r['kolc'] = 'Кольцо';
	$r['bron'] = 'Броня';
	$r['napad'] = 'Разрешение на нападение';
	$r['napadk'] = 'Разрешение на нападение';
	$r['napadt'] = 'Разрешение на нападение';
	$r['zakl'] = 'Заклинание';
	$r['blank'] = 'Документ';
	$r['zelie'] = 'Зелье/камни';
	$r['kam'] = 'Зелье';
	$r['potion'] = 'Снадобье';
	$r['herbal'] = 'Травы';
	$r['fishing'] = 'Рыболовные снасти';
	$r['fish'] = 'Рыба';
	$r['resources'] = 'Ресурсы';
	$r['rune'] = 'Руна';
	$r['book'] = 'Книга';
	$r['drob'] = 'Дробящее';
	$r['instrument'] = 'Инструмент';
	$r['mech'] = 'Меч';
	$r['noji'] = 'Нож';
	$r['quest'] = 'Квестовая вещь';
	$r['shit'] = 'Щит';
	$r['topo'] = 'Топор';
	return $r;
}

function stype_names($tp)
{
	$r = stypes();
	return $r[$tp];
}

function kind_stat($i)
{
	if ($i > 5) return "Добряк";
	elseif ($i > 2) return "Добрый";
	elseif ($i > 0) return "Отзывчивый";
	elseif ($i == 0) return "Нейтрален";
	elseif ($i > -2) return "Хитрый";
	elseif ($i > -5) return "Коварный";
	elseif ($i > -7) return "Алчный";
	else return "Злой";
}

function prof_pers($i)
{
	if ($i == "lesorub") return "Лесоруб";
	elseif ($i == "fishing") return "Рыбак";
	elseif ($i == "none") return "Не обучен ни одной из профессий.";
}

function tournir_fisher($uid, $pos = 0, $priz = '', $fish = '')
{
	$_persf = sql::q1("SELECT user, fish_tournir FROM users WHERE uid=" . $uid . "");
	$posWin = explode("&", $_persf["fish_tournir"]);
	$posWins = explode("-", $posWin[1]);
	if ($posWin[0] == 1 and $pos > 0) {
		$posWin[0] = 0;
		if ($pos == 1) $posWins[0]++;
		if ($pos == 2) $posWins[1]++;
		if ($pos == 3) $posWins[2]++;

		$posWin[2]++;

		sql::q("UPDATE users SET fish_tournir='" . $posWin[0] . "&" . $posWins[0] . "-" . $posWins[1] . "-" . $posWins[2] . "&" . $posWin[2] . "' WHERE uid=" . $uid);
	}
	if ($pos == 0) {
		sql::q("UPDATE users SET fish_tournir='0&" . $posWins[0] . "-" . $posWins[1] . "-" . $posWins[2] . "&" . ($posWin[2] + 1) . "' WHERE uid=" . $uid);
	}
}
function tournirer($i, $tournir = '')
{
	$otbor = sql::q1("SELECT count(uid) as count FROM users WHERE fish_tournir LIKE '1&%' and uid=" . $i . "");
	if ($otbor['count']) return "1";
	else return "0";
}
function proverka_fish($fish, $tpers)
{
	$f = sql::q1("SELECT count(uidp)as count FROM wp WHERE `image`='fish_new/" . $fish . "' and uidp='" . $tpers . "'");
	if ($f['count'] > 0) return "1";
	else return "0";
}
function debag($str)
{
	echo "<pre>";
	var_dump($str);
	echo "</pre>";
}
