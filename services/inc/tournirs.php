<?
##### турниры рыбаков
$ww12 = SQL::q1("SELECT * FROM quest WHERE `id`=6");
### начало турнира
if ($ww12["time"] <= time() && $ww12["finished"] == "1") {
	//	say_to_chat('s',"Начинаем турнир по ловле рыбы, все условия узнавайте у рыбака на <b> Маяке </b>.",0,'','*',0);
	$vid = rand(1, 2); //позже необходимо разнообразить
	$wf = SQL::q1("SELECT * FROM fish_new ORDER BY RAND()"); //ORDER BY RAND() или WHERE id=8
	say_to_chat('ft', "Начинаем турнир по ловле рыбы, все условия узнавайте у рыбака на <b>Маяке</b>.", 0, '', '*', 0);
	SQL::q("UPDATE quest SET `sParam`='" . $wf['name'] . "', `type`='" . $wf['lvl'] . "', `lParam`='" . $wf['id'] . "', `time`='" . (time() + 14400) . "', `finished`='0', `wParam`='1' WHERE id=6");
}
if ($_GET["tournir_fish"] == "yes" and $ww12["finished"] == '0' and  time() < ($ww12["time"] - 20)) // здесь добавить проверку на наличие рыбы по турниру = and proverka_fish($ww12['lParam'],$pers["uid"])==0
{
	//if (sql::q1("SELECT user FROM wp WHERE `image`='fish_new/".$ww1['lParam']."' and uidp='".$pers["uid"]."'")['user']) echo "есть рыба";
	$pf_uc = explode("&", $pers["fish_tournir"]);
	set_vars("fish_tournir='1&" . $pf_uc[1] . "&" . $pf_uc[2] . "'", UID);
	//$_GET["tournir_fish"]="no";

}
?>

<div class=wer>
	<?
	if ($ww12["wParam"] == '1' and $ww12["finished"] == '0') {

		$maxf = "SELECT MAX(weight) AS weight, id, uidp, user FROM wp WHERE `image`='fish_new/" . $ww12['lParam'] . "' GROUP by user ORDER BY weight DESC LIMIT 10";
		$i = 1;
		foreach (SQL::q($maxf) as $max_tf) {
			if (!tournirer($max_tf["uidp"])) {
				//сначало написать функцию в functions.php после раскидать
				if (($ww12["time"] - 1) == time()) {
					tournir_fisher($max_tf["uidp"], $i);
					say_to_chat('ft', 'Поздравляю Вас с ' . $i . ' местом.', 1, $pers["user"], '*', 0);
				}
			}
			$i++;
		}


		if ((proverka_fish($ww12['lParam'], UID) == '0') and ($ww12["time"]) == time()) {
			tournir_fisher($pers["uid"]);
			say_to_chat('ft', 'К сожалению вы не попали в турнирную таблицу. Спасибо за участие.', 1, $pers["user"], '*', 0);
		}
	}
	if ($ww12["finished"] == '0' and $ww12["time"] <= time()) {
		say_to_chat('ft', "Турнир по ловле рыбы закончен, результаты узнавайте у рыбака на <b>Маяке</b>.", 0, '', '*', 0);
		SQL::q("UPDATE quest SET `time`='" . (time() + 14400) . "', `finished`='1' WHERE id=6");
	}
	?>
</div>