<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'classes/autoload.php';
include_once 'inc/functions.php';

use Users\Person;
// include ('inc/sendmail.php');

$world = SQL::q1("SELECT weather,weatherchange FROM world");

if ($world["weatherchange"] < tme()) {
	say_to_chat("#W", "#W", 0, '', '*');
}

$auth = new Person(Person::GetIdByLoginPass(addslashes($_POST["user"]),md5($_POST["pass"])));
if (@$_POST["pass"]) {
	$pers = $auth->allDatas;
} else {
	$pers = SQL::q1("SELECT * FROM `users` WHERE `user`='" . addslashes($_POST["user"]) . "' and `pass`='" . addslashes($_POST["passnmd"]) . "'");
}

if (empty($_POST)) {
	$auth = new Person(intval($_COOKIE["uid"]));
	$pers = $auth->allDatas;
	//$pers = SQL::q1("SELECT * FROM `users` WHERE `uid`=" . intval($_COOKIE["uid"]) . " and `pass`='" . addslashes($_COOKIE["hashcode"]) . "'");
}
$err = 1;
if (isset($auth->uid)) {
	$err = 0;
}

if ($pers["flash_pass"] and empty($_POST["spass"])) {
	include("second_password.php");
	exit;
} elseif ($pers["flash_pass"] and $pers["flash_pass"] <> $_POST["spass"]) {
	$err = 1;
}

if (@$pers["block"] <> '')
	$err = 2;

if ($pers["diler"] == 1) {
	$pers["rank"] .= "<pv><diler>";
}
if ($err == 1) {
	$_GET['error'] = "login";
	include("index.php");
	exit;
}

if ($err == 2) {
	$_GET['error'] = "block";
	include("index.php");
	exit;
}

$lt = date("d.m.Y H:i");
setcookie("uid", $pers["uid"]);
setcookie("hashcode", $pers["pass"]);
setcookie("nick", $pers["user"]);
setcookie("options", $pers["options"]);
setcookie("spass", $pers["flash_pass"]);
$design = explode("|", $pers["options"]);


if ($_COOKIE["uid"] != $pers["uid"] and $_COOKIE["uid"] != 0 and intval($pers["uid"])) {
	SQL::q("INSERT INTO `one_comp_logins` (`uid1`,`uid2`,`time`) VALUES (" . intval($_COOKIE["uid"]) . ", " . intval($pers["uid"]) . ", '" . tme() . "');");
}


SQL::q("INSERT INTO `ips_in` ( `uid` , `ip` , `date`) VALUES (" . $pers["uid"] . ",'" . show_ip() . "'," . tme() . ");");

$chlast = SQL::q1("SELECT MAX(id) as max FROM chat")['max'] ?? 0;
SQL::q("UPDATE users SET lastip='" . show_ip() . "', lastvisit='" . $lt . "', lastvisits=" . (tme()) . ", lasto='" . (tme()) . "', online=1, chat_last_id=" . $chlast . " WHERE `uid`='" . $pers['uid'] . "';");
/*
Поздравления о дне рождения
*/
$dr = explode(".", $pers["dr"]);
$drmonth = intval($dr[1]);
$drday = intval($dr[0]);
$year = date("Y");
$DR_congratulate = $pers["DR_congratulate"];

if ($pers["DR_congratulate"] < $year) {
	set_vars("DR_congratulate=0", $_COOKIE['uid']);
	$uvedDR = "Ещё не поздравляли!";
} else
	$uvedDR = tp($pers["DR_congratulate"] - time());
if (mktime(0, 0, 0, $drmonth, $drday, $year) > time())
	$pers["DR_congratulate"] = mktime(0, 0, 0, $drmonth, $drday, $year);
else
	$pers["DR_congratulate"] = mktime(0, 0, 0, $drmonth, $drday, $year + 1);

if ($drday == date("d") and $drmonth == date("m") and ($pers["DR_congratulate"] == 0 or $DR_congratulate < time())) {
	say_to_chat('s', "Администрация поздравляет <b>" . $pers["user"] . "</b> с днём рождения! От лица всех игроков мы хотим вам пожелать ярких успехов, великих побед, море достатка и жизни без бед!", 0, '', '*', 0);
	say_to_chat('s', "Персонаж <b>" . $pers["user"] . "</b> получает " . ($pers["level"] * 20) . " LN в честь дня рождения!", 0, '', '*', 0);
	set_vars("money=money+level*20", "DR_congratulate={$year}");
}
//if ($pers["DR_congratulate"]<>$DR_congratulate) set_vars("DR_congratulate=".$pers["DR_congratulate"]."");

echo "<!DOCTYPE html><html><head><title>Одинокие земли[" . $pers["user"] . "]</title>
<meta content='text/html; charset=utf-8' Http-Equiv=Content-type>
<link rel='favicon' href='images/icon.ico'>
<link rel='shortcut icon' href='images/pict.png'>
<link href='css/main.css' rel=stylesheet type=text/css></head>
<body scroll='no'>
<script src='js/cookie.js'></script>
<script SRC='js/jquery.js'></script>
<script src='js/game.js?2'></script>
<script src='js/fight.js'></script>
<script src='js/pers.js'></script><script>";
$today = getdate();
echo "let ctip = " . $pers["ctip"] . ";";

