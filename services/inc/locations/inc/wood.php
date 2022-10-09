<?
$p = '';
$txt = '';
$ins = sql::q1("SELECT * FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and p_type=3 and durability>0");
$time = time();
if (WEATHER == 3) $pers["sp13"] -= $pers["sp13"] / 10;

if (empty($_GET["take"])) {
	if ($pers["waiter"] < $time) {
		if (!$cell["last_trees_change"]) //если необходимо чтобы появились деревья досрочно, убираем знак `!`
		{
			$count = sql::q1("SELECT COUNT(*) as count FROM trees_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "' and count='0'")['count'];
			if (($count == 3 or $count < 3) and tme() > $cell["last_trees_change"]) {
				sql::q("DELETE FROM trees_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "' LIMIT 3");

				$count = 0;
				while ($count < 3) {
					$count++;
					if ($cell["wood"] == 1) $Wcell = rand(1, 5);
					if ($cell["wood"] == 2) $Wcell = rand(6, 10);
					if ($cell["wood"] == 3) $Wcell = rand(11, 15);
					if ($cell["wood"] == 4) $Wcell = rand(16, 20);
					if ($cell["wood"] == 5) $Wcell = rand(21, 25);
					if ($cell["wood"] == 6) $Wcell = rand(26, 30);
					if ($cell["wood"] == 7) $Wcell = rand(31, 35);
					if ($cell["wood"] == 8) $Wcell = rand(36, 40);
					if ($cell["wood"] == 9) $Wcell = 41;
					$tree = sql::q1("SELECT * FROM trees WHERE id=" . $Wcell);
					$diff = rand(1, 4);
					sql::q("INSERT INTO `trees_cell` 
				( `x_y` , `name` , `image` , `time` , `count` , `difficult` , `lvl`, `tree_exp`, `price`) 
				VALUES 
				( '" . $cell["x"] . "_" . $cell["y"] . "', '" . $tree["name"] . "', '" . $tree["id"] . "',
				'" . ($time - rand(0, 100)) . "', '" . rand(1, 10) . "', '" . $diff . "', " . $tree["lvl"] . ", " . $tree["tree_exp"] . ", " . $tree["price"] . ");");

					$tree_grow = TREE_CHANGE;
					if (WEATHER == 2) $tree_grow /= 2;
					if (WEATHER == 3) $tree_grow *= 2;
					if (WEATHER == 1 and date("m") > 5 and date("m") < 9) $tree_grow *= 3;
					if (WEATHER == 6) $tree_grow /= 3;

					sql::q("UPDATE nature SET last_trees_change=" . ($time + $tree_grow) . " WHERE x=" . $cell["x"] . " and y=" . $cell["y"] . "");
				}
			}
		}
	}
	if ($pers["waiter"] == $time) 	echo "<script>location='main.php';</script>";
	$trees = sql::q("SELECT * FROM trees_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "' and count>0");
	//$txt.=tp($cell["last_trees_change"]-time())."<br>";
	$count_tr = 0;
	$txt .= '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#E5E5E5><tr>';
	foreach ($trees as $tree) {
		if ($count_tr < TREE_COUNT) {
			$chance = mtrunc(floor(61 - $tree["price"] * 2 + ($pers["sp13"] - $tree["tree_exp"]) / 20 + $tree["difficult"] * 5));
			if ($chance > 98) $chance = 98;
			if ($chance < 1) $chance = 1;
			$tre[$count_tr] = $tree["image"];
			$count_tr++;
			$txt .= "<td align=center width=100>";
			$txt .= "<font class=user>" . $tree["name"] . "</font>[ <font class=green>" . $tree["count"] . " ШТ.</font> ]<hr noshade>";
			if ($tree["difficult"] == 1) $txt .= "<center class=user>Молодое дерево</center>";
			if ($tree["difficult"] == 2) $txt .= "<center class=user>Зрелое дерево</center>";
			if ($tree["difficult"] == 3) $txt .= "<center class=user>Старое дерево</center>";
			if ($tree["difficult"] == 4) $txt .= "<center class=user>Дряхлое дерево</center>";
			$txt .= "<br>";
			$txt .= "<center><img src=images/weapons/trees/" . $tree["image"] . ".gif width=100></center><br>";
			if ($ins) {
				if ($tree["count"] > 0) {
					// обработка кнопки
					if ($pers["prof_osn"] <> "lesorub" and $tree["lvl"] > 0)
						$txt .= "<table width=60%><tr><td class=but><center id='Butr" . $count_tr . "' style=\"display:none;cursor:pointer;\">Нет профессии</center></td></tr></table>";
					elseif ($pers["prof_osn"] == "lesorub" and $pers["prof_osnLVL"] <= $tree["lvl"])
						$txt .= "<table width=60%><tr><td class=but><center id='Butr" . $count_tr . "' style=\"display:none;cursor:pointer;\">Нет навыка</center></td></tr></table>";
					elseif ($pers["prof_osn"] <> "lesorub" and $tree["lvl"] == 0) {
						$txt .= "<table width=60%><tr><td class=but><a href=main.php?wood=on&take=" . $tree["time"] . " id='Butr" . $count_tr . "' style=\"display:none;cursor:pointer;\" class=bga>Срубить " . $tree["name"] . " <br><i class=timef>" . $chance . "% шанс</i></a></td></tr></table>";
					} else
						$txt .= "<center><table width=60%><tr><td><a href=main.php?wood=on&take=" . $tree["time"] . " id='Butr" . $count_tr . "' style=\"display:none;cursor:pointer;\" class=bga>Срубить " . $tree["name"] . " <br><i class=timef>" . $chance . "% шанс</i></a></td></tr></table></center>";
					/*$txt .= "<center><table width=60%><tr><td><a href=".$rubim." id='Butr".$count_tr."' style=\"display:none;cursor:pointer;\" class=bga>".$knopka."</a></td></tr></table></center>";
				*/
				} else
					$txt .= "<a href='' id='Butr" . $count_tr . "' style=\"display:none;cursor:pointer;\" class=bga>СРУБЛЕНО</a>";
			} else
				$txt .= "<center class=but>Нет инструмента</center>";
			$txt .= "<script> function change()
									{document.getElementById(\"Butr1\").style.display=\"block\";
									document.getElementById(\"Butr2\").style.display=\"block\";
									document.getElementById(\"Butr3\").style.display=\"block\";}
									setTimeout(\"change()\"," . (TLOOK_TIME * 1000) . ");</script>";
			$txt .= "</td>";
		}
	}
	if ($count_tr == 0) $txt .= '<td align=center>Здесь нет деревьев...<br>Вам придётся подождать пока они подрастут.</td>';
	$txt .= '</tr></table>';
	set_vars("waiter=" . ($time + TLOOK_TIME) . ",action=1", UID);
	$pers["waiter"] = ($time + TLOOK_TIME);
} elseif ($pers["action"] == 1) {
	$tree = sql::q1("SELECT * FROM trees_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "' and time=" . intval($_GET["take"]) . " and count>0");
	$rub = round(TRUB_TIME * ($tree["lvl"] + $tree["difficult"]) - (($pers["sp13"] / ((TRUB_TIME) * ($tree["lvl"] + $tree["difficult"]))) * 5));
	if ($rub < 30) $rub = 30;

	if ($tree) {
		$chance = floor(61 - $tree["price"] * 2 + ($pers["sp13"] - $tree["tree_exp"]) / 20 + $tree["difficult"] * 5);
		$skill_plus = 0;
		if ($chance > 98) $chance = 98;
		if ($chance < 1) $chance = 1;
		if ($chance > rand(0, 100)) {
			$skill_plus = round((10 / (1 + $pers["sp13"])) * $tree["difficult"], 3);
			if (($pers["sp13"] + $skill_plus) >= 1290) $skill_plus = 0;
			$txt .= "<div><b class=green>Удачно срублено <b class=user>\"" . $tree["name"] . "\"</b>!</b></div>";
			$txt .= "<i>Шанс удачной срубки был: <b>" . $chance . "%</b></i><br>";
			$txt .= "Мирный опыт <b>+5</b> | ";
			$txt .= "Дровосек <b>+" . $skill_plus . "</b><br>";
			$txt .= "Стоимость сруба <b>" . ($tree["price"] * $tree["difficult"]) . " LN</b><br>";
			$txt .= "Долговечность \"<b>" . $ins["name"] . "</b>\" <b>-" . $tree["difficult"] . "</b>.<br>";
			sql::q("INSERT INTO `wp` 
				( `id` , `uidp` , `user` ,`weared` ,`id_in_w`, `price` , `dprice` , `image` 
				, `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` 
				, `max_durability` , `durability` ,`p_type`) 
				VALUES 
				(0, '" . $pers["uid"] . "', '" . $pers["user"] . "', '0','res..tree" . $tree["image"] . "', '" . ($tree["price"] * $tree["difficult"]) . "', '0', 'trees/" . $tree["image"] . "', '0', 'trees', 'resources',
 				'" . $tree["name"] . "', '', '" . $tree["difficult"] . "', '0', '1', '1','7');");
			sql::q("UPDATE trees_cell SET count=count-1 WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "' and time=" . intval($_GET["take"]) . " and count>0 LIMIT 1;");
			say_to_chat('d', ' ' . $tree["name"] . '.', 1, $pers["user"], '*', 0);
		} else {
			$txt .= "<div><b class=hp>Неудачная попытка сруба <b class=user>\"" . $tree["name"] . "\"</b>.</b></div>";
			$txt .= "<i>Шанс удачной срубки был: <b>" . $chance . "%</b></i><br>";
			$txt .= "Долговечность \"<b>" . $ins["name"] . "</b>\"  <b>-" . $tree["difficult"] . "</b>.<br>";
			$rub = 30;
		}

		set_vars("waiter=" . ($time + $rub) . ",tire=tire+5,sp13=sp13+" . round($skill_plus, 3) . ", peace_exp=peace_exp+5,action=0", UID);
		$dur_out = $tree["difficult"];
		if ($dur_out > $ins["durability"]) $dur_out = $ins["durability"];
		$ins["durability"] -= $dur_out;
		sql::q("UPDATE wp SET durability=durability-" . $dur_out . " WHERE id=" . $ins["id"] . " LIMIT 1;");
		$pers["waiter"] = ($time + $rub);
		if (rand(1, 100) < 40) {
			say_to_chat('s', 'Вы потревожили существ!', 1, $pers["user"], '*', 0);
			$bb = '';
			for ($i = 1; $i <= rand(1, 7); $i++) {
				$lesbot = rand(1, 30);
				// на сервере нужно делать ботов от 1 лвл и выше.
				if ($lesbot >= 1 and $lesbot <= 10) $bbot = 300;
				elseif ($lesbot >= 11 and $lesbot <= 20) $bbot = 2300;
				elseif ($lesbot >= 21 and $lesbot <= 30) $bbot = 2400;
				$bb .= "bot=" . ($bbot + rand($pers["level"] - 6, $pers["level"] + 2)) . "|";
			}
			$bb = substr($bb, 0, strlen($bb) - 1);
			begin_fight($pers["user"], $bb, "Нападение существ на лесоруба", 100, 300, 1);
		}
	} else {
		$txt .= "Вас кто-то опередил.";
	}
}
if ($ins) {
	if ($ins["durability"] > 0)
		$txt .= "<hr><center class=weapons_box><font color=blue><b>Выбран </font>:<i>" . $ins["name"] . "</i>. <font color=red>Долговечность</font>:<i>(" . $ins["durability"] . "/" . $ins["max_durability"] . ")</b></i></center>";
	else
		$txt .= "<hr><center class=weapons_box><font color=blue><b>Ваш </font><i>" . $ins["name"] . "</i> <font color=red>сломался</font></b></center>";
}
$_WOOD_RESPONSE = $txt;
unset($txt);
