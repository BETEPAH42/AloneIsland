<div id=inf_from_php style='margin-top:25px;padding: 0 15px;'>
	<table border='0' class='lightblock' width='100%'>
		<?
		// $c_on_pers = SQL::q("SELECT * FROM `wp` WHERE uidp='".$pers["uid"]."' and weared=1;");
		// $sostav = SQL::q("SELECT * FROM wp ORDER by ID DESC");
		// $_NOM=0;
		// while ($sost = SQL::q("SELECT * FROM wp ORDER by ID DESC"))
		// {
		// 	if ($sost["uidp"]==$pers["uid"] and $sost["id_in_w"]=='14547') 
		// 	{
		// 		$_NOM++;
		// 	}
		// echo $_NOM;
		// }

		// $status = $pers["clan_state"];
		$all_weight = SQL::q1("SELECT SUM(weight) as w, COUNT(*) as c FROM `wp` WHERE uidp=" . $pers["uid"] . " and in_bank=0");
		$all_wp = $all_weight["c"];
		$all_weight = $all_weight["w"];
		$pw = abs(10 + ($pers["sm3"] + $pers["s4"]) * 10);
		if (intval($all_weight) <> $pers["weight_of_w"])
			set_vars("weight_of_w=" . intval($all_weight), UID);
		$pers["weight_of_w"] = intval($all_weight);
		echo "<center>Масса вещей: [" . ($pers["weight_of_w"]) . "/" . abs(10 + ($pers["sm3"] + $pers["s4"]) * 10) . "] из " . $all_wp . " ваших вещей.</center>";
		if (abs(10 + ($pers["sm3"] + $pers["s4"]) * 10) < ($all_weight))
			echo "<br><b class=hp>Вы перегружены!</b>";


		if (@$_GET["zzz"]) {

			$zz = SQL::q("SELECT * FROM wp WHERE uidp='" . $pers["uid"] . "' and id='" . addslashes($_GET["zzz"]) . "' AND `auction` <> '1'");

			set_vars("money=money-" . (($zz["arrows_max"] - $zz["arrows"]) * $zz["arrow_price"]) . "", $pers["uid"]);

			SQL::q("UPDATE wp SET arrows=arrows_max WHERE uidp='" . $pers["uid"] . "' and id='" . addslashes($_GET["zzz"]) . "'");
		}

		if ($pers["sign"] <> 'none')

			$clan = SQL::q("SELECT * FROM `clans` WHERE `sign`='" . $pers['sign'] . "'");

		?>
		<tr>
			<td>
				<a href="javascript:inv_conf()" class=Blocked><img src='images/icons/0_on.png'></a>
			</Td>
			<?
			if ($weared_count) echo "<td><a href=main.php?snall=all class=Blocked>Снять всё</a></td>";

			?>
			<td>
				<a href=main.php?filter_f4=all class=Blocked>Вещи</a>
			</Td>
			<Td>
				<a href=main.php?inv=magic class=Blocked>Магия</a>
			</Td>
			<Td>
				<a href=main.php?inv=presents class=Blocked>Подарки</a>
			</Td>
			<Td>
				<a href=main.php?inv=cat3 class=Blocked>Комплекты</a>
			</td>
			<?

			if ($pers["alchemy_d"] > 0 and $pers["alchemy_b"] > 0 and $pers["alchemy_m"] > 0) echo "<td><a href=main.php?inv=cat5 class=Blocked>Алхимия</a></td>";
			elseif ($pers["alchemy_d"] == 0)
				echo "<td><a class=bg onclick=\"alert('Кончилась долговечность дистиллятора.')\">Алхимия</a></td>";
			elseif ($pers["alchemy_b"] == 0)
				echo "<td><a class=bg onclick=\"alert('Кончились пустые ёмкости.')\">Алхимия</a></td>";
			elseif ($pers["alchemy_m"] == 0)
				echo "<td><a class=bg onclick=\"alert('Кончилась долговечность ступки.')\">Алхимия</a></td>";

			?>
		</tr>
	</table>
	<table border='0' class='lightblock' width='100%'>
		<tr>
			<Td>
				<a href=main.php?filter_f4=blank class=Blocked>Документы</a>
			</Td>
			<Td>
				<a href=main.php?filter_f4=quest class=Blocked>Квестовые вещи</a>
			</Td>
		</tr>
	</table>
	<div id=container1 class=but></div>
	<?
	$r = types();
	$types = '';
	foreach ($r as $key => $value) {
		$types .= $key . '=' . $value . "|";
	}
	?>

	<script>
		var _type = '<?= $_FILTER["sorti"]; ?>';
		var _group = '<?= $_FILTER["filter_f6"]; ?>';
		var _sort = '<?= $_FILTER["sortp"]; ?>';
		var __types = '<?= $types; ?>';
		var _herbal = <?= SQL::qCount("SELECT COUNT(*) FROM wp WHERE uidp=" . $pers["uid"] . " and type='herbal'"); ?>;
		var _ruda = <?= SQL::qCount("SELECT COUNT(*) FROM wp WHERE uidp=" . $pers["uid"] . " and type='ruda'"); ?>;
		var _trees = <?= SQL::qCount("SELECT COUNT(*) FROM wp WHERE uidp=" . $pers["uid"] . " and type='trees'"); ?>;
		var _fish = <?= SQL::qCount("SELECT COUNT(*) FROM wp WHERE uidp=" . $pers["uid"] . " and type='fish'"); ?>;
		var _skin = <?= SQL::qCount("SELECT COUNT(*) FROM wp WHERE uidp=" . $pers["uid"] . " and type='skin'"); ?>;
	</script>

	<script type="text/javascript" src="js/inv.js"></script>

	<?

	$herbals = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE type='herbal' and weared=0 and uidp=" . $pers["uid"] . "");
	if ($herbals['count'] > 1 and strpos(" " . $pers["location"], "lavka") > 0) echo "<input type=button class=loc value='Сдать всю траву' onclick=\"location='main.php?give=allherbals'\">";

	$skin = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE type='skin' and weared=0 and uidp=" . $pers["uid"] . "");
	if ($skin['count'] > 1 and strpos(" " . $pers["location"], "lavka") > 0) echo "<input type=button class=loc value='Сдать все шкуры' onclick=\"location='main.php?give=allskin'\">";

	$ruda = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE type='ruda' and weared=0 and uidp=" . $pers["uid"] . "");
	if ($ruda['count'] > 1 and strpos(" " . $pers["location"], "lavka") > 0) echo "<input type=button class=loc value='Сдать всю руду' onclick=\"location='main.php?give=allruda'\">";

	$trees = SQL::q1("SELECT COUNT(id) as count FROM wp WHERE type='trees' and weared=0 and uidp=" . $pers["uid"] . "");
	if ($trees['count'] > 1 and strpos(" " . $pers["location"], "lavka") > 0) echo "<input type=button class=loc value='Сдать все деревья' onclick=\"location='main.php?give=alltrees'\">";
	$fish = SQL::qCount("SELECT COUNT(id) as count FROM wp WHERE type='fish' and weared=0 and uidp=" . $pers["uid"] . "");
	if ($fish['count'] > 1 and strpos(" " . $pers["location"], "lavka") > 0) echo "<input type=button class=loc value='Сдать всю рыбу' onclick=\"location='main.php?give=allfish'\">";
	if ($fish['count'] > 1 and strpos(" " . $pers["location"], "mayk") > 0) echo "<input type=button class=loc value='Сдать всю рыбу' onclick=\"location='main.php?give=allfish'\">";

	if ($herbals['count'] > 1) echo "<br><input type=button class=loc value='Передать все травы' onclick=\"giveallH(" . $herbals . ")\">";

	if ($_RETURN) echo "<br><center><center class=but style='width:60%'><b class=user>" . $_RETURN . "</b></center></center>";

	if ($_GET["inv"] == "presents") {

		if (@$_GET["delpr"]) {

			$exp = explode('_', $_GET["delpr"]);

			$uid = intval($exp[1]);

			$date = intval($exp[0]);

			SQL::q("DELETE FROM presents_gived WHERE uid=" . $uid . " and date=" . $date);
		}

		echo "<script>";

		$count_prs = SQL::q1("SELECT COUNT(*) as count FROM presents_gived WHERE uid=" . $pers["uid"] . "");

		echo "var prs = [" . $count_prs['count'] . "";

		foreach (SQL::q("SELECT * FROM presents_gived WHERE uid=" . $pers["uid"] . "") as $p) {

			$who = $p["who"];

			if ($p["anonymous"]) $who = 'Анонимно';

			echo ",['" . $p["name"] . "','" . $p["image"] . "','" . $who . "','" . date("d.m.Y H:i", $p["date"]) . "','" . $p["text"] . "','" . $p["date"] . '_' . $pers["uid"] . "']";
		}

		echo "];show_presents();";

		echo "</script>";
	} elseif ($_GET["inv"] == "blank") {
		include("inc/blank.php");
	} elseif ($_GET["inv"] == "magic") {
		include_once("inv/magic.php");
	} elseif ($_GET["inv"] == "cat3") {

		if (!SQL::q1("SELECT COUNT(*) as count FROM chars WHERE uid='" . $pers["uid"] . "'")['count']) {
			SQL::q("INSERT INTO `chars` (`uid`) VALUES ('" . $pers["uid"] . "');");
		}
		$chars = SQL::q1("SELECT complects FROM chars WHERE uid='" . $pers["uid"] . "'");

		if (@$_POST["name"]) {

			$perswears = '';

			foreach (SQL::q("SELECT id FROM wp WHERE weared=1 and uidp=" . $pers["uid"] . "") as $w)

				$perswears .= $w[0] . "|";

			$chars["complects"] .= addslashes($_POST["name"]) . ":" . $perswears . "@";

			SQL::q("UPDATE chars SET complects='" . $chars["complects"] . "' WHERE uid=" . $pers["uid"] . "");
		}

		if ($_POST["do"] == "delete") {

			$cc = explode("@", $chars["complects"]);

			$cc = $cc[$_POST["c"]];

			$chars["complects"] = str_replace($cc . "@", "", $chars["complects"]);

			SQL::q("UPDATE chars SET complects='" . $chars["complects"] . "' WHERE uid='" . $pers["uid"] . "'");
		}

		echo "<center><br><table width=80% class=inv_but><tr><td align=center><form method=post>Название:<input type=text name=name class=login> <input class=login type=submit value='Запомнить текущий комплект' style='width:100%'></form></td></tr></table></center>";

		if ($chars["complects"] <> '' and $chars["complects"] <> '@') {

			echo "<hr><form method=post><table border=0>";

			$pres = explode("@", $chars["complects"]);

			$i = 0;

			foreach ($pres as $p) {

				if ($p <> '') {

					echo "<tr>";

					$z = explode(":", $p);

					echo "<td class=but>" . $z[0] . "</td><td><input type=radio name=c value=" . $i . "></td>";

					$i++;
					echo "</tr>";
				}
			}
			echo "</table>";
			echo "<hr><center>Надеть:<input type=radio name=do value=wear> | Удалить:<input type=radio name=do value=delete><br><input type=submit class=login value='Ок' style='width:200px'></center></form>";
		} else echo "<hr>У вас нет комплектов.";

		if ($_GET["action"] == "delete") SQL::q("UPDATE chars SET presents='" . $chars["presents"] . "' WHERE uid='" . $pers["uid"] . "'");
	} elseif ($_GET["inv"] == "cat4") {

		if (@$_GET["delete_making"] and substr_count("|" . $pers["making"] . "|", $_GET["delete_making"] . "|")) {

			$pers["making"] = str_replace("|" . $_GET["delete_making"] . "|", "", "|" . $pers["making"] . "|");

			set_vars("making='" . $pers["making"] . "'", $pers["uid"]);

			echo "<br>Инструкция по сборке стерта из вашего блокнота";
		}

		if ($pers["making"] == "") echo "Вы не изучили ни одной инструкции по сборке.";

		$pers["making"] = str_replace("||", "|", $pers["making"]);

		$making = explode("|", $pers["making"]);

		$making = array_unique($making);

		foreach ($making as $a) {

			$z = 1;

			if ($a <> '') {

				$a = sql::q1("SELECT * FROM `making` WHERE `id`=" . $a);

				echo "<table class=fightlong border=0 width=100%>";

				echo "<tr><td class=laar align=center><b>" . $a["name"] . "</b></td></tr>";

				$v = sql::q1("SELECT * FROM weapons WHERE id='" . $a["id_weapon"] . "'");

				echo "<tr><td class=lbutton>Результат: <b>" . $v["name"] . "</b> 

			" . $v["price"] . "LN</td></tr>";

				echo "<tr><td class=items><input type=button class=submit value='Просмотр' onclick=\"location='main.php?show_making=" . $a["id"] . "'\"></td></tr></table><br>";
			}
		}
	} else {
		if (@$_GET["do_making"] and substr_count("|" . $pers["making"] . "|", $_GET["do_making"] . "|")) {
			$a = sql::q1("SELECT * FROM `making` WHERE `id`=" . $_GET["do_making"]);
			if ($pers["money"] >= $a["price"] and $pers["s5"] >= $a["sm5"]) {
				$z = 1;
				$clot = explode("|", $a["weapons_ids"]);
				$str = '';
				foreach ($clot as $vesh) {

					if (@$vesh) {

						$g = SQL::q1("SELECT id FROM wp WHERE id_in_w='" . $vesh . "' and uidp=" . $pers["uid"] . "");

						if (empty($g["id"])) $z = 0;

						SQL::q("DELETE FROM wp WHERE uidp=" . $pers["uid"] . " and weared=0 and id_in_w='" . $vesh . "' LIMIT 1;");
					}
				}

				$str = substr($str, 0, strlen($str) - 3);

				if ($z == 1)

					insert_wp($a["id_weapon"], $pers["uid"], -1, 0);



				set_vars("money=money-" . $a["price"], $pers["uid"]);
			}
		}

		if (@$_GET["show_making"]) {
			$z = 1;
			$a = SQL::q1("SELECT * FROM `making` WHERE `id`=" . intval($_GET["show_making"]));
			echo "<table class=fightlong border=0 width=100%>";
			echo "<tr><td class=laar align=center><b>" . $a["name"] . "</b></td></tr>";
			$v = SQL::q1("SELECT * FROM weapons WHERE id='" . $a["id_weapon"] . "'");
			echo "<tr><td class=lbutton>Результат: <b>" . $v["name"] . "</b> " . $v["price"] . "LN</td></tr>";
			echo "<tr><td class=items>Ингридиенты:</td></tr>";
			$b = explode("|", $a["weapons_ids"]);
			$i = 0;
			foreach ($b as $id) {
				if ($id <> "") {
					$i++;
					$v = SQL::q1("SELECT name,id FROM weapons WHERE id='" . $id . "'");
					echo "<tr><td>$i) <b>" . $v["name"] . "</b> ";
					$your = SQL::q1("SELECT id FROM wp WHERE uidp=" . $pers["uid"] . " and id_in_w='" . $id . "'");
					if (@$your["id"]) echo "<font class=green>Есть в инвентаре</font></td></tr>";
					else {
						echo "<b><font class=red>Отсутствует</font></b></td></tr>";
						$z = 0;
					}
				}
			}
			echo "<tr><td><hr><font class=time>Требования:</font><br>Деньги: <b>" . $a["price"] . "LN</b><br>Интелект: <b>" . $a["sm5"] . "</b>";

			if ($a["sp1"] > 0) echo "<br>Целитель: <b>" . $a["sp1"] . "</b>";

			if ($a["sp2"] > 0) echo "<br>Тёмное искусство: <b>" . $a["sp2"] . "</b>";

			if ($a["sp5"] > 0) echo "<br>Кузнец: <b>" . $a["sp5"] . "</b>";

			if ($a["sp6"] > 0) echo "<br>Рыбак: <b>" . $a["sp6"] . "</b>";

			if ($a["sp7"] > 0) echo "<br>Шахтёр: <b>" . $a["sp7"] . "</b>";

			if ($a["sp9"] > 0) echo "<br>Торговец: <b>" . $a["sp9"] . "</b>";

			if ($pers["money"] < $a["price"] or $pers["s5"] < $a["sm5"] or $pers["sp1"] < $a["sp1"] or $pers["sp2"] < $a["sp2"] or $pers["sp5"] < $a["sp5"] or $pers["sp6"] < $a["sp6"] or $pers["sp7"] < $a["sp7"] or $pers["sp9"] < $a["sp9"]) $z = 0;

			if ($z == 1)

				echo "<tr><td class=laar align=center><input type=button class=submit value='Собрать' onclick=\"location='main.php?do_making=" . $a["id"] . "'\">";

			echo "<tr><td class=laar align=center><input type=button class=submit value='Удалить' onclick=\"location='main.php?delete_making=" . $a["id"] . "&inv=cat4'\">";

			echo "<tr><td class=laar align=center> ";

			echo "</td></tr></table><br>";
		}

		if ($_GET["inv"] == "cat5" and $pers["alchemy_d"] > 0 and $pers["alchemy_b"] > 0 and $pers["alchemy_m"] > 0) {

			include("inc/alchemy.php");
		} else {

			//// ПОКАЗЫВАЕМ ИНВЕНТАРЬ

			######################
			$counter = 0;
			$type_sort = '';
			if (@$_FILTER["sorti"] and $_FILTER["sorti"] <> 'all')
				$type_sort = "(`type`='" . addslashes($_FILTER["sorti"]) . "')and";
			elseif ($_FILTER["sorti"] == 'all')
				$type_sort = "(`type`<>'herbal' and `type`<>'ruda' and `type`<>'trees' and `type`<>'fish' and `type`<>'skin' and `type`<>'quest' and `type`<>'blank')and";
			elseif ($_FILTER["sorti"] == 'blank')
				$type_sort = " `type`=='blank' and";
			elseif ($_FILTER["sorti"] == 'quest')
				$type_sort = " `type`=='quest' and";
			if (empty($_FILTER["sortp"]) or $_FILTER["sortp"] == "price")
				$sort = "price";
			else
				$sort = "tlevel";
			$koef = 1;
			if ($pers["level"] > 4) $koef = 0.8;

			if ($koef < 1) $koef += $pers["sp9"] / 1000;

			if ($koef > 0.99) $koef = 0.99;

			if ($_ECONOMIST) $koef = 0.99;



			$wp_sht = '';
			$shtukes = '';
			$counter = 0;

			if ($pers["level"] > 4) $koeff = 0.9;
			else $koeff = 1;

			$res = "SELECT * FROM `wp` WHERE " . $type_sort . " (`uidp`=" . $pers["uid"] . ") and weared=0 AND `auction` <> '1' AND in_bank<>1 ORDER BY `" . $sort . "`;";

			echo '<div class="weapons_container">';
			foreach (SQL::q($res) as $vesh) {
				// echo showWeapon([$vesh['id_in_w']]);
				$sht = 1;
				$item_lib = $vesh["id"];
				if (($vesh["durability"] > 0 or $vesh["max_durability"] == 0) and ($vesh["timeout"] == 0 or $vesh["timeout"] > time())) {
					if ($_FILTER["filter_f6"] <> 2 and $shtt = substr_count($wp_sht, '<' . $vesh["image"] . '_' . $vesh["durability"] . '_' . $vesh["price"] . '_' . $vesh["index"] . '>')) {

						$shtukes .= "document.getElementById('" . $vesh["image"] . '_' . $vesh["durability"] . '_' . $vesh["price"] . '_' . $vesh["index"] . "').innerHTML = '" . ($shtt + 1) . " шт.';";

						$wp_sht .= '<' . $vesh["image"] . '_' . $vesh["durability"] . '_' . $vesh["price"] . '_' . $vesh["index"] . '>';
					} else {
						echo "<tr><td align=left class=weapons_box>";
						include("inc/weapon.php");
						$wp_sht .= '<' . $vesh["image"] . '_' . $vesh["durability"] . '_' . $vesh["price"] . '_' . $vesh["index"] . '>';
					}

					if ($shtt == 0) {
						if ($v["present"])
							$koef_cur = 0.5;
						else
							$koef_cur = 1;
						$counter++;
						$buttons = '';

						if ($z == 1 and $napad == '' and ($v["type"] == 'shlem' or $v["type"] == 'orujie' or $v["type"] == 'kolco' or $v["type"] == 'bronya' or $v["type"] == 'naruchi' or $v["type"] == 'perchatki' or $v["type"] == 'ojerelie' or $v["type"] == 'sapogi' or $v["type"] == 'poyas' or $v["type"] == 'kam') and ($pers["sign"] == $v["tsign"] or $v["tsign"] == 'none')) $buttons .= "<td><img title='Надеть' src=images/icons/upload.png onclick=\"location='main.php?wear=" . $vesh["id"] . "'\" style='cursor:pointer'></td>";

						if ($v["arrows_max"] <> $v["arrows"]) $buttons .= "<td><input type=button class=inv_but value='Зарядить[" . ($v["arrows_max"] - $v["arrows"]) . " > " . (($v["arrows_max"] - $v["arrows"]) * $v["arrow_price"]) . " LN]' onclick=\"location='main.php?zzz=" . $vesh["id"] . "'\"></td>";

						if ($z == 1 and $napad == 1) $buttons .= "<td><input type=button class=inv_but value=Использовать onclick=\"napad('" . $item_lib . "')\"></td>";

						if ($z == 1 and $napad == 2) $buttons .= "<td><input type=button class=inv_but value=Использовать onclick=\"zakl('" . $item_lib . "','" . $v['name'] . "','1')\"></td>";

						if ($z == 1 and $vesh["type"] == "teleport") $buttons .= "<td><input type=button class=inv_but value=Использовать onclick=\"teleport('" . $item_lib . "','" . $v['name'] . "')\"></td>";

						if ($z == 1 and $vesh["type"] == "blank" and $vesh["present"] <> "" and $_NOM > 1) {
							$buttons .= "<td><INPUT type=\"button\" class=inv_but value=\" Просмотр \" onclick=\"location.href='?inv=blank'\"></td>";
						}
						if ($z == 1 and $vesh["type"] == "blank" and $vesh["present"] == "" and $_WHOP <> '') $buttons .= "<td><input type=button class=inv_but value='Подписать у " . $_WHOP . "' onclick=\"blank('" . $item_lib . "','" . $v['name'] . "','" . $_WHOP . "')\"></td>";

						if ($z == 1 and $vesh["type"] == "fish" and $vesh["weight"] < 0.1) $buttons .= "<td><input type=button class=inv_but value='Сделать живца' onclick=\"liver('" . $item_lib . "','" . $v['name'] . "','" . $vesh["weight"] . "')\"></td>";

						if ($z == 1 and $vesh["type"] == "potion") $buttons .= "<td><input type=button class=inv_but value=Использовать onclick=\"potion('" . $item_lib . "','" . $v['name'] . "')\"></td>";

						if ($weared_slots > 0 and $v["type"] == 'rune') $buttons .= "<td><input type=button class=inv_but value='Вставить в " . $weared_name . "' onclick=\"location='main.php?rune_join=" . $v["id"] . "'\"></td>";

						if (($v["where_buy"] <> 1 and $v["where_buy"] <> 2 or $v["type"] == "rune") and $v["clan_name"] == "" and $pers["level"] > 4 and $pers["punishment"] < time()) {

							$buttons .= "<td><input type=button class=inv_but value='Передать' onclick=\"peredat('" . $vesh["id"] . "','" . $v["name"] . "')\"></td>";

							$buttons .= "<td><input type=button class=inv_but value='Продать' onclick=\"sellingform('" . $vesh["id"] . "','" . $v["name"] . "')\" ></td>";
						}

						if (strpos(" " . $pers["location"], "lavka") > 0 and $v["where_buy"] <> 1 and ($v["where_buy"] <> 2 or $v["p_type"] == 5 or $v["p_type"] == 6 or $v["type"] == "rune") and ($v["clan_name"] == "" or $pers["clan_state"] == 'g'))
							$buttons .= "<td><input type=button class=inv_but value='Сдать за " . round(($v["price"] * $koef * $koef_cur) * (($v["durability"] + 1) / ($v["max_durability"] + 1)), 2) . "' onclick=\"conf_sale('main.php?lavkasdat=" . $vesh["id"] . "')\"></td>";

						if (strpos(" " . $pers["location"], "bank") > 0 and $v["where_buy"] <> 1 and ($v["where_buy"] <> 2 or $v["p_type"] == 5 or $v["p_type"] == 6 or $v["type"] == "rune") and $v["clan_name"] == "" and $pers["money"] >= round(($v["price"] * 0.1), 2))
							$buttons .= "<td><input type=button class=inv_but value='Сдать в банк на хранение [" . round(($v["price"] * 0.1), 2) . " LN]' onclick=\"conf_sale('main.php?bank=" . $vesh["id"] . "')\"></td>";

						if (strpos(" " . $pers["location"], "dhouse") > 0 and $v["where_buy"] == '1' and $v["clan_name"] == "" and $v["timeout"] == 0 and $v["dprice"] > 5)

							$buttons .= "<td><input type=button class=inv_but value='Сдать за " . ($v["dprice"] * 1) * (($v["durability"] + 1) / ($v["max_durability"] + 1)) . " y.e.' onclick=\"conf_sale('main.php?dhousesdat=" . $vesh["id"] . "')\"></td>";

						if (strpos(" " . $pers["location"], "dhouse") > 0 and $v["where_buy"] == '1' and $v["clan_name"] <> "" and $v["timeout"] == 0	and $status == 'g')

							$buttons .= "<td><input type=button class=inv_but value='Сдать за " . ($v["dprice"] * 1) * (($v["durability"] + 1) / ($v["max_durability"] + 1)) . " y.e.' onclick=\"conf_sale('main.php?dchousesdat=" . $vesh["id"] . "')\"></td>";

						if ($v["where_buy"] <> 1 and $v["where_buy"] <> 2 and $v["clan_name"] == "" and $pers["clan_tr"] and $clan["treasury"] < ($clan["maxtreasury"] + 30) and $v["p_type"] <> 200) {

							$buttons .= "<td><input type=button class=inv_but value='Сдать клану' onclick=\"confc('main.php?to_clan=" . $vesh["id"] . "')\" title='Сдать клану'></td>";
						}

						if (($v["where_buy"] <> 1 and $v["where_buy"] <> 2 or $v["p_type"] = 13) and ($v["clan_name"] == "" or ($v["clan_name"] <> "" and $v["price"] < 1400 and $v["dprice"] < 1 and $status = 'g'))) {
							$buttons .= "</td><td align=right width=50%><img src=images/icons/delete.png onclick=\"conf('main.php?drop=" . $vesh["id"] . "')\" title='Выкинуть' style='cursor:pointer'></td>";
						}
						echo "<table border=0 width=100%><tr><td>" . $buttons . "</td></tr></table></td></tr>";
					}
				} else {
					SQL::q("DELETE FROM wp WHERE id='" . $vesh["id"] . "'");

					if ($vesh["clan_sign"])
						SQL::q("UPDATE clans SET treasury=-1 WHERE sign='" . $pers["sign"] . "'");
				}
			}
			echo "</div>";
			unset($res);
			echo "</table></center>";
			if ($counter == 0)
				echo "<i class=timef><center>У вас нет с собой вещей.</center></i>";
		}
	}
	?>

