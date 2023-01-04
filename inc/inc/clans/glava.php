<?
echo "<SCRIPT type=\"text/JavaScript\">
function startCountdown(){
	   for (var i=1; i<3;i++){
	   var obj= document.getElementById('timer_inp'+i);
	 
	
	  
	   	if (obj.innerHTML>60) {
	if ((obj.innerHTML/3600)>0) {hr2 = obj.innerHTML/3600; var hr = Math.floor(hr2);
	}
	   min = obj.innerHTML%3600/60;
	   var m = Math.floor(min);
	   sec = (obj.innerHTML%3600)%60;
	   sec--;
	   }
	   else {sec=obj.innerHTML; min=0;}
		if (hr>4 || hr==0) okon='ов';
		if (hr>1 && hr<4) okon='а';
		else okon='';
	if(hr>0) 
	obj.innerHTML = hr+' час'+okon+' '+m+' минут '+sec+' секунд';

	if(hr===0) 
	obj.innerHTML = m+' минут '+sec+' секунд';
	   }
	   
    setTimeout(startCountdown,1000);
}
setTimeout(startCountdown,1000);
</script>";

if ($pers['sign'] == 'c2') {
	function reporty($txt)
	{
		echo "<br><center class=but2><center class=puns>" . $txt . "</center></center><br>";
	}
	$z = 1;

	while ($z < 6) {
		$zz = sql::q1("SELECT * FROM quest WHERE id=" . $z);
		if ($zz["finished"] == 0) {
			$que = 'закончиться';
			$que1 = 'т';
			$que5 = 'т';
		} else {
			$que = 'начнётся';
			$que1 = 'ла';
			$que5 = 'л';
		}

		if ($z == 1) {
			echo " " . tp($zz["time"] - time()) . "Квест по ведьме " . $que . " через : <font id=timer_inp1>300</font> находится на клетке [ " . $zz["lParam"] . " : " . $zz["zParam"] . "], проси" . $que1 . " " . $zz["sParam"] . "<br>";
		}
		if ($z == 2) {
			echo " Турнир №1 " . $que . " через :";
			echo "" . tp(($zz["time"] + 21600) - time()) . "<br>";
		}
		if ($z == 3) {
			echo " Турнир №2 " . $que . " через :";
			echo "" . tp(($zz["time"] + 21600) - time()) . "<br>";
		}
		if ($z == 4) {
			echo " Турнир №3 " . $que . " через :";
			echo "" . tp(($zz["time"] + 21600) - time()) . "<br>";
		}
		if ($z == 5) {
			echo " Квест по рыбаку " . $que . " через : <font id=timer_inp2>150</font> находится на клетке [ " . $zz["lParam"] . " : " . $zz["zParam"] . "], проси" . $que5 . " " . $zz["sParam"] . "<br>";
		}
		$z++;
	}

	######################### Поощрения
	if (@$_GET['uid']) {

		if ($_GET['pooch'] == 1) {
			reporty("*** Удачно поощрили персонажа " . $_GET["pers"] . "! ***");
			$a["image"] = 34;
			$a["params"] = 'Получаемый опыт увеличивается на 50%';
			$a["esttime"] = 259200;
			$a["name"] = 'Благословение Верховного совета';
			$a["special"] = 16;
			light_aura_on($a, $_GET["uid"]);
			say_to_chat("gl", "Накладывает на <font color=red><b>" . $_GET["pers"] . "</b></font> заклинание <b><i>Благословление</i></b>.", 0, '', '*', 0);
		}
		if ($_GET['pooch'] == 2) {
			reporty("*** Удачно наказали персонажа " . $_GET["pers"] . "! ***");
			$a["image"] = 61;
			$a["params"] = 'Получаемый опыт уменьшается на 50%';
			$a["esttime"] = 3600;
			$a["name"] = 'Кара Верховного совета';
			$a["special"] = 15;
			light_aura_on($a, $_GET["uid"]);
			say_to_chat("<font size=2>Верховный совет</font>", "Накладывает на <font color=red><b>" . $_GET["pers"] . "</b></font> заклинание <b><i>Кары</i></b>.", 0, '', '*', 0);
		}
		################# лечим персов

		if ($_GET['lech'] == 1) {
			$tr1 = sql::q1("SELECT * FROM p_auras WHERE uid=" . $_GET["uid"] . " and special>2 and special<6");
			if ($tr1["esttime"] > 0) {
				reporty("*** Удачно вылечили одну из травм персонажа " . $_GET["pers"] . "! ***");
				sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $_GET["uid"] . " and special>2 and special<6 LIMIT 1");
				say_to_chat("a", "Верховный совет вылечил одну из травм <b>" . $_GET["pers"] . "</b>", 0, '', '*', 0);
			} else reporty("*** У персонажа " . $_GET["pers"] . " нет травм! ***");
		}
	}
	########################## Удаление персонажа
	if (@$_GET['person']) {
		if ($_GET['person'] == 'del' and $pers['user'] == 'BETEPAH' and $_POST['prichina'] <> 'отсутствует' and $_POST['prichina'] <> '') {
			reporty("*** Персонаж " . $_GET["who"] . "->  id " . $_GET["uid"] . " удалён! По причине: " . $_POST['prichina'] . " ***");
			sql::q("DELETE FROM users WHERE uid=" . $_GET["uid"]);
			sql::q("DELETE FROM wp WHERE uid=" . $_GET["uid"]);
			say_to_chat("<font size=3>Верховный совет</font>", "Персонаж <font color=red><b>" . $_GET["who"] . "</b></font> удалён из игры по причине: <b><i>" . $_POST['prichina'] . "</i></b>.", 0, '', '*', 0);
		} elseif ($_POST['prichina'] == 'отсутствует' or $_POST['prichina'] == '') reporty("*** Введите причину " . $_POST['prichina'] . " удаления персонажа " . $_GET["who"] . "! ***");
		else reporty("*** Нельзя удалить персонажа " . $_GET["who"] . "! ***");
	}
	#############################################

	echo "<center><b>Поощрение и наказание персонажей:</b></center>";
	$pers_glav = sql::q("SELECT * FROM users ORDER by UID ASC");
	echo "<center><table width=800 class=but><tr>
