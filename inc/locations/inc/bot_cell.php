<?
$TXT = '';
if ($cell["last_bots_change"] < (time() - 7200)) {
	if ($cell["blvlmax"] > rand(0, 100)) {
		$w = SQL::q1("SELECT COUNT(id) as count FROM `bots_cell` WHERE xy='" . $cell["x"] . "_" . $cell["y"] . "'");
		if ($w['count'] > 3)
			SQL::q1("DELETE FROM bots_cell WHERE xy='" . $cell["x"] . "_" . $cell["y"] . "' LIMIT 1;");
		$q = '';
		if ($cell["type"] == 6) $q = 'where_ap = 1';
		elseif ($cell["type"] == 3) $q = 'where_ap = 2';
		else $q = 'where_ap = 3';
		$bot_id = SQL::q1("SELECT id,user,id_skin FROM bots WHERE " . $q . " and id_skin>0 ORDER BY RAND()");
		if ($bot_id) {
			$count = rand(1, 1);
			SQL::q("INSERT INTO `bots_cell` ( `id` , `name` , `time` , `xy` , `count` , `id_skin`) 	VALUES ('" . $bot_id["id"] . "', '" . $bot_id["user"] . "', '" . time() . "', '" . $cell["x"] . "_" . $cell["y"] . "'," . $count . "," . $bot_id["id_skin"] . ");");
			SQL::q("UPDATE nature SET last_bots_change=" . time() . " WHERE x='" . $cell["x"] . "' and y='" . $cell["y"] . "'");
		}
	} elseif ($cell["bot"] <> "Тип ботов" and rand(0, 100) < 50) {
		$user = SQL::q1("SELECT user FROM bots WHERE id='" . $cell["bot"] . "'");
		$bot_id = SQL::q1("SELECT id,user,level FROM bots WHERE user='" . $user["user"] . "' and level='" . rand($cell["blvlmin"], $cell["blvlmax"]) . "'");
		if ($bot_id) {
			$count = rand(1, 1);
			SQL::q1("INSERT INTO `bots_cell` ( `id` , `name` , `time` , `xy` , `count`,`id_skin`) VALUES ('" . $bot_id["id"] . "', '<font class=user>" . $bot_id["user"] . "</font>[<font class=lvl>" . $bot_id["level"] . "</font>]<img src=images/info.gif onclick=\"javascript:window.open(\'binfo.php?" . $bot_id["id"] . "\',\'_blank\')\" style=\"cursor:pointer\">', '" . time() . "', '" . $cell["x"] . "_" . $cell["y"] . "'," . $count . ",0);");
		}
		SQL::q1("UPDATE nature SET last_bots_change=" . time() . " WHERE x='" . $cell["x"] . "' and y='" . $cell["y"] . "'");
	}
}
$TXT .= "Живность на локации: <br>";
$TXT .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" class=LinedTable>';
$bots = SQL::q("SELECT * FROM bots_cell WHERE xy='" . $cell["x"] . "_" . $cell["y"] . "'");
// $TXT .= debag($bots);
$BCNT = 0;
foreach ($bots as $b) {
	// $TXT .= debag($b['bid']);
	$BCNT++;
	$TXT .= "<tr>";
	if ($b["id_skin"]) $b["name"] = "<font class=user>" . $b["name"] . "</font>[<font class=lvl>" . ($pers["level"]) . "</font>]";
	if ($b["time"] <= time()) {
		$TXT .= "<td><center><input type=image class=login onclick=\"{if(confirm('Вы действительно хотите напасть?')) location='main.php?out_action=battle&bid=" . $b["bid"] . "'}\" src='images/rp_logo.png'></center></td>";
		$TXT .= "<td class='user' nowrap>" . $b["name"] . "</td>";
		// удаляет всех ботов одного уровня
		if (@$_GET["out_action"] == "battle" and $_GET["bid"] == $b["bid"]) {
			$f_type = 0;
			if ($cell["type"] == 0) $f_type = 1;
			if ($cell["type"] == 1) $f_type = 1;
			if ($cell["type"] == 2) $f_type = 4;
			if ($cell["type"] == 6) $f_type = 5;
			if ($cell["type"] == 5) $f_type = 0;
			if ($cell["type"] == 8) $f_type = 2;
			if ($cell["type"] == 3) $f_type = 3;
			//$f_type = 0;
			$bb = '';
			if ($b["id_skin"]) {
				for ($i = 1; $i <= $b["count"]; $i++) $bb .= "bot=" . (floor($b["bid"] / 100) * 100 + $pers["level"] - 1) . "|";
				$bb = substr($bb, 0, strlen($bb) - 1);
				begin_fight($pers["user"], $bb, "Охота на существо", 50, 300, 1, $f_type);
				echo "<script>location='main.php';</script>";
			} else {
				for ($i = 1; $i <= $b["count"]; $i++) $bb .= "bot=" . $b["bid"] . "|";
				$bb = substr($bb, 0, strlen($bb) - 1);
				begin_fight($pers["user"], $bb, "Охота на существо", 50, 300, 1, $f_type);

				echo "<script>location='main.php';</script>";
			}
			SQL::q1("DELETE FROM bots_cell WHERE xy='" . $cell["x"] . "_" . $cell["y"] . "' and id='" . $b["id"] . "' and time<" . time() . " LIMIT 1;");
		}
	} else $TXT .= "<td></td>";
	$TXT .= "</tr>";
}
$TXT .= "</table>";
if ($BCNT == 0)
	$TXT .= '<i class=gray>Не обнаружено...</i>';
