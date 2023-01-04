<?
if (isset($_GET["fishing"])) {
	echo "
	<script>
	top.frames['main_top'].document.getElementById('outer').style.display= 'block';
	</script>
	";
	if (date("H") > 6 and date("H") < 22) $hrfish = 1;
	else $hrfish = 0;
	$skill_p = 0;
	if (WEATHER == 3) $skill_p = 50;
	if (WEATHER == 5) $skill_p = $pers["sp6"] / (-2);
	if (WEATHER == 7) $skill_p = $pers["sp6"] / (-1.25);
	if ($pers["prof_osn"] == "fishing") {
		$prof1 = "рыбак";
		$fishLVL = $pers["prof_osnLVL"];
		$trrr = " and (lvl='" . $fishLVL . "'|lvl<'" . $fishLVL . "')";
	} else {
		$prof1 = "другая профа";
		$fishLVL = 0;
		$trrr = "AND lvl='0'";
	}
	if (empty($_POST["primid"])) {
		$p = 'Мало';
		if ($cell["fish_population"] == 0) $p = 'нет рыбы.';
		if ($cell["fish_population"] > 20) $p = 'около 30 рыбок.';
		if ($cell["fish_population"] > 50) $p = 'достаточно рыбы.';
		if ($cell["fish_population"] > 80) $p = 'много рыбы.';
		if ($cell["fish_population"] > 100) $p = 'огромное количество рыбы.';
		if ($cell["fish_population"] > 500) $p = 'туча рыбы.';
		if ($cell["fishing"] == 1) {
			$w = "На пруду";
			$ws = intval($cell["fishing"]);
		}
		if ($cell["fishing"] == 2) {
			$w = "На озере";
			$ws = $cell["fishing"];
		}
		if ($cell["fishing"] == 3) {
			$w = "На речке";
			$ws = intval($cell["fishing"]);
		}
		if ($cell["fishing"] == 4) {
			$w = "На море";
			$ws = intval($cell["fishing"]);
		}
		if ($cell["fishing"] == 5) {
			$w = "В болоте";
			$ws = intval($cell["fishing"]);
		}
		if ($pers["prof_osn"] <> "fishing") $rer = "Вы можете ловить рыбу только 0 уровня";
		else $rer = "Вы можете ловить рыбу только до {$pers["prof_osnLVL"]} уровня.";
		##########################################
		// весь этот раздельчик потом закоментировать :-)
		/*	echo "{$w} можно поймать на <b>червя</b> при ".intval($pers["sp6"]+$skill_p)." и профессии ".$prof1.":<br>";
		$fishing=sql::q("SELECT * FROM fish_new WHERE exp<'".intval($pers["sp6"]+$skill_p)."' AND water LIKE '%{$cell["fishing"]}%' {$trrr}");
		foreach ($fishing as $fishing2)  
			{
			echo $fishing2["name"]."[".$fishing2["lvl"]."] и ";	
			}
	*/
		//echo "<center class=inv></center>";	
		##########################################
		echo "<center class=but2><b>{$w} {$p}</b> (У Вас: " . round($pers["sp6"] + $skill_p, 2) . " навыка)<br>
{$rer}</center>";
	}
	$snasti = sql::q1("SELECT id FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and p_type=1 and durability>0");
	$prim = sql::q1("SELECT id FROM wp WHERE p_type=1 and weared=0 and uidp=" . $pers["uid"] . " and durability>0 and type<>'orujie' and sp6>=0");
	if ($snasti["id"] == '') {
		$z = 0;
		echo "У вас нет снастей для ловли.";
	}
	if ($prim["id"] == '') {
		$z = 0;
		echo "У вас нет приманок для ловли.";
	}
	if ($pers["tire"] > 90) {
		$z = 0;
		echo "Вы слишком устали.";
	}
	if ($z == 1) {
		if (empty($_POST["primid"]) and $pers["waiter"] < tme()) {
			echo "<center class=fightlong><form action=main.php?fishing=on method=post><input type=hidden name=check value='" . md5($lastom_new . "1") . "'>
				<table border=0 class=LinedTable width=90%><tr><td align=center colspan=8><input type=submit class=login value='Ловить' style='width:100%'></td></tr>";
			$res = sql::q("SELECT durability,max_durability,id,image,name FROM wp WHERE uidp=" . $pers["uid"] . " and weared=0 and p_type=1 and durability>0 and type<>'orujie'");
			$a = 1;
			foreach ($res as $v) {
				if ($a == 1) echo "<tr>";
				echo "<td valing=center width=58><img src='images/weapons/" . $v["image"] . ".gif'></td><td class=user>" . $v["name"] . "</td>";
				echo "<td class=timef><center>[";
				$col = 'blue';
				if ($v["durability"] >= ($v["max_durability"] / 2) && $v["durability"] < $v["max_durability"]) $col = 'green';
				if ($v["durability"] < ($v["max_durability"] / 2) && $v["durability"] > 0) $col = 'red';
				echo "<font color=" . $col . ">" . $v['durability'] . "</font> / 
						<font color=blue>" . $v['max_durability'] . "</font>]</center></td>
						<td><center><input type=radio name=primid value=" . $v['id'] . "></center></td>";
				$a++;
				if ($a == 3) {
					echo "</tr>";
					$a = 1;
				}
			}
			echo "</table></form></center>";
			unset($res);
			set_vars("action=5", UID);
		}

		#################################//// вот здесь и будем всё перерабатывать
		elseif (@$_POST["primid"] and $pers["action"] == 5) {
			set_vars("action=0", UID);
			$i = 0;
			while ($i < 1) //количестов выловленного за один заброс, будет зависеть от вида удочки
			{
				echo "<center class=but2>";
				$i++;
				$v = sql::q1("SELECT * FROM wp WHERE p_type=1 and weared=0 and id=" . intval($_POST["primid"]) . " and uidp=" . $pers["uid"] . " and durability>0");
				if (@$v["id"]) {
					sql::q("UPDATE wp SET durability=durability-" . (1 / 2) . " WHERE uidp=" . $pers["uid"] . " and weared=1 and p_type=1 and durability>0 LIMIT 1;");
					if (rand(1, 100) < 5 and round(10.0 / sqrt($pers["sp6"]), 2) > 0) {
						$vesV = mt_rand(100, 250) / 1000;
						sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
						echo "Вы поймали консервную банку.<br><img src=images/weapons/fish/b.jpg> весом {$vesV} кг.<br>Ваше умение \"Рыбак\" выросло на " . round(10.0 / (sqrt($pers["sp6"]) + 1), 2) . "!<br> Мирный опыт +1";
						set_vars(
							"sp6=sp6+" . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . ",peace_exp=peace_exp+1",
							$pers["uid"]
						);
					} elseif (rand(1, 100) < 3 and round(20.0 / sqrt($pers["sp6"]), 2) > 0) {
						$vesV = mt_rand(200, 1000) / 1000;
						sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
						echo "Вы поймали дырявый сапог.<br><img src=images/weapons/fish/n.jpg> {$vesV} кг.<br>Ваше умение \"Рыбак\" выросло на " . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . "!<br> Мирный опыт +1";
						set_vars("sp6=sp6+" . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . ", peace_exp=peace_exp+1", $pers["uid"]);
					} elseif (rand(1, 100) < 3 and round(20.0 / sqrt($pers["sp6"]), 2) > 0) {
						$vesV = mt_rand(50, 300) / 1000;
						sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
						echo "Вы поймали труселя.<br><img src=images/weapons/fish/p.png> {$vesV} кг.<br>Ваше умение \"Рыбак\" выросло на " . round(15.0 / (sqrt($pers["sp6"]) + 1), 2) . "!<br> Мирный опыт +1";
						set_vars("sp6=sp6+" . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . ", peace_exp=peace_exp+1", $pers["uid"]);
					} elseif (rand(1, 500) < 3) {
						sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
						$vesh = insert_wp_new(UID, "price<100 and price>20 and dprice=0 and where_buy=0 ORDER BY RAND()", $pers["user"]);
						echo "Вы поймали вещь!<br>Ваше умение \"Рыбак\" выросло на " . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . "!<br> Мирный опыт +1";
						set_vars("sp6=sp6+" . round(20.0 / (sqrt($pers["sp6"]) + 1), 2) . ", peace_exp=peace_exp+1", $pers["uid"]);
						echo "<hr><center class=weapons_box>";
						include("inc/inc/weapon.php");
						echo "</center>";
					} else {
						//// сначало нужно отсеять лишнюю рыбу не только************************************************************************
						$fish = sql::q1("SELECT * FROM fish_new WHERE exp<" . ($pers["sp6"] + $skill_p) . " {$trrr} and (prim_1 like '{$v["index"]}|__' or prim_2 like '{$v["index"]}|__' or prim_3 like '{$v["index"]}|__') and water like '%" . $cell["fishing"] . "%' ORDER BY RAND()");
						//echo "SELECT * FROM fish_new WHERE exp<".($pers["sp6"]+$skill_p)." {$trrr} and (prim_1 like '{$v["index"]}|__' or prim_2 like '{$v["index"]}|__' or prim_3 like '{$v["index"]}|__') and water like '%".$cell["fishing"]."%' ORDER BY RAND()";
						$prim = array(1 => explode("|", $fish["prim_1"]), explode("|", $fish["prim_2"]), explode("|", $fish["prim_3"]));
						$timefish = explode("|", $fish["active"]);
						if (DAY_TIME == 0) $timef = $timefish[1];
						else $timef = $timefish[0];
						$iii = 1;
						while ($iii < 4) {
							if ($prim[$iii][0] == $v["index"]) {
								$proc = $prim[$iii][1];
								break;
							}
							$iii++;
						}
						$umelka = ($hrfish ? round((($pers["sp6"] / 1290 * 100) * ($proc / 100)) + (($pers["sp6"] / 1290 * 100) * ($timef / 100)), 2) : $proc);
						if (rand(sqrt($cell["fish_population"]), 150) < ($fish["no_kl"] - sqrt($pers["sp6"] + $skill_p) + 10) or $cell["fish_population"] == 0)
							echo "Нет клёва.";
						elseif ($fish["id"] == 0) {
							$riba = sql::q1("SELECT COUNT(*)as q FROM fish_new WHERE exp<" . ($pers["sp6"] + $skill_p) . " and water LIKE '%" . $cell["fishing"] . "%' and (prim_1 like '{$v["index"]}|__' or prim_2 like '{$v["index"]}|__' or prim_3 like '{$v["index"]}|__')");
							if (sql::q1("SELECT COUNT(*) FROM fish_new WHERE exp<" . ($pers["sp6"] + $skill_p) . " and (prim_1 like '{$v["index"]}|__' or prim_2 like '{$v["index"]}|__' or prim_3 like '{$v["index"]}|__') and water like '%" . $cell["fishing"] . "%'"))
								echo "Не хватает умений чтобы ловить здесь рыбу.";
							else
								echo "Не подходит приманка.";

							break; ##################	
						} elseif (rand(1, 100) < ($fish["exp"] / 10 - 2 * sqrt($pers["sp6"] + $skill_p))) {
							echo "Рыба сорвалась.";
							sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
						} elseif (rand(0, 100) <= $umelka) // добавить $timef - время суток ловли рыбы, $proc - процент по наживке, 
						{   //проверяем
							sql::q("UPDATE wp SET durability=durability-1 WHERE uidp=" . $pers["uid"] . " and weared=0 and id=" . $v["id"] . "");
							$durability = 1;
							$vesh_1 = insert_wp("fish_1", $pers["uid"], -1, 0);

							$vesh = sql::q1("SELECT * FROM wp WHERE id=" . $vesh_1 . "");

							if ($pers["prof_osnLVL"] >= 0) $k = mt_rand(0, 2);
							elseif ($pers["prof_osnLVL"] >= 1) $k = mt_rand(0, 3);
							elseif ($pers["prof_osnLVL"] >= 2) $k = mt_rand(0, 4);
							elseif ($pers["prof_osnLVL"] >= 3) $k = mt_rand(0, 5);
							elseif ($pers["prof_osnLVL"] >= 4) $k = mt_rand(0, 6);
							$ves = explode("|", $fish["ves"]);
							$rvnach = floor(($ves[1] - $ves[0]) / 7);
							$rvsred = ($ves[1] - $ves[0]) / 2 + $ves[0];
							if ($k == 0) {
								$l = "Малёк.";
								$wr = mt_rand($ves[0], $ves[0] + $rvnach);
							}
							if ($k == 1) {
								$l = "Подросший малёк.";
								$wr = mt_rand($ves[0], $ves[0] + $rvnach * 2);
							}
							if ($k == 2) {
								$l = "Малая.";
								$wr = mt_rand($ves[0] + $rvnach * 2, $ves[0] + $rvnach * 3);
							}
							if ($k == 3) {
								$l = "Средняя.";
								$wr = mt_rand($rvsred - 100, $rvsred + 100);
							}
							if ($k == 4) {
								$l = "Большая.";
								$wr = rand($ves[1] - $rvnach * 3, $ves[1] - $rvnach * 2);
							}
							if ($k == 5) {
								$l = "Огромная.";
								$wr = rand($ves[1] - $rvnach * 2, $ves[1] - $rvnach);
							}
							if ($k == 6) {
								$l = "Гигантская.";
								$wr = rand($ves[1] - $rvnach, $ves[1]);
							}
							$vesh["weight"] = $wr / 1000; // внести изменения по весу не забывая что вес весь в гр. а нужен в кг.
							$vesh["price"] = round($vesh["weight"] * $fish["price"], 2); //стоимость будет зависеть от веса, т.е. за кг.
							$vesh["timeout"] = (tme() + 345600);
							$vesh["name"] = $fish["name"];
							$vesh["image"] = "fish_new/" . $fish["id"];
							$vesh["tlevel"] = $fish["lvl"];
							$skill_fishUP = round(1 / (sqrt($pers["sp6"]) + 1), 2);
							$vesV = $vesh["weight"];
							if ($pers["sp6"] >= 1290) $skill_fishUP = 0;
							if ($pers["prof_osn"] <> "fishing" and $pers["sp6"] <= 300) $skill_fishUP = 0;
							//	echo "<center><b> ".$pers["sp6"]+round(1/(sqrt($pers["sp6"])+1),2)." вероятности вес от ".$ves[0]." до ".$ves[1]." вес рибки ".($wr/1000)." гр.</b> сейчас ".$hrfish." - ".$timef."</center>";
							echo "<b class=green>Вы поймали рыбу на {$v["name"]}!</b><br><i class=timef> " . $l . "</i><br> Мирный опыт +5";
							sql::q("UPDATE wp SET price=" . $vesh["price"] . ", weight=" . $vesh["weight"] . ",timeout=" . (tme() + 345600) . ",`describe`='" . $l . "',name='" . $vesh["name"] . "',image='" . $vesh["image"] . "', tlevel='" . $vesh["tlevel"] . "' WHERE id=" . $vesh["id"] . "");
							$vesh["timeout"] = (tme() + 345600);
							set_vars("sp6=sp6+{$skill_fishUP},peace_exp=peace_exp+5", $pers["uid"]);
							echo "<hr><center class=weapons_box>";
							include("inc/inc/weapon.php");
							echo "</center>";
							sql::q("UPDATE nature SET fish_population=fish_population-1 WHERE x=" . $cell["x"] . " and y=" . $cell["y"] . "");
						} else
							echo "Рыба сорвалась с крючка!";
					}
				} else
					break;
				echo "</center>";
			}
			if ((FISHING_TIME * $vesV) > 600) $t_f = 600;
			else $t_f = FISHING_TIME * $vesV;
			set_vars("waiter=" . round(tme() + $t_f) . ",tire=tire+2*" . $i . "", $pers["uid"]);
			$pers["waiter"] = round(tme() + $t_f);
		}
	}
}
