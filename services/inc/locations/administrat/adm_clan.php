<script type="text/javascript" src="js/reg_clan.js?8"></script>
<?
// 
echo "<center><table width=70%><tr><td width=25%><a href='main.php?adm_clan=c&folin=say' class=blocked>Поговорить с Форином</a></td><td width=25%><a href='main.php?adm_clan=c&folin=sclon' class=blocked>Виды преимуществ</a></td><td  width=25%><a href='main.php?adm_clan=c&folin=zaivka' class=blocked>Подать заявку</a></td><td  width=25%><a href='main.php?adm_clan=c&folin=zaivki' class=blocked>Поданые заявки</a></td></tr></table></center>";
echo "";
function adm_clan_do($txt)
{
	echo "<br><center><center>" . $txt . "</center></center><br>";
}
function adm_clan_reg($txt1)
{
	echo "<br><center><center>" . $txt1 . "</center></center><br>";
}
// пиздим с фолином
if (@$_GET["folin"]) {
	if ($_GET["folin"] == 'say') {
		adm_clan_do("<center><table width='670' class=admin_box><tr><td width='135'valign=top><img src='images/administrat/forin.png' alt='Писарь Форин'>
<center><font size=2 color=blue><b>Писарь Форин</b></font></center></td>
<td valign=top><p align=justify><font size=2><b>- Здравствуй, друг мой, меня зову Форин, я руковожу отделом регистрации кланов в Метрополисе. Если ты найдёшь собратьев
по своим интересам и собрав 10 <i>подписанных клановых бланка</i>, ты можешь принести их мне и я составлю заявку, которую рассмотрит Верховный совет.</b></font>
<p align=justify><font size=2 color=red><b>- А сколько нужно персонажей мне привести?</b></font>
<p align=justify><font size=2><b>- Не менне 3 единомышленников, которые дадут своё согласие на создание своего клана.</b></font>
<p align=justify><font size=2 color=red><b>- А где я возьму 10 <i>подписанных клановых бланка</i>? </b></font>
<p align=justify><font size=2><b> - Клановые бланки ты можешь своровать у проигравших тебе бой Варваров или Гиен-войнов. Но эти бланки чисты как капля росы на утренней траве. Но не забывай тот кто принесёт больше всего бланков тот и будет <i>главой</i> Вашего клана</b></font>
<p align=justify><font size=2 color=red><b>- А как же нам их подписать и самое главное у кого?</b></font>
<p align=justify><font size=2><b>- 5 клановых бланка подпишет Вам Верховный совет, 3 подпишу я, 2 бланка вы подпишите после прохождения квестов.</b></font>
<p align=justify><font size=2 color=red><b>- И это всё что потребуется или есть что-то ещё?</b></font>
<p align=justify><font size=2><b>- Ну конечно же мой друг. После того как Вы подпишите все бланки, Вам необходимо составить заявку, где потребуется указать название клана, выбрать значёк клана который будет зависить от склонности клана и эту заявку доложны подписать все единомышленники.</b></font>
</td></table></center>");
	}
	if ($_GET["folin"] == 'sclon') {
		adm_clan_do("<center>Описание возможностей</center>");
	}
	if ($_GET["folin"] == 'zaivka') {
		################### регистрация
		if (@$_POST["podacha"]) {
			$idl = sql::q1("SELECT SUM(id_z) FROM clans_reg");
			$idl++;
			sql::q("INSERT INTO `clans_reg` VALUES ('" . $idl . "','" . addslashes($_POST['do_name_cl']) . "','" . $_POST["do_sclon_cl"] . "','" . $_POST["sign_img"] . "','" . $pers['user'] . "','','','0','');");
			echo "<script>location='main.php?adm_clan=c&folin=zaivki';</script>";
		}

		$kol_bl1 = sql::q("SELECT * FROM wp");
		$kolbl = 0;
		foreach ($kol_bl1 as $kol_bl) {
			if ($kol_bl["uidp"] == $pers["uid"] and $kol_bl["id_in_w"] == 14547 and $kol_bl["present"] <> "")
				$kolbl++;
		}
		//if ($kolbl>10) echo "<br><center>Ты рановато ко мне пришёл воин, ведь у тебя ещё нет 10 <i>Подписанных клановых бланка</i>.</center>";

		if ($pers["sign"] <> "none") {
			echo "<br><center><i><b>Вы не можете подать заявку т.к. уже состоите в клане</b></i>.</center>";
		} else {
			echo "<center><table width='670' class=admin_box><tr><td width='135'valign=top><img src='images/administrat/forin.png' alt='Писарь Форин'>
<center><font size=2 color=blue><b>Писарь Форин</b></font></center></td>
<td valign=top>";
			//<font class=user  onclick=reg_clan('".$pers["user"]."','".$_POST["do_sclon_cl"]."','".str_replace(' ','&nbsp;',$_POST['do_name_cl'])."','','','','','".$pers['uid']."') style='cursor:pointer'>Проверка</font><br>
			echo "<p align=justify><font size=2><b>- Ну что ж " . $pers['user'] . " Вы собрали 10 - " . $kolbl . "<i> Подписанных клановых бланка</i> теперь можно составить заявку:</b></font>
<form method='POST' action=main.php?adm_clan=c&folin=zaivka&do_name_cl=" . $_POST["do_name_cl"] . "&do_sclon_cl=" . $_POST["do_sclon_cl"] . "&sign_img=" . $_POST["sign_img"] . ">
<br><br><font size=2><b><i>Название клана</i>:<input type='text' name=do_name_cl size=40 class=laar value=" . str_replace(' ', '&nbsp;', $_POST['do_name_cl']) . "><BR><hr>";
			if (@$_POST["do_name_cl"]) {
				if ($_POST["do_name_cl"] <> '') {
					echo "
	<i>Склонность клана</i>:<select name=do_sclon_cl class=laar> 
	<option value=none>Без склонности</option>
	<option value=lekari ";
					if ($_POST["do_sclon_cl"] == 'lekari') echo "selected";
					echo ">Целители</option>
	<option value=vampires ";
					if ($_POST["do_sclon_cl"] == 'vampires') echo "selected";
					echo ">Вампиры</option>
	<option value=torgovci ";
					if ($_POST["do_sclon_cl"] == 'torgovci') echo "selected";
					echo ">Торговцы</option>
	</select><br><center><input type='submit' value='Подтвердить!!'></center><br><hr>";
				}


				if ($_POST["do_sclon_cl"]) {
					if ($_POST["do_sclon_cl"] == 'none') $c = 'c/c';
					if ($_POST["do_sclon_cl"] == 'lekari') $c = 'l/l';
					if ($_POST["do_sclon_cl"] == 'vampires') $c = 'v/v';
					if ($_POST["do_sclon_cl"] == 'torgovci') $c = 't/t';
					echo "<i>Значёк клана</i>:";
					$i = 0;
					while ($i < 5) {
						$i++;
						echo "" . $i . "<input type=radio name=sign_img ondblclick value=" . $c . "" . $i;
						if ($_POST["sign_img"] == '' . $c . '' . $i . '') echo " CHECKED";
						echo "><img src='images/signs/" . $c . "" . $i . ".gif'>";
					}
					echo "<br><center><input type='submit' value='Подтвердить!!'></center><br><hr>";
				}

				if (@$_POST["sign_img"] <> '') {
					echo "<br><center><input type='submit' name=podacha value='Подать заявку!!' ></center></font></form>";
				}
			}

			echo "" . $_POST["do_name_cl"] . "," . $_POST["do_sclon_cl"] . "," . $_POST["sign_img"] . "";
			echo "<br>
</td></table></center>";
		}
	}
	if ($_GET["folin"] == 'zaivki') {
		//функция подписи
		if (isset($_GET['podpis'])) {
			if (isset($_GET['podpis'])) {
				echo "<center>Заявку подал " . $pers['user'] . " на создание клана " . $_GET['clan'] . ".</center>";
				sql::q("UPDATE clans_reg SET podpis" . $_GET['podpis'] . "='" . $pers['user'] . "' WHERE id_z='" . $_GET['clan'] . "'");
			} else echo "<center><font color=red>Неизвестная ошибка</font></center>";
		}
		//функция регистрации клана
		if (isset($_GET['sozdat'])) {
			$sozd_clan = sql::q1("SELECT * FROM clans_reg WHERE id_z=" . $_GET['clan'] . "");
			if ($_GET['sozdat'] == 1) {
				sql::q("INSERT INTO `clans` (`name` , `glav` , `sign` , `sklon` ) VALUES ('" . $sozd_clan['name_cl'] . "','" . $sozd_clan['who_zaiv'] . "','" . $sozd_clan['sign_img'] . "','" . $sozd_clan['sclon'] . "')");
				sql::q("UPDATE users SET sign='" . $sozd_clan['sign_img'] . "',rank='<glav>',state='Глава клана', clan_state='g' WHERE user='" . addslashes($sozd_clan['who_zaiv']) . "'");
				sql::q("UPDATE clans_reg SET dobro='" . $_GET['sozdat'] . "' WHERE id_z=" . $_GET['clan']);
				echo "Создание клана " . $sozd_clan['name_cl'] . "";
			}

			if ($_GET['sozdat'] == 2) {
				echo "Отказ";
				sql::q("UPDATE clans_reg SET dobro='" . $_GET['sozdat'] . "' WHERE id_z=" . $_GET['clan']);
			}
			if ($_GET['sozdat'] == 0) echo "Для исключения";
		}
		// вывод таблицы регистрации
		$id_zaiv_1 = sql::q("SELECT * FROM clans_reg ORDER BY id_z ASC");
		echo "<center><table class=but2 width=70%>";
		echo "<tr>
			<td width=5% align=center>Номер заявки</td>
			<td width=20% align=center>Название клана</td>
			<td width=15% align=center>Склонность клана</td>
			<td width=15% align=center>Логотип клана</td>
			<td width=15% align=center>Кто подал заявку</td>
			<td width=20% align=center>Подписи</td>
			<td width=20% align=center>Результат</td>
		</tr>";
		foreach ($id_zaiv_1 as $id_zaiv) {
			echo "<tr> <td><center>" . $id_zaiv['id_z'] . "</center></td><td>" . $id_zaiv['name_cl'] . "</td><td><center>" . $id_zaiv['sclon'] . "</center></td><td><center><img src='images/signs/" . $id_zaiv['sign_img'] . ".gif'></center></td><td><center>" . $id_zaiv['who_zaiv'] . "<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=" . $id_zaiv['who_zaiv'] . "','_blank')\" style='cursor:pointer'></center></td><td width=15% align=center>";
			$onclick = "onclick=\"set_clan('" . $id_zaiv['who_zaiv'] . "','" . $id_zaiv['id_z'] . "','" . $id_zaiv['name_cl'] . "','" . $id_zaiv['sign_img'] . "','" . $id_zaiv['sclon'] . "','" . $id_zaiv['podpis1'] . "','" . $id_zaiv['podpis2'] . "','" . $pers['user'] . "')\" style='cursor:pointer'";

			//обработка подписи заявки
			if ($id_zaiv['podpis1'] == "") echo "<font class=user  " . $onclick . ">Подписать</font>";
			else {
				echo "<center>" . $id_zaiv['podpis1'] . "<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=" . $id_zaiv['podpis1'] . "','_blank')\" style='cursor:pointer'></center>";
				if ($id_zaiv['podpis2'] == "") {
					if ($id_zaiv['podpis1'] == $pers['user']) echo "";
					else echo "<font class=user  " . $onclick . ">Подписать</font>";
				} else {
					echo "<br>
				<center>" . $id_zaiv['podpis2'] . "<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=" . $id_zaiv['podpis2'] . "','_blank')\" style='cursor:pointer'></center>";
				}
			}
			echo "</td>
<td>";
			if ($pers['sign'] == 'c2' and $id_zaiv['dobro'] <> 1 and $id_zaiv['dobro'] <> 2) {
				echo "<center><font onclick=\"reg_clanzaiv('" . $id_zaiv['who_zaiv'] . "','" . $id_zaiv['id_z'] . "','" . $id_zaiv['name_cl'] . "','" . $id_zaiv['sign_img'] . "','" . $id_zaiv['sclon'] . "','" . $id_zaiv['podpis1'] . "','" . $id_zaiv['podpis2'] . "')\" style='cursor:pointer'>Решаем вопрос</font></center>";
			} elseif ($id_zaiv['dobro'] == 0) echo "<center><Font color=blue>На рассмотрении</font></center>";
			elseif ($id_zaiv['dobro'] == 1) echo "<center><Font color=green>Удовлетворено</font></center>";
			elseif ($id_zaiv['dobro'] == 2) echo "<center><Font color=red>Отказано</font></center>";
			echo "</td>
</tr>";
		}
		echo "</table></center>";
	}
}
?>