if ($pers["lasto"] == 0) {
	say_to_chat("a", "<center class=return_win><b>Приветствие!</b> Мы рады видеть вас на просторах нашего мира! <hr> Вы можете прочитать помощь на странице вашего персонажа.</center>", 1, $pers["user"], '*', 0);
	say_to_chat("a", "Родился малыш! <b>" . $pers["user"] . "</b> мы приветствуем тебя и желаем длинного и увлекательного пути!", 0, '', '*', 0);
	if ($pers["referal_uid"]) {
		$p = SQL::q("SELECT uid,user FROM users WHERE uid=" . $pers["referal_uid"] . "");
		SQL::q("UPDATE users SET money=money+10,referal_counter=referal_counter+1,refc=refc+1,coins=coins+1 WHERE uid=" . $p["uid"] . "");
		say_to_chat("s", "Вы привели в игру персонажа <font class=user onclick=\"top.say_private(\'" . $pers["user"] . "\')\">" . $pers["user"] . "</font>! Вам на счёт зачислено <b>10 LN и 1 Пергамент</b>", 1, $p["user"], '*', 0);
	}
	$_v = SQL::q1("SELECT id FROM weapons WHERE tlevel=0 and where_buy=0 LIMIT 0,1;");
	if ($_v) {
		insert_wp($_v["id"], $pers["uid"]);
	}
	$_v = SQL::q1("SELECT id FROM weapons WHERE id=14539");
	if ($_v) {
		insert_wp($_v["id"], $pers["uid"]);
	}
	# 
	$_ECHO_OFF = true;
	foreach (SQL::q("SELECT id FROM blasts WHERE learnall=1") as $bl)
		insert_blast($bl["id"], $pers["uid"]);
	foreach (SQL::q("SELECT id FROM auras WHERE learnall=1") as $bl)
		insert_aura($bl["id"], $pers["uid"]);
}

