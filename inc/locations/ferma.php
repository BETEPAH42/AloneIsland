<center>
	<font color=blue size=4><b><i>Хижина крестьянина</i></b></font>
</center>

<?
if (!isset($_GET["ferma"]) and $pers["quest_NY"] == '1') {
	if (!isset($_GET['says']))
		echo "<center><a href='main.php?say=ferma&says=1' style='cursor:pointer; text-decoration:none;'>Узнать про ёлочки.</a></center><br>";

	if ($_GET['says'] == 1) {
		echo "- Здравствую воин, по какому поводу ты отвлекаешь меня от работы?<br>";
		echo "<a href='main.php?say=ferma&says=2' style='cursor:pointer; text-decoration:none;'>- Подскажи, где мне искать Новогодние ёлочки? Лесник сказал что ты можешь это знать, так как частенько общался с пропавшими лесорубами.</a><br>";
	} elseif ($_GET['says'] == 2) {
		$tl = rand(3, 7);
		echo "- Да, они иногда заходили ко мне в гости и рассказывали мне различные истории, но иногда они упоминали и места где можно срубить отличные <b>Новогодние ёлочки</b>. Но зачем это тебе?<br>";
		echo "<a href='main.php?say=ferma&says=" . $tl . "' style='cursor:pointer; text-decoration:none;'>- Я пообещал леснику помочь в подготовке праздника Смены цикла и принести ему несколько ёлочек.</a><br>";
	} elseif ($_GET['says'] == 3) {
		echo "<a href='main.php?say=ferma' style='cursor:pointer; text-decoration:none;'>- Одно из таких мест находтся около <i>Ядовитых земель</i> (19 : 12).</a><br>";
		SQL::q("UPDATE users SET quest_NY='1_1' WHERE uid=" . $pers["uid"]);
	} elseif ($_GET['says'] == 6) {
		echo "<a href='main.php?say=ferma' style='cursor:pointer; text-decoration:none;'>- Одно из таких мест находтся около <i>Болотной местности</i> (8 : 12).</a><br>";
		SQL::q("UPDATE users SET quest_NY='1_1' WHERE uid=" . $pers["uid"]);
	} elseif ($_GET['says'] == 7) {
		echo "<a href='main.php?say=ferma' style='cursor:pointer; text-decoration:none;'>- Одно из таких мест находтся около <i>Южной поляны</i> (-4 : 13).</a><br>";
		SQL::q("UPDATE users SET quest_NY='1_1' WHERE uid=" . $pers["uid"]);
	} elseif ($_GET['says'] == 4) {
		echo "<a href='main.php?say=ferma' style='cursor:pointer; text-decoration:none;'>- Одно из таких мест находтся около <i>Западные сады</i> (-13 ; 2).</a><br>";
		SQL::q("UPDATE users SET quest_NY='1_1' WHERE uid=" . $pers["uid"]);
	} elseif ($_GET['says'] == 5) {
		echo "<a href='main.php?say=ferma' style='cursor:pointer; text-decoration:none;'>- Одно из таких мест находтся около <i>Равнинной местности</i> (27 ; -5).</a><br>";
		SQL::q("UPDATE users SET quest_NY='1_1' WHERE uid=" . $pers["uid"]);
	}
} else echo "Отстань от меня я сказал тебе всё что знал.";

echo "<br><a href='winfo.php?w=999999' target='_blank'>Нажми предмет 1009</a>";
?>