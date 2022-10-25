<?
echo "
<script type='text/javascript'>
//обновление ДИВА каждую секунду
setInterval(function() { $('div.show7').load('# div.show7');},1000);
</script> ";
echo "
<style>
#show7 
{
z-index:5000;
}
</style>
";

$ww1 = sql::q1("SELECT * FROM quest WHERE `id`='6'");

### сам турнир
if ($ww1["wParam"] == 1) $vid_tournir = "максимальный вес";
if ($ww1["wParam"] == 2) $vid_tournir = "минимальный вес";
if ($ww1["wParam"] == 3) $vid_tournir = "минимальный вес";

if ($ww1["finished"] == '0') {
	echo "Сегодня проходит турнир по ловле на {$vid_tournir} рыбы <b><i>{$ww1['sParam']}</i></b>!!!  Закончится через <div class='show7'><b><i>" . tp($ww1['time'] - time()) . "</i></b>.</div><br>";
	if ($ww1["type"] > 0) $uchast = " персонажи обладающие профессией рыболова " . $ww1["type"] . " уровня";
	else $uchast = " любые персонажи";

	echo "В данном турнире могут принять участие {$uchast}. Заявку на участие можно подать в течение первых 30 минут со времени начала трунира.<br>";

	$pf_u = explode("&", $pers["fish_tournir"]);
	if ($pf_u[0] == 1) echo "<center>Вы уже участвуете в турнире.</center>"; //добавить ограничение по времени
	elseif (proverka_fish($ww1['lParam'], $pers["uid"]) >= 1) echo "Продайте рыбу";
	else echo "<center><a href='?tournir_fish=yes&fish=" . $ww1['lParam'] . "+' class=laar>Подать заявку</a></center>";
	echo "<br><center>Турнирная таблица первой десятки<br>
		<table width=70% style='login'><tr><td width=10% align=center>Место</td><td width=50% align=center>Ник персонажа</td><td width=40% align=center>Вес рыбы</td></tr>";

	if ($ww1["wParam"] == 1) {

		$maxf = sql::q("SELECT MAX(weight) AS weight, id, uidp, user FROM wp WHERE `image`='fish_new/" . $ww1['lParam'] . "' GROUP by user ORDER BY weight DESC LIMIT 10");
		$i = 1;
		// придумать решение отсутствие не только участников но и отсутствие рыбы	
		foreach ($maxf as $max_tf) {
			if (tournirer($max_tf["uidp"])) {
				echo "<tr><td>" . $i . "</td><td>" . $max_tf["user"] . "</td><td>" . round($max_tf["weight"], 3) . "</td></tr>";
				$i++;
			} else echo $max_tf["user"];
			//сначало написать функцию в functions.php после раскидать

		}
	}

	if ($ww1["wParam"] == 2) {
		$minf = sql::q("SELECT MIN(weight) AS weight, id, user FROM wp WHERE `image`='fish_new/" . $ww1['lParam'] . "' GROUP by user ORDER BY weight ASC LIMIT 10");
		$i = 1;
		foreach ($minf as $min_tf) {
			echo "<tr><td>" . $i . "</td><td>" . $min_tf["user"] . "</td><td>" . round($min_tf["weight"], 3) . "</td></tr>";
			$i++;
		}
	}

	if ($ww1["wParam"] == 3) {
	}

	echo "</table></center><br>";
}
### оповещение
if ($ww1["finished"] == 1) {

	echo "Турнир закончен. Они начинаются в 12.00 ежедневно. Начнётся через <div class='show7'><b><i>" . tp($ww1['time'] - time()) . "</i></b></div>";
}
### конец турнира

if ($ww1["time"] <= time() && $ww1["finished"] == "1") {
	echo "<center><font size=4 color=blue>Ждите ...</font></center>";
}


if ($ww1["finished"] == '0' and $ww1["time"] <= time()) {

	echo "<center><font size=4 color=blue>Ждите ...</font></center>";
}
