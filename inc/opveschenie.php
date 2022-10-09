<?
// Уровни Рыболовов
if ($pers["sp6"] >= 300 and $pers["sp6"] < 450 and $pers["prof_osn"] == "fishing"  and $pers["prof_osnLVL"] == 0) {
	set_vars("prof_osnLVL=1");
	say_to_chat('f', 'Вы получили <b>1</b> уровень профессии <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
}
if ($pers["sp6"] >= 450 and $pers["sp6"] < 600  and $pers["prof_osn"] == "fishing"  and $pers["prof_osnLVL"] <= 1) {
	set_vars("prof_osnLVL=2");
	say_to_chat('f', 'Вы получили <b>2</b> уровень профессии <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
}
if ($pers["sp6"] >= 600 and $pers["sp6"] < 800  and $pers["prof_osn"] == "fishing"  and $pers["prof_osnLVL"] <= 2) {
	set_vars("prof_osnLVL=3");
	say_to_chat('f', 'Вы получили <b>3</b> уровень профессии <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
}
if ($pers["sp6"] >= 800 and $pers["sp6"] < 1000  and $pers["prof_osn"] == "fishing"  and $pers["prof_osnLVL"] <= 3) {
	set_vars("prof_osnLVL=4");
	say_to_chat('f', 'Вы получили <b>4</b> уровень профессии <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
}
if ($pers["sp6"] >= 1000 and $pers["prof_osn"] == "fishing"  and $pers["prof_osnLVL"] <= 4) {
	set_vars("prof_osnLVL=5");
	say_to_chat('f', 'Вы получили <b>5</b> уровень профессии <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
}
//
########## оповещение о турнире Рыбаков


########## конец оповещение о турнире Рыбаков


if ($pers["prof_osn"] <> "fishing" and $pers["sp6"] > 300) {
	set_vars("sp6=300");
	say_to_chat('f', 'Вы разучились профессии <b>Рыбак</b>! Навык упал до ' . $pers["sp6"] . '.', 1, $pers["user"], '*', 0);
}

/*
if ($ww12["wParam"]==1) { 
	$maxf1=SQL::q("SELECT MAX(weight) AS weight, id, uidp, user FROM wp WHERE `image`='fish_new/".$ww12['lParam']."' GROUP by user ORDER BY weight DESC LIMIT 10");
	$i=1;
	while ($max_tf1=mysqli_fetch_array($maxf1))
		{
		$otbor=sql::q1("SELECT uid FROM users WHERE fish_tournir LIKE '0&%' and uid=".$max_tf1["uidp"]);
		 if ($max_tf1["uidp"]==$otbor["uid"]) echo ""; 
		 else echo "<tr><td>".$i."</td><td>".$max_tf1["user"]."</td><td>".round($max_tf1["weight"],3)."</td></tr>";
		echo $otbor["uid"]."--";
		echo $pers["fish_tournir"];
		//сначало написать функцию в functions.php после раскидать
		if ($ww1["finished"]=='0' and ($ww12["time"]-1)==time()) {
			echo "".tournir_fisher($max_tf1["uidp"], $i)."";
			}
		$i++;
		}	
	}
	*/
