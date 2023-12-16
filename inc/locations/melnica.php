<?php
echo "<center>ПРоверка мельницы!!</center>";
$n = 0;
$q_skin1 = SQL::q("SELECT `id_in_w` FROM `wp` WHERE user='" . $pers['user'] . "'");
foreach ($q_skin1 as $q_skin) {
	if ($q_skin['id_in_w'] == 'res..skin14')
		$n++;
}
$d = '';
if ($n == 0 or $n >= 5) $d = 'шкур';
if ($n == 1) $d = 'шкуру';
if ($n >= 2 and $n <= 4) $d = 'шкуры';

?>
<table align='center' width="80%">
	<tr>
		<td width='219'><img src='images/quests/quest_mel.png'></td>
		<td valign='top'>
			<center>ДЕЛАЕМ ДИАЛОГИ</center>
			<?

			if (!isset($_GET['say_mel']) or $_GET['say_mel'] == '0') {

				if ($pers['quest2'] == '-' or $pers['quest2'] == '2') {
					echo "'Начать разговор<br>";
				} else {
					echo "<a href='main.php?say=melnica&say_mel=";

					if ($pers['quest2'] == 0) echo "1";
					else {
						if ($pers['quest2'] == 1 and $n >= 10) echo "quest_mel_1";
						else echo "vipolnenie";
					}
					echo "'>Начать разговор</a><br>";
				}
				echo "<a href='main.php?say=melnica&say_mel=hleb'>Продолжить разговор</a><br>";
			}
			if ($_GET["say_mel"]) {
				if ($pers['quest2'] <> '-') {
					$MEL = sql::q1("SELECT * FROM `dialog_quest` WHERE id_d='" . $_GET["say_mel"] . "'");

					echo "" . $MEL["dialog"] . " ";
					if ($pers['quest2'] == '1') echo "<b>Хотя ты заполучил $n $d.</b>";

					echo "<br>";
					if ($MEL['vopros1'] <> '') echo "<a href='main.php?say=melnica&say_mel=" . $MEL['id_1'] . "'>" . $MEL['vopros1'] . "</a><br>";
					if ($MEL['vopros2'] <> '') echo "<a href='main.php?say=melnica&say_mel=" . $MEL['id_2'] . "'>" . $MEL['vopros2'] . "</a><br>";
					if ($MEL['vopros3'] <> '') echo "<a href='main.php?say=melnica&say_mel=" . $MEL['id_3'] . "'>" . $MEL['vopros3'] . "</a><br>";
				} else echo "- Воин ты разочаровал меня и я не буду с тобой больще общаться!";

				if ($_GET["say_mel"] == 'hleb') {
					echo "Сделаем магизин или что-нить на подобие его<br>";
					echo "<br> Проба создания ссылки на предметы ";
					echo "<img src='images/' onclick=\"javascript:window.open('winfo.php?w=1009','_blank')\" style='cursor:pointer'>";
				}
				########### Начинаем квеста по крысам
				if ($_GET['say_mel'] == 'quest_go1') {

					echo "- Я буду тебе очень благодарен воин если ты поможешь мне.!";
					sql::q("UPDATE users SET quest2='1' WHERE uid=" . $pers["uid"]);
					say_to_chat("s", 'Квест <b>&laquo;Спасение урожая&raquo;</b> начался!', "1", $pers["user"], $pers["location"], date("H:i:s"));
				}
				if ($_GET['say_mel'] == 'quest_mel_end') {

					echo " - Прощай мой друг!!!";
					sql::q("UPDATE users SET quest2='-' WHERE uid=" . $pers["uid"]);
					say_to_chat("s", 'Вы больше не сможете брать задания у старого мельника!', "1", $pers["user"], $pers["location"], date("H:i:s"));
				}

				########### Окончание квеста по крысам
				if ($_GET['say_mel'] == 'quest_mel_end') {

					echo " - Вознаграждние.!";
					sql::q1("UPDATE users SET quest2='2' WHERE uid=" . $pers["uid"]);
					say_to_chat("s", 'Квест <b>&laquo;Спасение урожая&raquo;</b> начался!', "1", $pers["user"], $pers["location"], date("H:i:s"));
					say_to_chat("s", 'За прохождение квеста <b>&laquo;Спасение урожая&raquo;</b> вы получаете <b>25 000 опыта</b>, <b>1 000 LM</b>, <b>10 Бр.&laquo;Неподписанный клановый бланк&raquo; в количестве 2 шт.</b> !', "1", $pers["user"], $pers["location"], date("H:i:s"));
					SQL::q1("UPDATE users SET quest1 = 2, exp=exp+25000, money=money+1000, dmoney=dmoney+10 where user='" . $pers["user"] . "'");

					insert_wp(15638, $pers["uid"]);
					insert_wp(15638, $pers["uid"]);

					SQL::q1("DELETE FROM wp WHERE user='" . $pers["user"] . "' AND id_in_w='res..skin14' LIMIT 10");
				}
				echo "<Center><a href='main.php?say=melnica'><b>Возврат</b></a></Center>";
			}
			?>
		</td>
	</tr>
</table>