</div>

<script>
	<?

	echo $shtukes;



	$level = SQL::q1("SELECT * FROM `exp` WHERE `level`=" . ($pers["level"] + 1));

	$level1 = SQL::q1("SELECT * FROM `exp` WHERE `level`=" . ($pers["level"]));

	$zv = SQL::q1("SELECT name FROM `zvanya` WHERE `id` = '" . $pers["zvan"] . "'");

	echo "var DecreaseDamage = " . DecreaseDamage($pers) . ";";
	echo "build_pers('" . $sh["image"] . "','" . $sh["id"] . "','" . $oj["image"] . "','" . $oj["id"] . "','" . $or1["image"] . "','" . $or1["id"] . "','" . $po["image"] . "','" . $po["id"] . "','" . $z1["image"] . "','" . $z1["id"] . "','" . $z2["image"] . "','" . $z2["id"] . "','" . $z3["image"] . "','" . $z3["id"] . "','" . $sa["image"] . "','" . $sa["id"] . "','" . $na["image"] . "','" . $na["id"] . "','" . $pe["image"] . "','" . $pe["id"] . "','" . $or2["image"] . "','" . $or2["id"] . "','" . $ko1["image"] . "','" . $ko1["id"] . "','" . $ko2["image"] . "','" . $ko2["id"] . "','" . $br["image"] . "','" . $br["id"] . "','" . $pers["pol"] . "_" . $pers["obr"] . "',0,'" . $pers["sign"] . "','" . $pers["user"] . "','" . $pers["level"] . "','" . $pers["chp"] . "','" . $pers["hp"] . "','" . $pers["cma"] . "','" . $pers["ma"] . "'," . $pers["tire"] . ",'" . $kam1["image"] . "','" . $kam2["image"] . "','" . $kam3["image"] . "','" . $kam4["image"] . "','" . $kam1["id"] . "','" . $kam2["id"] . "','" . $kam3["id"] . "','" . $kam4["id"] . "'," . $hp . "," . $pers["hp"] . "," . $ma . "," . $pers["ma"] . "," . $sphp . "," . $spma . "," . $pers["s1"] . "," . $pers["s2"] . "," . $pers["s3"] . "," . $pers["s4"] . "," . $pers["s5"] . "," . $pers["s6"] . "," . $pers["free_stats"] . "," . round($pers["money"], 2) . "," . $pers["dmoney"] . "," . $pers["kb"] . "," . $pers["mf1"] . "," . $pers["mf2"] . "," . $pers["mf3"] . "," . $pers["mf4"] . "," . $pers["mf5"] . "," . $pers["udmin"] . "," . $pers["udmax"] . "," . $pers["rank_i"] . ",'" . $zv["name"] . "'," . $pers["victories"] . "," . $pers["losses"] . "," . $pers["exp"] . "," . $pers["peace_exp"] . "," . ($level["exp"] - $pers["exp"] - $pers["peace_exp"]) . "," . $pers["zeroing"] . ",1,'" . $pers["diler"] . "'," . round(($level["exp"] - $pers["exp"]) * 100 / ($level["exp"] - $level1["exp"])) . ",'" . $ws1 . "','" . $ws2 . "','" . $ws3 . "','" . $ws4 . "','" . $ws5 . "','" . $ws6 . "'," . intval($pers["free_f_skills"] + $pers["free_p_skills"] + $pers["free_m_skills"]) . "," . intval($pers["help"]) . "," . intval(($pers["refc"] + $pers["referal_counter"]) ? 1 : 0) . "," . $pers["coins"] . ");";
	?>
</script>
<?
// $as = "SELECT * FROM p_auras WHERE uid=".$pers["uid"].";";
// $txt = '';
// while($a = SQL::q($as))
// {
// 	$txt .= $a["image"].'#<b>'.$a["name"].'</b>@';
// 	$txt .= 'Осталось <i class=timef>'.tp($a["esttime"]-time()).'</i>';
// 	$params = explode("@",$a["params"]);
// 		foreach($params as $par)
// 		{
// 			$p = explode("=",$par);
// 			$perc = '';
// 			if (substr($p[0],0,2)=='mf') $perc = '%';
// 			if ($p[1] and $p[0]<>'cma' and $p[0]<>'chp')
// 			$txt .= '@'.name_of_skill($p[0]).':<b>'.plus_param($p[1]).$perc.'</b>';
// 		}
// 	$txt .= '|';
// }
// echo "<script>view_auras('".$txt."');</script>";