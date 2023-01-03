<?

if (!$_persvs["id"]) $_persvs  =  $persvs;

if ($_persvs["bid"] > 0 and mtrunc($_persvs["level"] - $_pers["level"] + 8) and $fight["travm"] > 0) {
	if ($_pers["sp10"] < 1000) {
		$_pers["sp10"] += 10 / (mtrunc(($_pers["sp10"] / 10 + 1) * ($_pers["sp10"] / 10 + 1)) + 1);
		set_vars("sp10=" . $_pers["sp10"], $_pers["uid"]);
	}

	if ($_pers["pol"] == "male") $ob = "обыскал";
	else $ob = "обыскала";
	if ($_pers["pol"] == "male") $ra = "разделал";
	else $ra = "разделала";

	if (rand(1, 100) < ($_pers["sp10"] / 5 + $_pers["sp4"] / 5)) {
		if (rand(1, 100) < $_persvs["dropfrequency"] and $_persvs["droptype"] <> 0) {
			if ($_persvs["droptype"] == 1) {
				$r = floor($_persvs["dropvalue"] + $_persvs["level"] / 2);
				SQL::q("UPDATE `users` SET `money`=money+" . $r . " WHERE `uid`='" . $_pers["uid"] . "'");
				if ($_pers["sp4"] < 1000) {
					$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
					set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
				}
				$res = "Обнаружено " . $r . " LN!";
			} elseif ($_persvs["droptype"] == 2) {
				$r = 1;
				SQL::q("UPDATE `users` SET `coins`=coins+" . $r . " WHERE `uid`='" . $_pers["uid"] . "'");
				if ($_pers["sp4"] < 1000) {
					$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
					set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
				}
				$res = "Обнаружено " . $r . " пергамент!";
			} elseif ($_persvs["droptype"] == 3) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"]) {
					insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				}
				$res = "Обнаружено «" . $v["name"] . "» !";
			} elseif ($_persvs["droptype"] == 4) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"]) {
					$res = "Обнаружено «" . $v["name"] . " [Срок действия 1 день]» !";
					$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					sql::q1("UPDATE wp SET timeout=" . (tme() + 3600 * 24) . " WHERE id=" . $id);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				}
				$res = "Обнаружено «" . $v["name"] . "» !";
			} elseif ($_persvs["droptype"] == 5) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"]) {
					$res = "Обнаружено «" . $v["name"] . " [Срок действия 3 дня]» !";
					$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					sql::q("UPDATE wp SET timeout=" . (tme() + 3600 * 72) . " WHERE id=" . $id);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				}
			} elseif ($_persvs["droptype"] == 6) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"]) {
					$res = "Обнаружено «" . $v["name"] . " [Срок действия 7 дней]» !";
					$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					sql::q("UPDATE wp SET timeout=" . (tme() + 3600 * 168) . " WHERE id=" . $id);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				} else $res = " а вот и косяк";
			} elseif ($_persvs["droptype"] == 7) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"]) {
					$res = "Обнаружено «" . $v["name"] . " [Срок действия 1 месяц]» !";
					$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					sql::q("UPDATE wp SET timeout=" . (tme() + 3600 * 720) . " WHERE id=" . $id);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				}
			} elseif ($_persvs["droptype"] == 8) {
				$v = sql::q1("SELECT name,id FROM weapons WHERE id=" . $_persvs["dropvalue"] . "");
				if (@$v["id"] && $_pers["quest1"] == 1 && $_pers["n_quest1"] < '10') {
					$res = "Обнаружено «<i>" . $v["name"] . " </i>[Срок действия 1 месяц]» !";
					$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
					sql::q("UPDATE wp SET timeout=" . (tme() + 3600 * 720) . " WHERE id=" . $id);
					sql::q("UPDATE users SET n_quest1=n_quest1+1 WHERE uid=" . $_pers["uid"]);
					if ($_pers["sp4"] < 1000) {
						$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
						set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
					}
				} else $res = "Ничего не найдено.";
			}
		} else $res = "Ничего не найдено.";
		###### старая функция дропа
		if (($_persvs["level"] / 12 + $_pers["sp10"] / 400) > rand(1, 200)) ############### выбивание вещей одетых на ботах
		{
			$v = sql::q1("SELECT id,name FROM wp WHERE uidp=" . (-1 * $_persvs["bid"]) . " ORDER BY RAND()");
			if ($v["id"] == true) {
				$res = "Обнаружено «" . $v["name"] . "» !";
				$id = insert_wp_new($_pers["uid"], "id=" . $v["id"], $_pers["user"]);
				sql::q("UPDATE wp SET where_buy=0 WHERE id=" . $id["id"] . "");
				if ($_pers["sp4"] < 1000) {
					$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
					set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
				}
			} else $res = "Ничего у него при себе не найдено.";
		} elseif (($_persvs["level"] / 6 + $_pers["sp10"] / 100) > rand(1, 300)) ############### выбивание вещей из ботов (любых)
		{
			$v = sql::q1("SELECT id,name FROM weapons WHERE where_buy=0 and price>200 and price<2000 and dprice=0 ORDER BY RAND() LIMIT 1;");
			if ($v["id"]) {
				$res = "Обнаружено «" . $v["name"] . "» !";
				$id = insert_wp($v["id"], $_pers["uid"], -1, 0, $_pers["user"]);
				sql::q("UPDATE wp SET where_buy=0 WHERE id=" . $id . "");
				if ($_pers["sp4"] < 1000) {
					$_pers["sp4"] += 10 / (mtrunc(($_pers["sp4"] / 10 + 1) * ($_pers["sp4"] / 10 + 1)) + 1);
					set_vars("sp4=" . $_pers["sp4"], $_pers["uid"]);
				}
			} else $res = "Ничего у него при себе не найдено.";
		}
	} else $res = "Ничего не найдено.";

	$str = " <font class=bnick color=" . $colors[$_pers["fteam"]] . ">" . $_pers["user"] . "</font> " . $ob . " существо. Результаты: <b>" . $res . "</b>%";
	say_to_chat('s', 'Вы обыскали <b>' . $_persvs["user"] . '</b> [' . $_persvs["level"] . ']. Результаты: <b>' . $res . '</b>', 1, $_pers["user"], '*', 0);
	############### выделка кожи
	if ($_persvs["id_skin"]) {
		$INS = sql::q1("SELECT * FROM wp WHERE uidp=" . $_pers["uid"] . " and weared=1 and p_type=14");
		if ($INS["id"]) {
			$SK = sql::q1("SELECT * FROM skins WHERE id=" . $_persvs["id_skin"]);
			$chance = 30 + $_pers["sp14"] / ($SK["price"] + 10);
			$SKILL_UP = 0;
			if ($chance > rand(0, 100)) {
				$res = "<b class=green>«" . $SK["name"] . "»</b>";
				sql::q("INSERT INTO `wp` ( `id` , `uidp` , `user` ,	`weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` ,`p_type`) VALUES (0, '" . $_pers["uid"] . "', '" . $_pers["user"] . "', '0','res..skin" . $SK["id"] . "','" . $SK["price"] . "', '0', 'skin/skin" . $SK["id"] . "', '0', 'skin', 'resources', '" . $SK["name"] . "', '', '1', '0', '1', '1','7');");
			} else {
				$res = "<b class=hp>Неудачная разделка</b>";
				$SKILL_UP = round(20 / (mtrunc($_pers["sp14"]) + 1), 3);
			}

			sql::q("UPDATE wp SET durability=durability-1 WHERE id=" . $INS["id"]);
			set_vars("sp14=sp14+" . $SKILL_UP, $_pers["uid"]);

			$str .= " <font class=bnick color=" . $colors[$_pers["fteam"]] . ">" . $_pers["user"] . "</font> " . $ra . " существо. Результаты: " . $res . "%";
			say_to_chat('s', 'Вы разделали <b>' . $_persvs["user"] . '</b> [' . $_persvs["level"] . ']. Результаты: ' . $res . '; Выделка Кожи <b>+' . $SKILL_UP . '</b>; <b>' . $INS["name"] . "</b> -1 долговечность; Шанс " . $chance . "% .", 1, $_pers["user"], '*', 0);
		}
	}
} else
	$str = " ";
