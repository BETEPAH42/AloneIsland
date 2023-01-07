<script type="text/javascript" src="/js/fish_edit.js"></script>
<script language=JavaScript src='/js/adm_new.js' type="text/javascript"></script>
<script>
	function give(id) {
		init_main_layer();
		ml.innerHTML += '<form action="main.php?giveFish=' + id + '" method=POST>КОМУ: <input class=login type=text value="" name=nickfor size=20><hr><input class=login type=submit value=[OK]></form>';
	}
</script>
<?php
if (@$_GET["giveFish"]) {
    $uid = SQL::q1("SELECT uid FROM users WHERE user='" . $_POST["nickfor"] . "'")['uid'];
    if(insert_wp_fish((int)$_GET["giveFish"], $uid)){
            echo "Удачно выдано";
    }
}
if (@$_GET["fishining"]) {
	echo "<span style='color:red;'>{$_GET["fishining"]}</span>";
}
if ($_GET["zapis"] == "yes") {
	sql::q("INSERT INTO fish_new (`name` ,`water` ,`active` ,`prim_1` , `prim_2` , `prim_3` , `lvl` , `ves` , `price`) VALUES ('" . $_GET["fish"] . "' , '" . $_GET["water"] . "' , '" . $_GET["active"] . "' , '" . $_GET["prim_1"] . "' , '" . $_GET["prim_2"] . "' , '" . $_GET["prim_3"] . "' , '" . $_GET["lvl"] . "' , '" . $_GET["ves"] . "' , '" . $_GET["price"] . "');");
	echo "Сохранено " . $_GET["fish"] . " [" . $_GET["water"] . "]";
}
echo "<table width=10%><tr><td><a class=bga href=main.php?go=administration>Назад в меню</a></td></tr></table>";

$sortir = "";
$priman = "";
$qyery = "";
if (@$_GET["lvl"]) {
	$lvl = "lvl={$_GET["lvl"]}";
	$query .= "WHERE {$lvl}";
}

if (@$_GET["water"]) {
	$waters = " water like '%{$_GET["water"]}%'";
	if(trim($query)){
		$query .= " and ".$waters;
	} else {
		$query .= "WHERE {$waters}";
	}
}
if (@$_GET["priman"]) {
	if ($_GET["lvl"] <> "all") {
		$priman = "(prim_1 like '{$_GET["priman"]}|__' or prim_2 like '{$_GET["priman"]}|__' or prim_3 like '{$_GET["priman"]}|__') ";
	}
	if(trim($query)){
		$query .= " and ".$priman;
	} else {
		$query .= "WHERE {$priman}";
	}
}

$fishhh = "SELECT * FROM fish_new {$query} ORDER BY id ASC";

echo "<br><center><form method='GET'>Сотрировка: по уровню <form><select name='lvl'>";
if ($_GET["lvl"] == "0") $sl0 = "selected";
echo "<option value=0 {$sl0}>Все</option>";
if ($_GET["lvl"] == "1") $sl1 = "selected";
echo "<option value=1 {$sl1}>0</option>";
if ($_GET["lvl"] == "2") $sl2 = "selected";
echo "<option value=2 {$sl2}>1</option>";
if ($_GET["lvl"] == "3") $sl3 = "selected";
echo "<option value=3 {$sl3}>2</option>";
if ($_GET["lvl"] == "4") $sl4 = "selected";
echo "<option value=4 {$sl4}>3</option>";
if ($_GET["lvl"] == "5") $sl5 = "selected";
echo "<option value=5 {$sl5}>4</option>";
if ($_GET["lvl"] == "6") $sl6 = "selected";
echo "<option value=6 {$sl6}>5</option>";
if ($_GET["lvl"] == "7") $sl7 = "selected";
echo "<option value=7 {$sl7}>6</option>
</select>
по месту обитания:
<select name=water>";
if ($_GET["water"] == "0") $sw0 = "selected";
echo "<option value=0 {$sw0}>Все</option>";
if ($_GET["water"] == "1") $sw1 = "selected";
echo "<option value=1 {$sw1}>Пруд</option>";
if ($_GET["water"] == "2") $sw2 = "selected";
echo "<option value=2 {$sw2}>Озеро</option>";
if ($_GET["water"] == "3") $sw3 = "selected";
echo "<option value=3 {$sw3}>Река</option>";
if ($_GET["water"] == "4") $sw4 = "selected";
echo "<option value=4 {$sw4}>Море</option>";
if ($_GET["water"] == "5") $sw5 = "selected";
echo "<option value=5 {$sw5}>Болото</option>
</select>
 по приманке:
<select name=priman>";
echo "<option value=all";
if ($_GET["priman"] == "all") echo " selected";
echo ">Все</option>";
echo "<option value=o";
if ($_GET["priman"] == "o") echo " selected";
echo ">Опарыш</option>";
echo "<option value=c";
if ($_GET["priman"] == "c") echo " selected";
echo ">Червь</option>";
echo "<option value=m";
if ($_GET["priman"] == "m") echo " selected";
echo ">Мотыль</option>";
echo "<option value=g";
if ($_GET["priman"] == "g") echo " selected";
echo ">Горох</option>";
echo "<option value=i";
if ($_GET["priman"] == "i") echo " selected";
echo ">Икра</option>";
echo "<option value=r";
if ($_GET["priman"] == "r") echo " selected";
echo ">Ручейник</option>";
echo "<option value=h";
if ($_GET["priman"] == "h") echo " selected";
echo ">Хлеб</option>";
echo "<option value=t";
if ($_GET["priman"] == "t") echo " selected";
echo ">Тесто</option>";
echo "<option value=p";
if ($_GET["priman"] == "p") echo " selected";
echo ">Перловка</option>";
echo "<option value=l";
if ($_GET["priman"] == "l") echo " selected";
echo ">Живец</option>";
echo "<option value=k";
if ($_GET["priman"] == "k") echo " selected";
echo ">Кузнечик</option>";
echo "<option value=u";
if ($_GET["priman"] == "u") echo " selected";
echo ">Кукуруза</option>";
echo "<option value=q";
if ($_GET["priman"] == "q") echo " selected";
echo ">Капуста</option>";
echo "<option value=z";
if ($_GET["priman"] == "z") echo " selected";
echo ">Зелень</option>";
echo "<option value=s";
if ($_GET["priman"] == "s") echo " selected";
echo ">Моллюск</option>";
echo "<option value=f";
if ($_GET["priman"] == "f") echo " selected";
echo ">Лягушка</option>";
echo "<option value=d";
if ($_GET["priman"] == "d") echo " selected";
echo ">Муха</option>";
echo "<option value=bl";
if ($_GET["priman"] == "bl") echo " selected";
echo ">Блесна</option>";
echo "</select>
<input type='submit' value='Отсортировать'>
</form>
</center>";

echo "<center class='hp'>БАЗА ДАННЫХ РЫБЫ</center>
<table width=90% align='center'>
	<tr>
		<td align='center'>ID</td>
		<td align='center'>Название рыбы<br>[уровень]:</td>
		<td align='center'>Активность рыбки:</td>
		<td align='center'>Изображение</td>
		<td align='center'>Место обитания</td>
		<td align='center'>Приманки</td>
		<td align='center'>Вес</td>
		<td align='center'>Цена за кг.</td>
		<td align='center'>Примечание</td>
		<td align='center'>Действия</td>
	</tr>";