<td width=20><center><b>UID</b></center></td>
<td width=150><center><b>Персонаж</b></center></td>
<td width=150><center><b>Онлайн</b></center></td>
<td width=130><center><b>Где находится?</b></center></td>
<td width=150><center><b>Имя</b></center></td>
<td width=100><center><b>День рождения?</b></center></td>
</tr>";

	foreach ($pers_glav as $pers_g) {
		//<img src=images/signs/travm.gif title=" проверка">
		///// вывод травм персов
		//$tr1=sql::q1("SELECT * FROM p_auras WHERE uid=".$pers_g["uid"]." and special>2 and special<6");
		$tr = sql::q1("SELECT COUNT(*) as uid FROM `p_auras` WHERE uid=" . $pers_g["uid"] . " and special>2 and special<6");
		if ($tr["uid"] > 0) $tr1 = "<img src=images/signs/travm.gif title='Персонаж имеет " . $tr["uid"] . " травм.'>";
		else $tr1 = "";
		if ($status == 'g' or $status == 'z' or $pers["sign"] == 'c2')
			$onclick = "onclick=\"set_status2('" . $pers_g["user"] . "','" . $pers_g["clan_state"] . "','" . $pers_g["state"] . "'," . $pers_g["clan_tr"] . "," . (($pers_g["uid"] == $pers["uid"]) ? 1 : 0) . ",'" . $status . "'," . (($pers["sign"] == "c2") ? 1 : 0) . "," . $pers_g["uid"] . ")\" style='cursor:pointer'";
		else $onclick = '';
		$i++;
		echo "<tr>
<td><b>" . $pers_g['uid'] . "</b></td>
<td><img src='images/signs/" . $pers_g['sign'] . ".gif' alt='Клан  в должности'><font class=user  " . $onclick . ">" . $pers_g['user'] . "</font>[" . $pers_g['level'] . "]
<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=" . $pers_g['user'] . "','_blank')\" style='cursor:pointer'> 
" . $tr1 . "</td>
<td>";
		if ($pers_g['online'] == 1) echo "<font color=blue><b>Персонаж в игре</b></font>";
		else echo "<font color=red><b>" . time_echo(time() - $pers_g["lastom"]) . "</b></font>";
		echo "</td>
<td><center>" . $pers_g['x'] . " : " . $pers_g['y'] . "</center></td>
<td><center><b>" . $pers_g['name'] . "</b></center></td>
<td><center><b>" . $pers_g['dr'] . "</b></center></td>

</tr>";
	}
	echo "</table></center><br>";
}
##################################################### кланка для не контролерров
else {
	function reporty($txt)
	{
		echo "<br><center class=but2><center class=puns>" . $txt . "</center></center><br>";
	}
	######################### Поощрения
	if (@$_GET['uid']) {

		if ($_GET['pooch'] == 1) {
			reporty("*** Удачно поощрили персонажа " . $_GET["pers"] . "! ***");
			$a["image"] = 34;
			$a["params"] = 'Получаемый опыт увеличивается на 50%';
			$a["esttime"] = 3600;
			$a["name"] = 'Благословение Верховного совета';
			$a["special"] = 16;
			light_aura_on($a, $_GET["uid"]);
			say_to_chat("<font size=3>Верховный совет</font>", "Накладывает на <font color=red><b>" . $_GET["pers"] . "</b></font> заклинание <b><i>Благословление</i></b>.", 0, '', '*', 0);
		}
		if ($_GET['pooch'] == 2) {
			reporty("*** Удачно наказали персонажа " . $_GET["pers"] . "! ***");
			$a["image"] = 61;
			$a["params"] = 'Получаемый опыт уменьшается на 50%';
			$a["esttime"] = 3600;
			$a["name"] = 'Кара Верховного совета';
			$a["special"] = 15;
			light_aura_on($a, $_GET["uid"]);
			say_to_chat("<font size=3>Верховный совет</font>", "Накладывает на <font color=red><b>" . $_GET["pers"] . "</b></font> заклинание <b><i>Кары</i></b>.", 0, '', '*', 0);
		}
		if ($_GET['lech'] == 1) {
			$trr = sql::q1("SELECT * FROM p_auras WHERE uid=" . $_GET["uid"] . "");
			if ($trr['esttime'] > 0) {
				reporty("*** Удачно вылечили все травмы персонажа " . $_GET["pers"] . "! ***");
				sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $_GET["uid"] . " and special>2 and special<6 LIMIT 1");
				say_to_chat("a", "Ведьма Алиса в восторге от великодушия <b>" . $_GET["pers"] . "</b>, ведь он" . $male . " помог" . $la . " ей в осуществлении её нового плана! Она щедро дарит <b>" . $pers["user"] . "</b> " . $exp . " опыта и сундук с сокровищами.", 0, '', '*', 0);
			} else
				reporty("*** У персонажа " . $_GET["pers"] . " нет травм! ***");
		}
	}


	echo "<center><b>Поощрение и наказание персонажей:</b></center>";

	echo "<center><table width=1000 class=but><tr><td width=20><center><b>UID</b></center></td><td width=180><center><b>Персонаж</b></center></td><td width=120><center><b>Онлайн</b></center></td><td width=100><center><b>Где находится?</b></center></td><td width=100><center><b>Имя</b></center></td><td width=100><center><b>День рождения?</b></center></td><td width=100><center><b>Поощрить</b></center></td><td width=100><center><b>Наказать</b></center></td>";
	if ($pers['sklon'] == 'lekari') echo "<td width=80><center><b>Вылечить</b></center></td>";
	echo "</tr>";
	$pers_glav = sql::q("SELECT * FROM users WHERE sign='" . $pers['sign'] . "'");
	foreach ($pers_glav as $pers_g) {

		$i++;
		echo "<tr><td><b>" . $pers_g['uid'] . "</b></td><td><font class=user  " . $onclick . "><img src='images/signs/" . $pers_g['sign'] . ".gif' alt='Клан  в должности'>" . $pers_g['user'] . "[" . $pers_g['level'] . "]<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=" . $pers_g['user'] . "','_blank')\" style='cursor:pointer'></td><td>";
		if ($pers_g['online'] == 1) echo "<font color=blue><b>Персонаж в игре</b></font>";
		else echo "<font color=red><b>" . time_echo(time() - $pers_g["lastom"]) . "</b></font>";
		echo "</td><td><center>" . $pers_g['x'] . " : " . $pers_g['y'] . "</center></td><td><center><b>" . $pers_g['name'] . "</b></center></td><td><center><b>" . $pers_g['dr'] . "</b></center></td><td><center><b><a href=main.php?clan=glava&action=addon&gopers=clan&pooch=1&uid=" . $pers_g['uid'] . "&pers=" . $pers_g['user'] . " class=ma>Поощрить</a></b></center></td><td><center><b><a href=main.php?clan=glava&action=addon&gopers=clan&pooch=2&uid=" . $pers_g['uid'] . "&pers=" . $pers_g['user'] . " class=hp>Наказать</a></b></center></td>";
		if ($pers['sklon'] == 'lekari') echo "<td><center><b><a href=main.php?clan=glava&action=addon&gopers=clan&lech=1&uid=" . $pers_g['uid'] . "&pers=" . $pers_g['user'] . " class=hp>Лечить травму</a></b></center></td>";
		echo "</tr>";
	}
	echo "</table></center><br>";
}
