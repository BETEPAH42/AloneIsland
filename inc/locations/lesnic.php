<center>
	<font color=blue size=4><b><i>Лесопилка</i></b></font>
</center>
<center>
	<table width=80%>
		<tr>
			<td width=30%>Картинка</td>
			<td width=70%>

				<?
				$e = 0;
				$q_skin1 = SQL::q("SELECT `id_in_w` FROM `wp` WHERE user='" . $pers['user'] . "'");
				foreach ($q_skin1 as $q_skin) {
					if ($q_skin['id_in_w'] == 'res..tree41')
						$e++;
				}


				################## квесты
				if (!isset($_GET['say_lesnic']) or $_GET['say_lesnic'] == '0') {
					############### елочный квест
					if ($pers['quest_NY'] == 2) {
						echo " Начать разговор?<br>";
					} else {
						echo " Прийдя на лесопилку вы встретили лесника +имя+";
						echo "<br><a href='main.php?say=lesnic&say_lesnic=";
						if ($pers["quest_NY"] == 0)
							echo "NY_1";
						elseif (($pers["quest_NY"] == 1 or $pers["quest_NY"] == '1_1') and $e < 8) echo "NY_8";
						elseif (($pers["quest_NY"] == 1 or $pers["quest_NY"] == '1_1') and $e >= 8) echo "NY_9";
						echo "' style='cursor:pointer; text-decoration:none;color: red; font-weight:bold; border: 0px;'>Начать разговор</a><br>";
					}
				}

				if ($_GET["say_lesnic"]) {

					$NYear = sql::q1("SELECT * FROM `dialog_quest` WHERE id_d='" . $_GET["say_lesnic"] . "'");

					echo $NYear["dialog"];
					echo "<br>";
					if ($NYear['vopros1'] <> '') echo "<a href='main.php?say=lesnic&say_lesnic=" . $NYear['id_1'] . "' style='cursor:pointer; text-decoration:none;'>" . $NYear['vopros1'] . "</a><br>";
					if ($NYear['vopros2'] <> '') echo "<a href='main.php?say=lesnic&say_lesnic=" . $NYear['id_2'] . "' style='cursor:pointer; text-decoration:none;'>" . $NYear['vopros2'] . "</a><br>";
					if ($NYear['vopros3'] <> '') echo "<a href='main.php?say=lesnic&say_lesnic=" . $NYear['id_3'] . "' style='cursor:pointer; text-decoration:none;'>" . $NYear['vopros3'] . "</a><br>";

					if ($_GET["say_lesnic"] == 'NY_5') {
						echo " - Прощай мой друг!!!";
						sql::q("UPDATE users SET quest_NY='-' WHERE uid=" . $pers["uid"]);
						say_to_chat("s", 'Лесник <b>&laquo;Имя&raquo;</b> больше никогда с Вами разговаривать не будет!', "1", $pers["user"], $pers["location"], date("H:i:s"));
					}
					if ($_GET["say_lesnic"] == 'NY_7') {
						echo "- Я буду тебе очень благодарен воин если ты поможешь мне.!";
						sql::q("UPDATE users SET quest_NY='1' WHERE uid=" . $pers["uid"]);
						say_to_chat("s", 'Предновогодний квест <b>&laquo;Помощь леснику&raquo;</b> начался!', "1", $pers["user"], $pers["location"], date("H:i:s"));
					}
					if ($_GET["say_lesnic"] == 'NY_8') {
						echo "- Ты же ещё не принёс мне 8 Новогодних ёлочек?<br>";
						$proba = rand(1, 3);
						if ($proba == 1) echo "<a href='main.php?say=lesnic' style='cursor:pointer; text-decoration:none;'>- Упс, извени, скоро вернусь?</a><br>";
						if ($proba == 2) echo "<a href='main.php?say=lesnic' style='cursor:pointer; text-decoration:none;'>- Да,я просто решил тебя проверить не забыл ли ты меня?</a><br>";
						if ($proba == 3) echo "<a href='main.php?say=lesnic' style='cursor:pointer; text-decoration:none;'>- О_о, точно побежал!</a><br>";
					}
					if ($_GET["say_lesnic"] == 'NY_9') {
						echo "- Спасибо что помог мне воин, и благодарность моя будет выражена и материально!";
						sql::q("UPDATE users SET quest_NY='2', exp=exp+25000, money=money+1500, coins=coins+10 WHERE uid=" . $pers["uid"]);
						say_to_chat("s", 'Предновогодний квест <b>&laquo;Помощь леснику&raquo;</b> выполнен!', "1", $pers["user"], $pers["location"], date("H:i:s"));
						say_to_chat("s", 'За выполнение квеста <b>&laquo;Помощь леснику&raquo;</b> Вы получаете 25000 опыта, 1500 LN и 10 пергаментов!', "1", $pers["user"], $pers["location"], date("H:i:s"));
					}
				}


				?>
			</td>
		</tr>
	</table>
</center>