foreach (SQL::q($fishhh) as $fishh) {

	$water = explode("|", $fishh["water"]);
	$w = 0;
	while ($w < 5) {
		if ($water[$w] == "1") $water[$w] = "Пруд";
		if ($water[$w] == "2") $water[$w] = "Озеро";
		if ($water[$w] == "3") $water[$w] = "Река";
		if ($water[$w] == "4") $water[$w] = "Море";
		if ($water[$w] == "5") $water[$w] = "Болото";
		$w++;
	}
	$ves = explode("|", $fishh["ves"]);
	if ($ves[0] > 1000) $ves[0] = "От " . ($ves[0] / 1000) . " кг.";
	else $ves[0] = "От " . $ves[0] . " гр.";
	if ($ves[1] > 1000) $ves[1] = "до " . ($ves[1] / 1000) . " кг.";
	else $ves[1] = "до " . $ves[1] . " гр.";
	$d = explode("|", $fishh["active"]);
	$d[0] = "Днём " . $d[0] . "%";
	$d[1] = "Ночью " . $d[1] . "%";
	$prim = array(1 => explode("|", $fishh["prim_1"]), explode("|", $fishh["prim_2"]), explode("|", $fishh["prim_3"]));
	$i = 1;
	while ($i < 4) {
		if ($prim[$i][0] == 'o') $prim[$i][0] = "Опарыш";
		if ($prim[$i][0] == 'c') $prim[$i][0] = "Червь";
		if ($prim[$i][0] == 'm') $prim[$i][0] = "Мотыль";
		if ($prim[$i][0] == 'g') $prim[$i][0] = "Горох";
		if ($prim[$i][0] == 'i') $prim[$i][0] = "Икра";
		if ($prim[$i][0] == 'r') $prim[$i][0] = "Ручейник";
		if ($prim[$i][0] == 'h') $prim[$i][0] = "Хлеб";
		if ($prim[$i][0] == 't') $prim[$i][0] = "Тесто";
		if ($prim[$i][0] == 'p') $prim[$i][0] = "Перловка";
		if ($prim[$i][0] == 'l') $prim[$i][0] = "Живец";
		if ($prim[$i][0] == 'k') $prim[$i][0] = "Кузнечик";
		if ($prim[$i][0] == 'u') $prim[$i][0] = "Кукуруза";
		if ($prim[$i][0] == 'q') $prim[$i][0] = "Капуста";
		if ($prim[$i][0] == 'z') $prim[$i][0] = "Зелень";
		if ($prim[$i][0] == 's') $prim[$i][0] = "Моллюск";
		if ($prim[$i][0] == 'f') $prim[$i][0] = "Лягушка";
		if ($prim[$i][0] == 'd') $prim[$i][0] = "Муха";
		if ($prim[$i][0] == 'bl') $prim[$i][0] = "Блесна";
		$i++;
	}

	// if (preg_match("/\b" . $waters . "\b/i", $fishh["water"])) {
		echo "<tr><td align=center>" . $fishh["id"] . "</td><td><font class=user>" . $fishh["name"] . " [" . $fishh["lvl"] . "]</font>
		</td>
		<td align=center>" . $d[0] . "<br>" . $d[1] . "</td>
		<td align=center onclick=\"fish_edits('" . $fishh["name"] . "', '{$fishh["id"]}', '{$fishh["lvl"]}')\" style='cursor:pointer'><img src=images/weapons/fish_new/" . $fishh["id"] . ".gif></td>
		<td align=center>" . $water[0] . " " . $water[1] . " " . $water[2] . "</td>
		<td align=center>" . $prim[1][0] . " " . $prim[1][1] . "<br>" . $prim[2][0] . " " . $prim[2][1] . "<br>" . $prim[3][0] . " " . $prim[3][1] . "</td>
		<td align=center>" . $ves[0] . "<br>" . $ves[1] . "</td><td align=center>" . $fishh["price"] . "<br></td>
		<td align=center>навык<br>" . $fishh["exp"] . "</td>
		<td align=center>
			<input type=button class=login onclick='give({$fishh["id"]});' value='Выдать'>
		</td>
	</tr>";
	// } else echo "";
}
if ($pers["user"] == "BETEPAH") {
	echo "
		<tr>
			<td align='center'>
				<form action='main.php?go=fishin&add=fish' method='POST'>
					Название рыбы:<br>
					<input type='text' name='fish' value='" . $_POST["fish"] . "' size=6>
			</td>
			<td align='center' border=4>Активность рыбы:<br><input type=text name=active value='" . $_POST["active"] . "' size=6></td>
			<td align='center'>Уровень рыбы:<br><input type=text name=lvl value='" . $_POST["lvl"] . "' maxlength=4 size=4></td>
			<td align='center'>Место обитания<br><input type=text name=water value='" . $_POST["water"] . "' maxlength=4 size=4></td>
			<td align='center'>Приманка №1<br><input type=text name=prim_1 value='" . $_POST["prim_1"] . "' maxlength=4 size=4></td>
			<td align='center'>Приманка №2<br><input type=text name=prim_2 value='" . $_POST["prim_2"] . "' maxlength=4 size=4></td>
			<td align='center'>Приманка №3<br><input type=text name=prim_3 value='" . $_POST["prim_3"] . "' maxlength=4 size=4></td>
			<td align='center'>Цена за 1 кг.<br><input type=text name=price value='" . $_POST["price"] . "' maxlength=4 size=4></td>
			<td align='center'>Мин./макc. ВЕС<br><input type=text name=ves value='" . $_POST["ves"] . "' size=4></td>
		</tr>
		<tr>
			<td colspan='9' align='right'>
				<input type='submit' value='Ввести'></form>
			</td>
		</tr>
	</table>";
	// echo "<center class=hp>Результат</center>";
	if ($_GET["add"] == "fish" and $pers["user"] == "BETEPAH") {
		//SQL::q("INSERT INTO fish_new (`name` ,`water` ,`prim_1` , `prim_2` , `prim_3` , `lvl` , `ves` , `price`) VALUES ('".$_POST["fish"]."' , '".$_POST["water"]."' , '".$_POST["prim_1"]."' , '".$_POST["prim_2"]."' , '".$_POST["prim_3"]."' , '".$_POST["lvl"]."' , '".$_POST["ves"]."' , '".$_POST["price"]."');");
	?>
		<div class=lbutton style='display:block;position:absolute;left:30%;top:40%;width:40%;z-index:2;'>
			<?php echo "<center><font size=3>Вы ввели: " . $_POST["fish"] . " | " . $_POST["lvl"] . " | " . $_POST["water"] . " | " . $_POST["prim_1"] . " | " . $_POST["prim_2"] . " | " . $_POST["prim_3"] . " | " . $_POST["price"] . " | " . $_POST["ves"] . " | </font></center>";
			?>
			<div align=center style='display:block;z-index:1;'>
				<table class=bt align=center width=80%>
					<tr>
						<td colspan=3>
							<center class=but>
								<font class=hp>Хотите сохранить эти данные?</font>
							</center>
						</td>
					</tr>
					<tr>
						<td width=40%>
							<center class=ma style="display:none;cursor:pointer;">
								<? echo "<a href='main.php?go=fishin&zapis=yes&fish=" . $_POST["fish"] . "&active=" . $_POST["active"] . "&water=" . $_POST["water"] . "&prim_1=" . $_POST["prim_1"] . "&prim_2=" . $_POST["prim_2"] . "&prim_3=" . $_POST["prim_3"] . "&lvl=" . $_POST["lvl"] . "&price=" . $_POST["price"] . "&ves=" . $_POST["ves"] . "'>Да</a>";
								?>
							</center>
						</td>
						<td width=20%></td>
						<td width=40%>
							<center class=hp style="display:none;cursor:pointer;"><a href=main.php?go=fishin class=knopka>Нет</a></center>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<?
	}
	if ($_GET["add"] == "fish_edit" and $pers["user"] == "BETEPAH") {
		//SQL::q("INSERT INTO fish_new (`name` ,`water` ,`prim_1` , `prim_2` , `prim_3` , `lvl` , `ves` , `price`) VALUES ('".$_POST["fish"]."' , '".$_POST["water"]."' , '".$_POST["prim_1"]."' , '".$_POST["prim_2"]."' , '".$_POST["prim_3"]."' , '".$_POST["lvl"]."' , '".$_POST["ves"]."' , '".$_POST["price"]."');");
	?>
		<div class=lbutton style='display:block; position:absolute; left:30%; top:40%; width:40%; z-index:2;'>
			<? echo "<center><font size=3>Вы ввели: " . $_POST["fish"] . " | " . $_POST["lvl"] . " | " . $_POST["water"] . " | " . $_POST["prim_1"] . " | " . $_POST["prim_2"] . " | " . $_POST["prim_3"] . " | " . $_POST["price"] . " | " . $_POST["ves"] . " | </font></center>";
			?>
			<div align='center' style='display:block;z-index:1;'>
				<table class='bt' align='center' width=80%>
					<tr>
						<td colspan=3>
							<center class='but'>
								<font class='hp'>Хотите сохранить эти данные?</font>
							</center>
						</td>
					</tr>
					<tr>
						<td width=40%>
							<center class='ma' style="display:none;cursor:pointer;">
								<? echo "<a href='main.php?go=fishin&zapis=yes&fish=" . $_POST["fish"] . "&active=" . $_POST["active"] . "&water=" . $_POST["water"] . "&prim_1=" . $_POST["prim_1"] . "&prim_2=" . $_POST["prim_2"] . "&prim_3=" . $_POST["prim_3"] . "&lvl=" . $_POST["lvl"] . "&price=" . $_POST["price"] . "&ves=" . $_POST["ves"] . "'>Да</a>";
								?>
							</center>
						</td>
						<td width=20%></td>
						<td width=40%>
							<center class='hp' style="display:none;cursor:pointer;"><a href='main.php?go=fishin' class='knopka'>Нет</a></center>
						</td>
					</tr>
				</table>
			</div>
		</div>
<?
	}
}
?>