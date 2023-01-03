<center>
	<table width='90%' border='2'>
		<tr>
			<td>
				<?php

				if (empty($_GET["inv"])) {
					$cl = sql::q("SELECT * FROM clans");
					echo "<table width=10%><tr><td><a class=bga href=main.php?go=administration>Назад в меню</a></td></tr></table>";
					echo "<center><table width='90%' class=but border=0>";
					$i = 1;
					echo "<tr class=user>";
					echo "<td width='3%'><center>№ п/п</center></td>";
					echo "<td width='12%'><center>Клан:</center></td>";
					echo "<td width='15%'><center>Глава клана:</center></td>";
					echo "<td width='15%'><center>Сайт клана:</center></td>";
					echo "<td width='15%'>Склонность: </td>";
					echo "<td width='10%'>Количество членов клана: </td>";
					echo "<td width='10%'><center>Казна клана:</center></td>";
					echo "<td width='10%'><center>Изменения в клан:</center></td>";
					echo "</tr>";
					foreach ($cl as $c) {
						echo "<tr>
	<td width='3%'><center>" . ($i++) . "</center></td>
	<td width='12%' class=user><center><img src=images/signs/" . $c["sign"] . ".gif>" . $c["name"] . "[" . $c["level"] . "]</center></td>
	<td width='15%'class=green><center><a href=info.php?p=" . $c["glav"] . " target=_blank>" . $c["glav"] . "</a></center></td>
	<td width='15%'><center><a class=bg href=http://" . $c["sait"] . " target=_blank>" . $c["sait"] . "</a></center></td>";
						if ($c["sklon"] == 'none') echo "<td width='15%'><center>Нет преимуществ</center></td>";
						if ($c["sklon"] == 'lekari') echo "<td width='15%'><center>Целители</center></td>";
						if ($c["sklon"] == 'pravitel') echo "<td width='15%'><center>Правители мира</center></td>";
						if ($c["sklon"] == 'vampirs') echo "<td width='15%'><center>Вампиризм</center></td>";
						echo "<td width='10%'><center>" . SQL::q1("SELECT COUNT(*) as count FROM users WHERE sign='" . $c["sign"] . "'")['count'] . "</center></td>
	<td width='10%'><center><a class=bga href=main.php?inv=" . $c["sign"] . ">Казна</a></center></td>
	<td width='10%'><center><a class=bga href=main.php?redact=" . $c["sign"] . ">Правка</a></center></td>
	</tr>";
					}
					echo "</table></center>";
				} else {
					if (@$_GET["delete"]) {
						sql::q("UPDATE wp SET durability=0 WHERE id=" . intval($_GET["delete"]));
						echo "Удалено.";
					}

					$sign = $_GET["inv"];
					echo "<a class=bga href=main.php?go=clans>Обратно в режим редактора кланов</a>";
					$clan = sql::q1("SELECT * FROM clans WHERE sign='" . $sign . "'");
					$delete_button1 = "<input type=button class=but onclick=\"location='main.php?inv=" . $clan["sign"] . "&delete=";
					$delete_button2 = "'\" value='Удалить'>";
					include("./inc/inc/clans/inv.php");
				}
				#################################### Редактор кланов
				if (@$_GET["redact"]) {

					$clan_red = sql::q1("SELECT * FROM clans WHERE sign='" . $_GET["redact"] . "'");
					echo "<a class=bga href=main.php?go=clans>Скрыть редактор клана:</a>";
					echo "<center><table width='90%' class=but border=0>";
					echo "<tr class=user>";
					echo "<td width='15%'><center>Сменить название Клана:</center></td>";
					echo "<td width='20%'><center>Сменить Главу клана:</center></td>";
					echo "<td width='15%'><center>Сменить Склонность:</center></td>";
					echo "<td width='15%'><center>LN Клана:</center></td>";
					echo "<td width='15%'><center>Бр. Клана:</center></td>";
					echo "<td width='10%'><center>Поощрить весь Клан:</center></td>";
					echo "<td width='10%'><center>Наказать весь Клан:</center></td>";
					echo "</tr>";
					echo "<tr class=bg>";
					echo "<td width='15%'><center>" . $clan_red["name"] . "</center></td>";
					echo "<td width='20%'><center>" . $clan_red["glav"] . "</center></td>";
					echo "<td width='15%'><center>" . $clan_red["sklon"] . "</center></td>";
					echo "<td width='15%'><center>" . $clan_red["money"] . "</center></td>";
					echo "<td width='15%'><center>" . $clan_red["dmoney"] . "</center></td>";
					echo "<td width='10%'><center>6</center></td>";
					echo "<td width='10%'><center>7</center></td>";
					echo "</tr>";
					echo "<tr class=user>";
					echo "<td width='15%'><center><form method='POST' action=main.php?redact=" . $clan_red["sign"] . ">
<textarea name=do_cl_name cols=15 rows=1>" . $clan_red["name"] . "</textarea><br>
<input type=submit class=login value='Сохранить'></form></center></td>";
					echo "<td width='20%'><center><form method='POST' action=main.php?redact=" . $clan_red["sign"] . ">";

					echo "<select name=do_cl_glava>";
					$cl_pers = sql::q("SELECT * FROM users WHERE sign='" . $clan_red["sign"] . "' ");
					foreach ($cl_pers as $cp) {
						$i++;
						echo "<option value=" . $cp['user'] . ">" . $cp['user'] . "</option>";
					}
					echo "</select><br><input type=submit class=login value='Сохранить'></form></center></td>";

					echo "<td width='15%'><center><form method='POST' action=main.php?redact=" . $clan_red["sign"] . ">
		<select name=do_cl_sklon><option value=none>Ни каких</option>
		<option value=lekari>Целители</option>
		<option value=vampirs>Вампиризм</option>
</select><br><input type=submit class=login value='Сохранить'></form></center></td>";

					echo "<td width='15%'><center><form method='POST' action=main.php?redact=" . $clan_red["sign"] . ">
			<INPUT TYPE=text name=do_cl_money maxlength=6 size=4>
			<select name=do_cl_vmoney>
			<option value=vidati>Выдать</option>
			<option value=zabrat>Забрать</option>
			</select><br><input type=submit class=login value='Сохранить'></font></center></td>";
					echo "<td width='15%'><center><form method='POST' action=main.php?redact=" . $clan_red["sign"] . ">
			<INPUT TYPE=text name=do_cl_dmoney maxlength=6 size=4>
			<select name=do_cl_vdmoney>
			<option value=vidati>Выдать</option>
			<option value=zabrat>Забрать</option>
			</select><br><input type=submit class=login value='Сохранить'></font></center></td>";
					echo "<td width='10%'><center>432</center></td>";
					echo "<td width='10%'><center>345</center></td>";
					echo "</tr>";
					echo "</table></center>";
				}
				################################### Смена названия клана
				if (@$_POST["do_cl_name"]) {
					sql::q("UPDATE clans SET name='" . $_POST["do_cl_name"] . "' WHERE sign='" . $clan_red["sign"] . "'");
					sql::q("UPDATE users SET clan_name='" . $_POST["do_cl_name"] . "' WHERE sign='" . $clan_red["sign"] . "'");
					sql::q("UPDATE wp SET clan_name='" . $_POST["do_cl_name"] . "' WHERE clan_sign='" . $clan_red["sign"] . "'");
					echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
					echo "<center><font size=3 color=red><b>Вы изменили название клана на -> " . $_POST["do_cl_name"] . "</b></font></center>";
				}
				################################### Смена главы клана
				if (@$_POST["do_cl_glava"]) {
					sql::q("UPDATE clans SET glav='" . $_POST["do_cl_glava"] . "' WHERE sign='" . $clan_red["sign"] . "'");
					sql::q("UPDATE users SET state='Боец',clan_state='b' WHERE user='" . $clan_red["glav"] . "'");
					sql::q("UPDATE users SET state='Глава клана',clan_state='g' WHERE user='" . $_POST["do_cl_glava"] . "'");
					echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
				}
				################################### Смена склонности клана
				if (@$_POST["do_cl_sklon"]) {
					sql::q("UPDATE clans SET sklon='" . $_POST["do_cl_sklon"] . "' WHERE sign='" . $clan_red["sign"] . "'");
					echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
				}
				###################################
				################################### Забираем и выдаём деньги
				if (@$_POST["do_cl_money"]) {
					if ($_POST["do_cl_money"] > 0) {
						if (@$_POST["do_cl_vmoney"] == 'zabrat') {
							$cMon = SQL::q1("SELECT * FROM clans WHERE sign='" . $clan_red["sign"] . "' ");
							if (($cMon["money"] - $_POST["do_cl_money"]) >= 0) {
								sql::q("UPDATE clans SET money=money-'" . $_POST["do_cl_money"] . "' WHERE sign='" . $clan_red["sign"] . "'");
								echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
							} else echo "<center><font size=3 color=red><b>Вы не можете забрать больше чем  " . $cMon["money"] . " LN.</b></font></center>";
						}
						if (@$_POST["do_cl_vmoney"] == 'vidati') {
							sql::q("UPDATE clans SET money=money+'" . $_POST["do_cl_money"] . "' WHERE sign='" . $clan_red["sign"] . "'");
							echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
						}
					} else echo "<center><font size=3 color=red><b>Введено не правильное значение</b></font></center>";
				}
				################################### Забираем и выдаём Бр.
				if (@$_POST["do_cl_dmoney"]) {
					if ($_POST["do_cl_dmoney"] > 0) {
						if (@$_POST["do_cl_vdmoney"] == 'zabrat') {
							$cDMon = SQL::q1("SELECT * FROM clans WHERE sign='" . $clan_red["sign"] . "' ");
							if (($cDMon["dmoney"] - $_POST["do_cl_dmoney"]) >= 0) {
								sql::q("UPDATE clans SET dmoney=dmoney-'" . $_POST["do_cl_dmoney"] . "' WHERE sign='" . $clan_red["sign"] . "'");
								echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
							} else echo "<center><font size=3 color=red><b>Вы не можете забрать больше чем  " . $cDMon["dmoney"] . " LN.</b></font></center>";
						}
						if (@$_POST["do_cl_vdmoney"] == 'vidati') {
							sql::q("UPDATE clans SET dmoney=dmoney+'" . $_POST["do_cl_dmoney"] . "' WHERE sign='" . $clan_red["sign"] . "'");
							echo "<script>location='main.php?redact=" . $clan_red["sign"] . "';</script>";
						}
					} else echo "<center><font size=3 color=red><b>Введено не правильное значение</b></center>";
				}
				?>
			</td>
		</tr>
	</table>
</center>