if (date("H") > 6 and date("H") < 22) $night = 0;
else $night = 1;
?>
view_frames(<?= $night; ?>);
</script>
<NOSCRIPT>
	<b>Внимание!</b><br>Нормальная работа игры возможна только под управлением браузера <b>Internet Explorer версии 5.5 и выше</b> (<a href=http://www.microsoft.com/windows/ie_intl/ru/default.mspx target=_blank>ссылка</a>). При этом у Вас должна быть включена поддержка файлов cookies и Java-скриптов. Проверьте Ваши настройки.
</NOSCRIPT>

</BODY>

</HTML>
<?php
if ($night == 0) {

	$configs = SQL::q1("SELECT * FROM configs LIMIT 0,1");
	/*
if($configs["last_dump"]<tme()){
say_to_chat ("a","Внимание! Игра приостановит свою работу на малый срок. Оптимизация и сохранение параметров. Пожалуйста не покидайте наш мир, скоро всё нормализуется...Не боись братва это реально ))",0,0,'*',0);
}
*/

	if (date("d") <> date("d", $configs["last_rating_update"])) {
		include("ratings/rating.php");
		SQL::q("UPDATE configs SET last_rating_update=" . tme() . "");
	}

	//send_mail('SlaiderM@gmail.com', 'Здраствуйте! Вы слишком давно не заходили в игру. Сейчас вас ожидают интересные битвы, новая магия и вещи. А так же новые территории! Заходите и проведите время с удовольствием! <hr> <b>Никнэйм: <i>***</i></b> <br> <b>Пароль: <i>***</i></b><hr><center><a href=http://aloneislands.ru><h2>AloneIslands.Ru</h2></a></center>', 'robot@aloneislands.ru', 0, 0)

	if ($configs["last_dump"] < tme() + 10) {
		SQL::q("UPDATE configs SET last_dump=" . ($configs["last_dump"] + 172800) . "");
		$pass = "12345";
		$bl = SQL::q("SELECT * FROM users WHERE lastom<(" . tme() . "-(level+1)*1209600) and lastom>0 and block<>'' LIMIT 0,1;");
		/*	if ($bl)
	{
		SQL::q("DELETE FROM users WHERE uid=".$bl["uid"]);
		SQL::q("DELETE FROM chars WHERE uid=".$bl["uid"]);
		SQL::q("DELETE FROM wp WHERE uidp=".$bl["uid"]);
		SQL::q("DELETE FROM p_auras WHERE uid=".$bl["uid"]);
		SQL::q("DELETE FROM u_blasts WHERE uidp=".$bl["uid"]);
		SQL::q("DELETE FROM u_auras WHERE uidp=".$bl["uid"]);
		SQL::q("DELETE FROM u_blasts WHERE uidp=".$bl["uid"]);
		SQL::q("DELETE FROM u_special_dmg WHERE uid=".$bl["uid"]);
		SQL::q("DELETE FROM bank_account WHERE uid=".$bl["uid"]);
		SQL::q("DELETE FROM presents_gived WHERE uid=".$bl["uid"]);
	}
	*/
		SQL::q("DELETE FROM wp WHERE uidp=0");
		SQL::q("OPTIMIZE TABLE `users`");
		SQL::q("OPTIMIZE TABLE `wp`");
		SQL::q("OPTIMIZE TABLE `chars`");
		SQL::q("OPTIMIZE TABLE `mine`");
		SQL::q("OPTIMIZE TABLE `bots_cell`");
		SQL::q("OPTIMIZE TABLE `herbals_cell`");
		SQL::q("OPTIMIZE TABLE `bots`");
		SQL::q("OPTIMIZE TABLE `weapons`");
		SQL::q("OPTIMIZE TABLE `chat`");
		SQL::q("TRUNCATE TABLE `bots_battle`");
		SQL::q("TRUNCATE TABLE `chat`");
		SQL::q("TRUNCATE TABLE `salings`");
		SQL::q("UPDATE users SET chat_last_id=0");
		//	include("sql_dump.php");
	}
}
/*
SQL::q("START TRANSACTION;");
$luser = SQL::q1("SELECT * FROM users WHERE lastom<(".tme()."-1814400) and lastom>0 and block='' and action<>-1 LIMIT 0,1;");
if ($luser["user"] and eregi("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$", $luser["email"]))
{
	$luser["pass"] = rand(10000,99999);
	send_mail($luser["email"], 'Здраствуйте! Вы слишком давно не заходили в игру. Сейчас вас ожидают интересные битвы, новая магия и вещи. А так же новые территории! Заходите и проведите время с удовольствием! <hr> <b>Никнэйм: <i>'.$luser["user"].'</i></b> <br> <b>Пароль: <i>'.$luser["pass"].'</i></b><hr><center><a href=http://aloneislands.ru><h2>AloneIslands.Ru</h2></a><br>не нужно отвечать на это письмо</center>', 'robot@aloneislands.ru', 0, 0);
	SQL::q("UPDATE users SET pass='".md5($luser["pass"])."',action=-1 WHERE uid=".$luser["uid"]."");
	if ($admin = sql::q1("SELECT user FROM users WHERE uid<6 and online=1 LIMIT 0,1;"))
		say_to_chat ("s","Отослано письмо на <b>".$luser["email"]."</b>, для <b>".$luser["user"]."</b> <b>Пароль: <i>".$luser["pass"]."</i></b>. Время незахода в игру: ".tp(tme()-$luser["lastom"])." ",1,$admin,'*',0);
}
elseif ($luser["user"])
{
	SQL::q("UPDATE users SET action=-1 WHERE uid=".$luser["uid"]."");
}
SQL::q("COMMIT;");
*/


########### Новый год
if ($pers["new_year"] == 1 and date("d") < 30 and date("m") == 12) {
	say_to_chat("a", "Администрация нашей замечательной онлайн игры поздравляет Вас с Новым Годом!", 1, $pers["user"], '*', 0);
	say_to_chat("a", "Желаем Вам творческих успехов, процветания и уверенности в завтрашнем дне. Крепкого здоровья Вам и Вашим близким!", 1, $pers["user"], '*', 0);
	say_to_chat("a", "Замечательного настроения и исполнения самых заветных желаний в Новогоднюю ночь!", 1, $pers["user"], '*', 0);
	say_to_chat("a", "Мы дарим вам обнуление мирных умений и обнуление всех параметров, в знак признательности вашего выбора именно нашей онлайн игры!", 1, $pers["user"], '*', 0);
	say_to_chat("a", "В новом году вас ждёт ещё больше новых и ярких изменений, таких как введение интересных квестов, историй внутри игры и новых профессий.", 1, $pers["user"], '*', 0);
	if ($pers["level"] > 0) {
		say_to_chat("a", "Вы обнаружили мощь предков под ёлкой.", 1, $pers["user"], '*', 0);
		set_vars("new_year=0", $pers["uid"]);
		//,zeroing=zeroing+1,skill_zeroing=skill_zeroing+1
		$v = SQL::q1("SELECT id,name FROM weapons WHERE id='1095'");
		$id = insert_wp($v["id"], $pers["uid"]);
		SQL::q("UPDATE wp SET where_buy=2,timeout=" . (tme() + 7 * 84600) . ",dprice=0 WHERE id=" . $id . "");
	}
}
echo '<div id="outnews">
  <img src="feedback.png" alt="Новости">
  <div id="in_outnews">';
// $n=SQL::q();
foreach (SQL::q("SELECT * FROM news") as $n_s) {
	echo "<hr><center>{$n_s["date"]}-{$n_s["time"]}</center><hr><font size=3>{$n_s["text"]}</font><br><p align='right'><b>{$n_s["title"]}</b></p><hr>";
}
echo '</div></div>';
?>