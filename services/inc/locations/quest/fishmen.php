<?
$qFish = sql::q1("SELECT * FROM quest WHERE id = " . Q_FISH);
if (!$qFish) {
	sql::q("INSERT INTO quest (id,finshed)VALUES(" . Q_FISH . ",1)");
}
if ($qFish["finished"] == 1 && $qFish["time"] < tme()) {
	$randWp = sql::q1("SELECT name FROM wp WHERE price<300 and dprice=0 and type='fish' ORDER BY RAND() LIMIT 0,1;")['name'];
	$randCell = sql::q1("SELECT x,y FROM nature WHERE (x*x+y*y)<1024 ORDER BY RAND() LIMIT 0,1");
	if (signum($randCell["x"]) == 0 && signum($randCell["y"]) == -1) $go_n = 'север';
	if (signum($randCell["x"]) == 0 && signum($randCell["y"]) == 1) $go_n = 'юг';
	if (signum($randCell["x"]) == -1 && signum($randCell["y"]) == 0) $go_n = 'запад';
	if (signum($randCell["x"]) == 1 && signum($randCell["y"]) == 0) $go_n = 'восток';
	if (signum($randCell["x"]) == -1 && signum($randCell["y"]) == -1) $go_n = 'северо-запад';
	if (signum($randCell["x"]) == -1 && signum($randCell["y"]) == 1) $go_n = 'юго-запад';
	if (signum($randCell["x"]) == 1 && signum($randCell["y"]) == -1) $go_n = 'северо-восток';
	if (signum($randCell["x"]) == 1 && signum($randCell["y"]) == 1) $go_n = 'юго-восток';
	say_to_chat("o", "Рыбак Чувак снова просит обитателей Метрополиса помочь ему в приготовлении новой термоядерной ухи. На этот раз ему нужно <b>«" . $randWp . "»</b>. И как обычно он отблагодарит за помощь щедрыми подарками!", 0, '', '*', 0);
	say_to_chat("o", "Хитрый старичок забрел не так далеко от города, но его нужно поискать. Он считает, что находится на " . $go_n . "е(Неподалеку от локации [" . rand($randCell["x"] - 3, $randCell["x"] + 3) . " : " . rand($randCell["y"] - 3, $randCell["y"] + 3) . "]) от входа в метрополис,и ожидать вас он будет ровно час.", 0, '', '*', 0);
	$qFish["sParam"] = $randWp;
	$qFish["lParam"] = $randCell["x"];
	$qFish["zParam"] = $randCell["y"];
	sql::q(
		"UPDATE quest SET sParam = '" . $qFish["sParam"] . "', lParam = '" . $qFish["lParam"] . "', zParam = '" . $qFish["zParam"] . "',	finished = 0,time = " . (tme() + 3600) . " WHERE id =" . Q_FISH . ""
	);
}
if (@$_GET["gF"] && !$qFish["finished"] && $qFish["time"] > tme()) {
	if ($pers["x"] == $qFish["lParam"] && $pers["y"] == $qFish["zParam"]) {
		$yourWp = sql::q1("SELECT * FROM wp WHERE uidp=" . UID . " and weared=0 and name='" . $qFish["sParam"] . "'");
		if ($yourWp) {
			if ($pers["pol"] == 'female') {
				$male = 'а';
				$la = "ла";
			} else {
				$male = '';
				$la = "";
			}
			sql::q("UPDATE wp SET durability=0 WHERE id=" . $yourWp["id"] . "");
			$r = rand(2, 4);
			$exp = 1000 + round(($pers["level"] * 1000) / $pers["questFISH"], 0);
			$ln = $yourWp["price"] * 2;
			say_to_chat("o", "Рыбак в восторге от великодушия <b>" . $pers["user"] . "</b>, ведь он" . $male . " помог" . $la . " ему в осуществлении его нового плана! Он щедро дарит <b>" . $pers["user"] . "</b> " . $exp . " опыта и сундук с сокровищами.", 0, '', '*', 0);
			say_to_chat("o", "Рыбак дарит вам " . $exp . " опыта, " . $r . " пергамента, <b>" . $ln . " LN</b> и накладывает на вас «Благословение Небес»", 1, $pers["user"], '*', 0);
			sql::q("UPDATE users SET exp = exp + " . $exp . ", 
					money = money + " . $ln . ", 
					coins = coins + " . $r . ", 
					questFISH = questFISH + 1
					WHERE uid=" . UID . "");
			$a["image"] = 35;
			$a["params"] = '';
			$a["esttime"] = 3600;
			$a["name"] = 'Благословение Небес';
			$a["special"] = 16;
			light_aura_on($a, $pers["uid"]);
			sql::q("UPDATE quest SET  finished = 1, time = " . (tme() + 3600) . " WHERE id = " . Q_FISH . "");
		}
	}
} else
	if (!$qFish["finished"] && $qFish["time"] > tme()) {
	if (
		$pers["x"] == $qFish["lParam"] &&
		$pers["y"] == $qFish["zParam"]
	) {
		$_RETURN .= '<center class=but>Вы нашли Рыбака!</center><i class=user>Он всё ещё нуждается в <b>«' . $qFish["sParam"] . '»</b></i>';
		$yourWp = sql::q1("SELECT * FROM wp WHERE uidp=" . UID . " and weared=0 and name='" . $qFish["sParam"] . "'");
		if ($yourWp) {
			$vesh = $yourWp;
			include("inc/inc/weapon2.php");
			$_RETURN .= "<div class=but2><script>" . $text . "</script></div><input type=button class=login value='Отдать' onclick=\"location = 'main.php?gF=" . md5(tme()) . "';\">";
		}
	}
}
if (!$qFish["finished"] && $qFish["time"] <= tme()) {
	say_to_chat("o", "Никто не смог помочь Рыбаку... Огрызнувшись на нерадивых жителей, он ушёл спать...", 0, '', '*', 0);
	sql::q("UPDATE quest SET finished = 1,	time = " . (tme() + 3600) . "	WHERE id = " . Q_FISH . "");
}
