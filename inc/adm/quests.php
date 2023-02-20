<?php
include_once "inc/balance.php";
echo '<script type="text/javascript" src="js/adm_bots.js?3"></script>';
echo '<script type="text/javascript" src="js/adm_quests.js?3"></script>';

function selectQuester($id = null) {
	var_dump($id);
	$rs = sql::q("SELECT id,name FROM residents");
	$rs_select = '<select name=rs>';
	foreach ($rs as $r) {
		$sel = $r["id"] == $id ?'selected': '';
		$rs_select .= '<option value=' . $r["id"] . ' '.$sel.'>' . $r["name"] . '</option>';
	}
	$rs_select .= '</select>';
	return $rs_select;
} 

function selectAction($num = 0) {
	$selects = [
		'Ничего',
		'Перейти на речёвку',
		'Закрыть окно общения',
		'Выдать квест',
		'Написать фразу в чат',
		'Начать бой с говорящим',
		'Выдать опыта',
		'Выдать денег',
		'Выдать бриллиантов',
		'Выдать пергаментов',
		'Вылечить травму',
		'Телепортировать',
		'Забрать квестовую вещь',
		'Выдать квестовую вещь'
	];
	$atype = '';
	$atype = '<select name=atype id=atype onchange="atype_ch()">';
	foreach ($selects as $key=>$select) {
		$sel = $key==$num ?'SELECTED': '';
		$atype .= '<option value='.$key.' '.$sel.'>'.$select.'</option>';
	}
	$atype .= '</select>';
	return $atype;
}

function show_speech($sp, $def = 0)
{
	$text = '';
	if ($def == 0)
		$text .= "<tr>";
	else
		$text .= "<tr>";
	if ($def == 0) {
		$rs = sql::q1("SELECT * FROM residents WHERE speechid=" . $sp["id"]);
		if ($rs["id_bot"]) {
			$b = sql::q1("SELECT * FROM bots WHERE id=" . $rs["id_bot"]);
			$text .= "<td><b class=user>" . $rs["name"] . "</b><b class=lvl>[" . $b["level"] . "]</b><img src=images/info.gif onclick=\"javascript:window.open('binfo.php?" . $rs["id_bot"] . "','_blank')\" style=\"cursor:point\"></td>";
			$text .= "<td><img src='images/persons/" . $rs["image"] . ".gif' height=50></td>";
		}
	}
	$text .= "<td class=about>" . $sp["text"] . "</td>";
	$text .= "<td class=but><b class=blue>" . $sp["kindup"] . "</b></td>";
	$text .= "<td><a href=main.php?spadd=" . $sp["id"] . " class=nt><img src=images/icons/apps_on.png title='Добавть вариант ответа'></a></td>";
	$text .= "<td><a href=main.php?spedit=" . $sp["id"] . "&res=".$rs["id_bot"]." class=nt><img src=images/icons/0_on.png title='Изменить'></a></td>";
	$text .= "<td><a href=main.php?spdelete=" . $sp["id"] . " class=nt><img src=images/icons/delete.png title='Удалить'></a></td>";

	$sps = sql::q("SELECT * FROM speech WHERE id_from=" . $sp["id"]);
	$table = '<table border=0 width=100% cellspacing=0 cellspadding=0 class=but>';
	foreach ($sps as $s) {
		$str = '';
		if ($s["action"] == 1) $str = 'Перейти на речёвку';
		if ($s["action"] == 2) $str = 'Закрыть окно общения';
		if ($s["action"] == 3) $str = 'Выдать квест';
		if ($s["action"] == 4) $str = 'Написать фразу в чат';
		if ($s["action"] == 5) $str = 'Начать бой с говорящим';
		if ($s["action"] == 6) $str = 'Выдать опыта';
		if ($s["action"] == 7) $str = 'Выдать денег';
		if ($s["action"] == 8) $str = 'Выдать бриллиантов';
		if ($s["action"] == 9) $str = 'Выдать пергаментов';
		if ($s["action"] == 10) $str = 'Вылечить травму';
		if ($s["action"] == 11) $str = 'Телепортировать';
		if ($s["action"] == 12) $str = 'Забрать квестовую вещь';
		if ($s["action"] == 13) $str = 'Выдать квестовую вещь';
		if ($str) $str = '[<b class=red>' . $str . '</b><b class=green>' . $s["value"] . '</b>]';
		$table .= "<tr><td class=gray><img src=images/icons/right.png>" . $s["answer"] . "" . $str . "</td><td><table class=but2>" . show_speech($s, 1) . "</table></td></tr>";
	}
	$table .= '</table>';
	$text .= "<td>" . $table . "</td>";
	$text .= "</tr>";
	return $text;
}


