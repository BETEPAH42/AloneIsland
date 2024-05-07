<?php
header("Cache-Control: no-cache, must-revalidate");
// header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// header("Content-type: text/html; charset=utf-8");
$opt = explode("|", $_COOKIE["options"]);
require_once 'classes/autoload.php';
include_once 'inc/functions.php';

if (@$_GET["sort"]) {
	if ($_GET["sort"] == '2') $opt[2] = "0+";
	if ($_GET["sort"] == '1') $opt[2] = "+0";
	if ($_GET["sort"] == 'z') $opt[2] = 'z';
	if ($_GET["sort"] == 'a') $opt[2] = 'a';
	setcookie("options", implode("|", $opt), time() + 20000);
}

if ($opt[2] == "0+") $_GET["sort"] = '2';
if ($opt[2] == "+0") $_GET["sort"] = '1';
if ($opt[2] == "z") $_GET["sort"] = 'z';

?>
<script src='js/newch_list.js?2'></script>
	<?php
	$pers = SQL::q1("SELECT `x`,`y`,`location`,`block`,`pass`,`rank`,
	`sign`,`user`,`uid`,`diler`,`priveleged`,`level` FROM `users` WHERE `uid` = '" . (int)$_COOKIE["uid"] . "'");

	if ($pers["block"] or $pers["pass"] <> $_COOKIE["hashcode"]) die("Exit : 0");
	$place = $pers["location"];
	if (@$_GET["ignore"]) {
		SQL::q("INSERT INTO `ignor` ( `uid` , `nick` ) VALUES (" . $pers["uid"] . ", '" . trim(str_replace("'", "", $_GET["ignore"])) . "');");
	}
	if (@$_GET["ignore_unset"]) {
		sql::q("DELETE FROM ignor WHERE uid=" . $pers["uid"] . " and nick='" . trim(str_replace("'", "", $_GET["ignore_unset"])) . "'");
	}
	if (@$_GET["no_tip"]) {
		sql::q($resDB, "INSERT INTO `no_tips` ( `uid` , `tip_id` ) VALUES (" . $pers["uid"] . ", " . intval($_GET["no_tip"]) . ");");
	}
	$t = time();
	$t1 = time() - 360 + microtime();
	SQL::q("UPDATE `users` SET `online`='0', timeonline = timeonline+lastom-lastvisits, gain_time=0 WHERE `lasto`<{$t1} and `lastom`<{$t1} and online=1;");
	$vsego = SQL::q1("SELECT COUNT(uid) as count FROM `users` WHERE `online`='1'");
	$_max = SQL::q1("SELECT max_online,time_max_online FROM `configs` LIMIT 0,1");
	$max = $_max["max_online"];
	$tmax = $_max["time_max_online"];

	if ($t % 60 == 0) {
		if ($max < $vsego['count'])
			SQL::q("UPDATE configs SET max_online=" . $vsego['count'] . ",time_max_online=" . $t . "");
		SQL::q("UPDATE `users` SET `online` = '0',timeonline=timeonline+lastom-lastvisits,gain_time=0 WHERE `lasto` < " . $t1 . " and `lastom` < " . $t1 . " and online=1;");
	}

	if ($place <> 'out')
		$locname = SQL::q1("SELECT name FROM `locations` WHERE `id`='" . $place . "' ;");
	else
		$locname = SQL::q1("SELECT name FROM `nature` WHERE `x`='" . $pers["x"] . "' and `y`='" . $pers["y"] . "' ;");
	echo "<script>";
	echo "let locname = '" . $locname['name'] . "';";
	echo "let xy='" . $pers["x"] . " : " . $pers["y"] . "';";

	if ($pers["level"])
		echo "let vsg=" . $vsego['count'] . ";";
	else
		echo "let vsg=0;";

	if (substr_count($pers["rank"], "<molch>") or $pers["diler"] == '1' or $pers["priveleged"] or 1)
		echo "let priveleged=1;";
	else
		echo "let priveleged=0;";
		echo "</script>";
	if ($place == 'arena') $dQ = 'or sign=\'c2\'';

	if (empty($_GET["view"]) or $_GET["view"] == "this") {
		if ($place <> 'out')
			$res = SQL::q("SELECT sign,user,level,state,diler,clan_name,uid,priveleged,silence,invisible,clan_state FROM `users` WHERE `online`=1 and (`location`='" . $place . "' " . $dQ . ");");
		else
			$res = SQL::q("SELECT sign,user,level,state,diler,clan_name,uid,priveleged,silence,invisible,clan_state FROM `users` WHERE `online`=1 and `location`='out' and x=" . $pers["x"] . " and y=" . $pers["y"]);
	} else
		$res = SQL::q("SELECT sign, user, level, state, diler, clan_name, uid, priveleged, silence, invisible, clan_state FROM `users` WHERE `online` = 1;");
	$i = 0;
	$s = '';
	$tyt = 0;

	$r = [];
	if ($place <> 'out')
		$rsds = SQL::q("SELECT * FROM residents WHERE location=? AND online=1;",[$place]);
	else
		$rsds = SQL::q("SELECT * FROM residents WHERE x=" . $pers["x"] . " AND y=" . $pers["y"] . " AND location='out' AND online=1;");
	if (count($rsds) > 0) {
		foreach ($rsds as $rs) {
			$b = SQL::q1("SELECT level FROM bots WHERE id=" . $rs["id_bot"])['level'] ?? 0;
			$r [] = "'".$rs["name"] . "|" . $b . "|" . $rs["id"] . "|" . $rs["id_bot"]."'";
			$tyt++;
		}
	}
	$ignore = '';
	$ign  = SQL::q("SELECT nick FROM ignor WHERE uid=" . $pers["uid"] . "");
	foreach ($ign as $ig)
		$ignore .= $ig["nick"] . '|';

	foreach ($res as $row) {
		$tyt++;
		$i++;
		$tr = '';
		$trs = SQL::q("SELECT special FROM p_auras WHERE uid=" . $row["uid"] . " and special>2 and special<6 and esttime>" . time());
		foreach ($trs as $ttt) {
			if ($ttt["special"] == 3) $tr .= "Легкая травма.";
			if ($ttt["special"] == 4) $tr .= "Средняя травма.";
			if ($ttt["special"] == 5) $tr .= "Тяжёлая травма.";
		}
		unset($clan);
		if ($row["sign"] <> 'none' and $row["sign"] <> '') {
			if (!$pers["clan_name"])
				$clan = SQL::q1("SELECT name,level FROM clans WHERE sign='" . $row["sign"] . "'");
			$row["clan_name"] = $clan["name"];
			SQL::q("UPDATE users SET clan_name='" . $row["clan_name"] . "' WHERE uid=" . $row["uid"] . "");
		}
		$row["state"] = $row["clan_name"] . "[" . $clan["level"] . "] " . _StateByIndex($row["clan_state"]) . "[" . str_replace("|", "!", $row["state"]) . "]";
		if ($row["invisible"] <= time() or $row["user"] == $pers["user"] or $pers["sign"] == 'c2' or substr_count($pers["rank"], "<pv>"))	$inv = 1;
		else $inv = 0;
		if ($row["priveleged"])
			$prv = SQL::q1("SELECT status FROM `priveleges` WHERE `uid`=" . $row["uid"]);
		else $prv['status'] = '';
		if ($row["user"] == 'BETEPAH')
			$prv['status'] = 'Дизайнер';
		if ($inv) {
			$row["state"] = str_replace("'", "&quot;", $row["state"]);
			if ($row["invisible"] > time()) $row["user"] = "n=" . $row["user"];
			$s .= $row["user"] . "|" . $row["level"] . "|" . $row["sign"] . "|" . $row["state"] . "|";
			if ($row["silence"] > time()) $s .= ($row["silence"] - time()) . "|";
			else $s .= "|";
			$s .= $tr . "|";
			$s .= $row["diler"] . "|";
			if (substr_count("|" . $ignore, "|" . $row["user"] . "|")) $s .= ".|";
			else $s .= "|";
			$s .= $prv['status'] . "|";
			$s .= "'";
		}
		if ($inv) $s .= ",";
	}
	echo "<script>";
	echo "let list = new Array('";
	echo substr($s, 0, strlen($s) - 2);
	echo "');";
	echo "var residents = new Array(";
	echo implode(",",$r);
	echo "); ";
	echo "var zds=" . $tyt . "; show_head();";
	echo "show_list('" . intval($_GET["sort"]) . "','" . $_GET["view"] . "');";

	if (substr_count($pers["rank"], "<molch>") or $pers["diler"] == '1' or $pers["priveleged"])
		echo "var molch=1;";
	else
		echo "var molch=0;";
	?>
</script>