function delete_sp($id)
{
	$id = intval($id);
	$_s = sql::q("SELECT id FROM speech WHERE id_from=" . $id);
	foreach ($_s as $s) {
		delete_sp($s["id"]);
	}
	sql::q("DELETE FROM speech WHERE id=" . $id);
}


if ($priv["equests"]) {

	####БЛОК РАБОТЫ С ОБИТАТЕЛЯМИ
	#############################

	if (@$_GET["newrs"] and empty($_POST)) {
		$locs = sql::q("SELECT id,name FROM locations");
		$loc_select = '<select name=location>';
		$loc_select .= '<option value=out SELECTED>ПРИРОДА</option>';
		foreach ($locs as $loc) {
			$loc_select .= '<option value=' . $loc["id"] . '>' . $loc["name"] . '</option>';
		}
		$loc_select .= '</select>';
		echo "<script>var image='male_0';</script>";
		echo "<form action=main.php?added=1 method=post><center><table class=but2 border=0 width=60%>";
		echo "<tr>";
		echo "<td class=timef>Название</td><td width=50%><input type=text class=login style='width:100%;' name=user></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Описание</td><td width=50%><input type=text class=login style='width:100%;' name=description></td>";
		echo "</tr>";
		echo "<td class=timef>Доброта(10 до -10)</td><td width=50%><input type=text class=login style='width:100%;' name=kindness></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Локация</td><td width=50%>" . $loc_select . "<input type=text class=login style='width:20px;' name=x>:<input type=text class=login style='width:20px;' name=y></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Образ</td><td width=50%><input type=hidden name=image id=image value='male_0'><img id=img src='images/persons/male_0.gif' onclick='change_img()' height=80></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Уровень</td><td width=50%><input type=text class=login style='width:40%;' name=lvl value=0></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Сила бота</td><td width=50%><input type=text class=login style='width:50%;' name=power value=100><script>autoconfig();</script><input type=hidden name=balance value=1></td>";

		echo "</tr>";

		echo "<tr>";
		echo "<td class=timef>Тип дропа</td><td width=50%><select name=droptype class=real id=droptype>
		<option value=0>Ничего</option>
		<option value=1>Деньги</option>
		<option value=2>Пергаменты</option>
		<option value=3>Вещь навсегда</option>
		<option value=4>Вещь на день</option>
		<option value=5>Вещь на 3 дня</option>
		<option value=6>Вещь на неделю</option>
		<option value=7>Вещь на месяц</option>
		</select></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class=timef>Значение дропа(Для вещи надо указать ID)(Для пергаментов и денег надо указать среднее значение кол-ва)</td><td width=50%><input type=text class=login style='width:90%;' name=dropvalue value=5></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class=timef>Частота дропа в процентах.</td><td width=50%><input type=text class=login style='width:90%;' name=dropfrequency value=5></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class=timef>Невоспреимчивость к магии</td><td width=50%><select name=magic_resistance class=real>
		<option value=0>Нет</option>
		<option value=1>Да</option>
		</select></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan=4><input type=submit class=login style='width:100%;' value='Добавить'></td>";
		echo "</tr>";
		echo "</table></center>";
	} elseif (@$_GET["added"]) {
		$p = $_POST;
		$class = mtrunc($_POST["class"] - 2) + 1;
		$botlastid = sql::q1("SELECT MAX(id) as max FROM bots")['max'];
		$botlastid = floor($botlastid[0] / 100) * 100 + 100;

		$exp = explode("_", $p["image"]);
		$pol = substr($p["image"], 0, strpos($p["image"], "_"));
		$obr = str_replace($pol . "_", '', $p["image"]);

		$i = $p["lvl"];
		$summ = sql::q1("SELECT SUM(stats) as sum FROM exp WHERE level<=" . $i)['sum'] + 20;
		$params = Class_Params($i, $class, "mech", $p["power"] / 100);
		$params["s1"] += 0.3 * $summ;
		$params["s2"] += 0.2 * $summ;
		$params["s3"] += 0.2 * $summ;
		$params["s4"] += 0.3 * $summ;
		$params["hp"] += $params["s4"] * 5;
		$params["kb"] += $params["s4"];


		sql::q("INSERT INTO `bots` (`id`,`user`,`s1`,`s2`,`s3`,`s4`,`s5`,`s6`,`mf1`,`mf2`,`mf3`,`mf4`,`mf5`,`kb`,`hp`,`ma`,`udmin`,`udmax`,`level`,`obr`,`sm4`,`pol`,`id_skin`,`droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`) VALUES ('" . ($botlastid + $i - intval($p["lvl"])) . "','" . $p["user"] . "','" . $params["s1"] . "','" . $params["s2"] . "','" . $params["s3"] . "','" . $params["s4"] . "',1,1,'" . $params["mf1"] . "','" . $params["mf2"] . "','" . $params["mf3"] . "','" . $params["mf4"] . "','" . $params["mf5"] . "','" . $params["kb"] . "','" . $params["hp"] . "','9','" . ($params["udmin"] + 1) . "','" . ($params["udmax"] + 1) . "','" . $i . "','" . $obr . "','2','" . $pol . "'," . intval($p["skin_id"]) . "," . intval($p["droptype"]) . "," . intval($p["dropvalue"]) . "," . intval($p["dropfrequency"]) . "," . intval($p["magic_resistance"]) . ");");

		echo "<br><font class=user>" . $p["user"] . "</font>[<font class=lvl>" . $i . "</font>]<img src=images/info.gif onclick=\"javascript:window.open('binfo.php?" . ($botlastid + $i - intval($p["minlvl"])) . "','_blank')\" style=\"cursor:point\">";

		sql::q("INSERT INTO `residents` (`name`, `id_bot`, `image`, `location`, `x`, `y`, `kindness`, `description`, `speechid`) VALUES ('" . $p["user"] . "', " . ($botlastid + $i - intval($p["lvl"])) . ", '" . $p["image"] . "', '" . $p["location"] . "', '" . $p["x"] . "', '" . $p["y"] . "', '" . $p["kindness"] . "', '" . $p["description"] . "', 0);");
	}
	if (@$_GET["delete"]) {
		$idb = sql::q1("SELECT id_bot FROM residents WHERE id=" . intval($_GET["delete"]))['id_bot'];
		sql::q1("DELETE FROM bots WHERE id=" . $idb);
		sql::q("DELETE FROM residents WHERE id=" . intval($_GET["delete"]));
	}

	####БЛОК РАБОТЫ С РЕЧЬЮ
	#############################

	if (@$_GET["newsp"] and empty($_POST)) {
		$rs = sql::q("SELECT id,name FROM residents");
		$rs_select = '<select name=rs>';
		foreach ($rs as $r) {
			$rs_select .= '<option value=' . $r["id"] . '>' . $r["name"] . '</option>';
		}
		$rs_select .= '</select>';
		echo "<form action=main.php?newsp=1 method=post>";
		echo "<center><table class=but2 border=0 width=60%>";
		echo "<tr>";
		echo "<td class=timef>Предыстория</td><td width=50%><textarea class=inv rows=4 cols=30 name=prehistory></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Текст</td><td width=50%><textarea class=inv rows=4 cols=30 name=text></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Квестолог</td><td width=50%>" . $rs_select . "</td>";
		echo "</tr>";
		echo "</table></center>";
		echo "<tr>";
		echo "<td colspan=4><input type=submit class=login style='width:100%;' value='Добавить'></td>";
		echo "</tr>";
		echo "</form>";
		echo "<center class=inv>%s - ник персонажа; %l - уровень персонажа; </center>";
	} elseif (@$_GET["newsp"]) {
		$p = $_POST;
		$slast = sql::q1("SELECT MAX(id) as max FROM speech")['max'] + 1;
		sql::q("INSERT INTO `speech` (`id` ,`id_from` ,`answer` ,`text` ,`action` ,`value` ,`kindup`,`prehistory`) VALUES ('$slast', '', '', '" . $p["text"] . "', '0', '', '0','" . $p["prehistory"] . "');");
		sql::q("UPDATE residents SET speechid=" . $slast . " WHERE id=" . intval($p["rs"]));
	}
	$atype = '';
	$atype = '<select name=atype id=atype onchange="atype_ch()">';
	$atype .= '<option value=0 SELECTED>Ничего</option>';
	$atype .= '<option value=1>Перейти на речёвку</option>';
	$atype .= '<option value=2>Закрыть окно общения</option>';
	$atype .= '<option value=3>Выдать квест</option>';
	$atype .= '<option value=4>Написать фразу в чат</option>';
	$atype .= '<option value=5>Начать бой с говорящим</option>';
	$atype .= '<option value=6>Выдать опыта</option>';
	$atype .= '<option value=7>Выдать денег</option>';
	$atype .= '<option value=8>Выдать бриллиантов</option>';
	$atype .= '<option value=9>Выдать пергаментов</option>';
	$atype .= '<option value=10>Вылечить травму</option>';
	$atype .= '<option value=11>Телепортировать</option>';
	$atype .= '<option value=12>Забрать квестовую вещь</option>';
	$atype .= '<option value=13>Выдать квестовую вещь</option>';
	$atype .= '</select>';

	if (@$_GET["spadd"] and empty($_POST)) {
		// $rs = sql::q("SELECT id,name FROM residents");
		// $rs_select = '<select name=rs>';
		// foreach ($rs as $r) {
		// 	$rs_select .= '<option value=' . $r["id"] . '>' . $r["name"] . '</option>';
		// }
		// $rs_select .= '</select>';

		$from_user = '<select name=qwp id=atype onchange="atype_ch()">';
		$from_user .= '<option value=\'null\' SELECTED>Ничего</option>';
		$from_user .= '<option value=\'qwp\'>Квестовый предмет</option>';
		$from_user .= '<option value=\'wp\'>Предмет в рюкзаке</option>';
		$from_user .= '<option value=\'ln\'>Деньги LN</option>';
		$from_user .= '<option value=\'gold\'>Деньги золото</option>';		
		$from_user .= '</select>';

		$speech_select = '<select name=speechto>';
		$asp["id_from"] = intval($_GET["spadd"]);
		do {
			$asp = sql::q1("SELECT id_from,answer,text,id FROM speech WHERE id=" . $asp["id_from"]);
			$speech_select .= str_replace("", "", addslashes("<option value=" . $asp["id"] . ">" . substr($asp["answer"], 0, 20) . "::" . substr($asp["text"] . "</option>", 0, 20)));
		} while ($asp["id_from"]);
		$speech_select .= '</select>';

		echo "<Script>var speech='" . $speech_select . "';</script>";

		echo "<form action=main.php?spadd=" . $_GET["spadd"] . " method=post>";
		echo "<center><table class=but2 border=0 width=60%>";
		echo "<tr>";
		echo "<td class=timef>Предыстория</td><td width=50%><textarea class=but rows=2 cols=30 name=prehistory></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Ответ</td><td width=50%><textarea class=but rows=2 cols=30 name=answer></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Действие</td><td width=50%>" . $atype . "<div id=_atype></div></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Текст</td><td width=50%><textarea class=inv rows=4 cols=30 name=text></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Мораль</td><td width=50%><input type=text class=inv value=0 name=kindness></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Количество показов(0-бесконечно)</td><td width=50%><input type=text class=inv value=0 name=showcounts></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Требование для диалога:</td><td width=50%>" . $from_user . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Показывать до квеста</td><td width=50%>" . $before_q . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Показывать после квеста</td><td width=50%>" . $after_q . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Отношение</td><td width=50%>><input type=radio name=plus value=1> <<input type=radio name=plus value=2> <input type=text name=plusv value=0></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Квестолог</td><td width=50%>" . selectQuester() . "</td>";
		echo "</tr>";
		echo "</table></center>";
		echo "<tr>";
		echo "<td colspan=4><input type=submit class=login style='width:100%;' value='Добавить'></td>";
		echo "</tr>";
		echo "</form>";
		echo "<center class=inv>%s - ник персонажа; %l - уровень персонажа; </center>";
	} elseif (@$_GET["spadd"]) {
		$a = intval($p["atype"]);
		$value = 0;
		if ($a == 1) $value = $p["speechto"];
		if ($a == 3) $value = $p["quest"];
		if ($a == 4) $value = $p["value"];
		if ($a == 6) $value = $p["value"];
		if ($a == 7) $value = $p["value"];
		if ($a == 8) $value = $p["value"];
		if ($a == 9) $value = $p["value"];
		if ($a == 11) $value = $p["location"] . "|" . $p["x"] . "|" . $p["y"];

		$rel = 0;
		if ($p["plus"] and $p["plusv"]) {
			if ($p["plus"] == 1) $rel = abs($p["plusv"]);
			if ($p["plus"] == 2) $rel = -1 * abs($p["plusv"]);
		}

		$p = $_POST;
		$slast = SQL::q1("SELECT MAX(id) as max FROM speech")['max'] + 1;
		SQL::q1("INSERT INTO `speech` (`id` ,`id_from` ,`answer` ,`text` ,`action` ,`value` ,`kindup` , `afterquest`,`beforequest`,`relation`,`showcounts`,`prehistory`, `in_wp`) VALUES ('$slast', '" . intval($_GET["spadd"]) . "', '" . $p["answer"] . "', '" . $p["text"] . "', '" . $p["atype"] . "', '" . $value . "', '" . $p["kindness"] . "'," . intval($p["afterquest"]) . "," . intval($p["beforequest"]) . "," . $rel . "," . intval($p["showcounts"]) . ",'" . $p["prehistory"] . "', '".$p['qwp']."');");
	}

	if (@$_GET["spedit"] and empty($_POST)) {
		$sp = SQL::q1("SELECT * FROM speech WHERE id=" . intval($_GET["spedit"]));
		var_dump($sp);
		$rs = SQL::q("SELECT id,name,speechid FROM residents;");
		echo "<form action=main.php?spedit=" . $sp["id"] . " method=post>";
		echo "<center><table class=but2 border=0 width=60%>";
		echo "<tr>";
		echo "<tr>";
		echo "<td class=timef>Ответ</td><td width=50%><textarea class=but rows=2 cols=30 name=answer>" . $sp["answer"] . "</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=timef>Действие</td><td width=50%>" . selectAction($sp["action"]) . "<div id=_atype></div></td>";
		echo "</tr>";
		echo "<td class=timef>Текст</td><td width=50%><textarea class=inv rows=4 cols=30 name=text>" . $sp["text"] . "</textarea></td>";
		echo "</tr>";
		echo "</table></center>";
		echo "<tr>";
		echo "<td colspan=4><input type=submit class=login style='width:100%;' value='Сохранить'></td>";
		echo "</tr>";
		echo "</form>";
		echo "<center class=inv>%s - ник персонажа; %l - уровень персонажа; %q - квестовая вещь;</center>";
	} elseif (@$_GET["spedit"]) {
		$p = $_POST;
		var_dump($p);
		sql::q("UPDATE speech SET text='" . htmlspecialchars_decode($p["text"]) . "', action=".$p["atype"]." WHERE id=" . intval($_GET["spedit"]));
		//sql::q("UPDATE residents SET speechid=" . intval($_GET["spedit"]) . " WHERE id=" . intval($p["rs"]));
	} elseif (@$_GET["spdelete"]) {
		delete_sp($_GET["spdelete"]);
	}
	#### КОНЕЦ	
}

echo "<center><table width=80% class=but>
<tr>
<td class=but2 width=30% colspan=6><a class=bga href=main.php?go=administration>Назад</a></td>
</tr>
<tr>
<td class=but2 width=30%><a class=bg href=main.php?go=questsR>Обитатели</a></td>
<td class=but2 width=30%><a class=bg href=main.php?go=questsS>Речь</a></td>
<td class=but2 width=30%><a class=bg href=main.php?go=questsQ>Квесты</a></td>
</tr></table></center>";
