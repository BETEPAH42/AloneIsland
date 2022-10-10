<?php
//error_reporting(0);
require_once 'classes/sql.php';
include_once 'inc/functions.php';
?>

<script src="js/ch.js?7"></script>
<script>
	<?php
	$server_state = tme() + microtime();
	if (isset($_POST["message"])) {
		$_POST["type"] = intval($_POST["type"]);
		$_POST["message"] = trim($_POST["message"]);
		if ($_POST["type"] <> 1 and $_POST["type"] <> 3) $_POST["type"] = 2;
		$_POST["message"] = str_replace("\\", "", $_POST["message"]);
		$_POST["message"] = str_replace(".х", "//", $_POST["message"]);
		$_POST["message"] = str_replace("/[", "//", $_POST["message"]);
		$_POST["message"] = str_replace("•", ".", $_POST["message"]);
		$m = $_POST["message"];
		$i = strlen($m) - 1;
		while ($m[$i] <> '|' and $i > 0) $i--;
		if ($i > 0) $_POST["message"] = substr($m, $i + 1, strlen($m) - $i);
		$_POST["towho"] = substr($m, 0, $i) . "|";
		if ($_POST["towho"] == "|") $_POST["towho"] = "";
		unset($m);
		unset($i);
		if ($_POST["ttype"] == "priv") $_POST["priv"] = 1;
		else $_POST["priv"] = 0;
	}

	$info = 0;
	$uid = intval($_COOKIE["uid"]);
	$opt = explode("|", $_COOKIE["options"]);

	//SQL::q("SELECT COALESCE(GET_LOCK('".intval($_COOKIE["uid"])."', 60));");


	##############################Боты
	if (tme() % 5 == 0)
		include("bots/attack.php");
	###############################

	$pers = SQL::q1("SELECT * FROM `users`	WHERE `uid`= " . $uid . " LIMIT 0,1;");

	##
	if ($pers["location"] <> "cherch")
		$pers["location"] = '--';
	else
		$_POST["priv"] = 0;
	##
	$a_m = $pers["a_m"];
	$flood = $pers["flood"];
	$chcolor = $opt[5];
	if ($pers["block"] or $pers["pass"] <> $_COOKIE["hashcode"] or !$pers["user"]) exit;
	if ($pers["invisible"] < tme())
		$online = '`online`=1 ,';
	else
		$online = '';
	if ($pers["diler"]) $pers["rank"] .= "<diler><molch><pv><prison><block><w_pom><b_info><punishment>";


	//Добавляем сообщение
	if (@$_POST["message"] and $pers["silence"] <= tme()) {
		// РВC
		$rvs = 0;
		$m = $_POST["message"];
		if (is_rvs($m . " " . $_POST["towho"])) {
			say_to_chat("a", 'Персонаж <b>' . $pers["user"] . '</b> замолчал на 2 минуты. Подозрение на РВС.(<b>World Spawn</b>)', 0, '', '*');
			$a["image"] = 'molch';
			$a["params"] = '';
			$a["esttime"] = 120;
			$a["name"] = 'Заклинание молчания';
			$a["special"] = 1;
			light_aura_on($a, $pers["uid"]);
			SQL::q("UPDATE `users` SET silence=" . (tme() + $a["esttime"]) . " WHERE `uid`=" . $pers["uid"] . "");
			$flood = 0;
		} elseif (is_mat($m)) {
			say_to_chat("a", 'Персонаж <b>' . $pers["user"] . '</b> замолчал на 10 минут. Подозрение на мат.(<b>World Spawn</b>)', 0, '', '*');
			$a["image"] = 'molch';
			$a["params"] = '';
			$a["esttime"] = 600;
			$a["name"] = 'Заклинание молчания';
			$a["special"] = 1;
			light_aura_on($a, $pers["uid"]);
			SQL::q("UPDATE `users` SET silence=" . (tme() + $a["esttime"]) . " WHERE `uid`=" . $pers["uid"] . "");
			$flood = 0;
		} elseif (is_rkp($m)) {
			say_to_chat("a", 'Персонаж <b>' . $pers["user"] . '</b> замолчал на 10 минут. Подозрение на РКП.(<b>World Spawn</b>)', 0, '', '*');
			$a["image"] = 'molch';
			$a["params"] = '';
			$a["esttime"] = 600;
			$a["name"] = 'Заклинание молчания';
			$a["special"] = 1;
			light_aura_on($a, $pers["uid"]);
			SQL::q("UPDATE `users` SET silence=" . (tme() + $a["esttime"]) . " WHERE `uid`=" . $pers["uid"] . "");
			$flood = 0;
		} else {
			////////////////////////////////////
			if ($_POST["type"] <> 3) {
				if ((tme()) < ($pers["lasto"] + 2))
					$flood++;
				else
					$flood = 0;
				if ($m[0] == '%') {
					if ($m[1] == 'u' and $pers["diler"])
						$m = "<u>" . substr($m, 2, strlen($m)) . "</u>";
					if (($m[1] == 'b' or $m[1] == 'и') and $pers["diler"])
						$m = "<b>" . substr($m, 2, strlen($m)) . "</b>";
					if ($m[1] == 'i' and $pers["diler"])
						$m = "<i>" . substr($m, 2, strlen($m)) . "</i>";
					if ($m[1] == 'h' and $pers["diler"])
						$m = "<h3>" . substr($m, 2, strlen($m)) . "</h3>";
					if (($m[1] == 'g' or $m[1] == 'п') and $pers["diler"])
						$m = "<h2>" . substr($m, 2, strlen($m)) . "</h2>";
				}
				$priv = 0;
				if (@$_POST["ttype"] == "priv")
					$priv = 1;
				if (empty($_POST["towho"]))
					$towho = "";
				else
					$towho = $_POST["towho"];
				$lt = date("H:i:s");
				if (empty($towho))
					$priv = 0;
				if ($chcolor <> '')
					$color = str_replace("#", "", $chcolor);
				else
					$color = "000000";
				if ($priv == 0 and $pers["invisible"] > tme()) {
					$pers["user"] = 'n=' . $pers["user"];
					$color = "000000";
				}
				if ($_POST['ttype'] == "clan") {
					echo "top.frames['ch_buttons'].document.querySelector('input[name=\"message\"]').value = '';";
					$clan = $pers["sign"];
					SQL::q("INSERT INTO `chat` (`id`,`user`,`towho`,`private`,`location`,`message`,`time`,`telepat`,`clan`,`color`,`type`) VALUES (0,'" . $pers["user"] . "','" . $towho . "' , '" . $priv . "', '" . $pers["location"] . "' , '" . $m . "' , '" . $lt . "','" . $telepat . "','" . $clan . "','" . $color . "'," . $_POST["type"] . ");");
				} else {
					echo "top.frames['ch_buttons'].document.querySelector('input[name=\"message\"]').value = '';";
					SQL::q("INSERT INTO `chat` (`id`,`user`,`towho`,`private`,`location`,`message`,`time`,`telepat`,`color`,`type`) VALUES (0,'" . $pers["user"] . "','" . $towho . "','" . $priv . "', '" . $pers["location"] . "','" . $m . "','" . $lt . "','" . $telepat . "','" . $color . "'," . $_POST["type"] . ");");
				}
			} else {
				$user = $pers["user"];
				if ($pers["invisible"] > tme())
					$user = 'Невидимка';
				SQL::q("INSERT INTO `fight_log` ( `time` , `log` , `cfight` , `turn` ) VALUES ( '" . date("H:i") . "', '" . $user . " : " . addslashes($m) . "', '" . $pers["cfight"] . "', '" . round((time() + microtime()), 2) . "' );");
			}
			echo "top.clearer = 1;";
		}
	} elseif ($pers["silence"] > tme())
		echo "top.clearer = 1;";

	//Вывод сообщений...

	if ($pers["sign"] <> 's')
		$res = SQL::q("SELECT * FROM `chat` WHERE (`id`>" . $pers["chat_last_id"] . ") and (location='" . $pers["location"] . "' or `user`='s' or `telepat`='1' or `clan`='" . $pers["sign"] . "' or location='*')");
	else
		$res = SQL::q("SELECT * FROM `chat` WHERE `id`>" . $pers["chat_last_id"] . " DESC");



	$cfgs = SQL::q1("SELECT a_message, m_frequency FROM configs");
	if ($a_m < time() and date("i") % $cfgs['m_frequency'] == 0) {
		$info = 1;
		$a_m = time() + 60;
		$tx["time"] = date("H:i:s");
		$tx["user"] = 'a';
		$tx["message"] = "" . $cfgs['a_message'] . "";
		$tx["color"] = '000000';
	}
	unset($cfgs);

	$ignore = '';
	foreach (SQL::q("SELECT nick FROM ignor WHERE uid=" . $pers["uid"] . "") as $ig)
		$ignore .= '<' . $ig["nick"] . '>';

	/*
if ((50-$pers["level"])>rand(1,500))
{
	$tip = sql::q1("SELECT id,title,text FROM tips WHERE maxlevel>".$pers["level"]." ORDER BY RAND()");
	$view = sql::q1("SELECT uid FROM no_tips WHERE uid=".$pers["uid"]." and tip_id=".$tip["id"]." ");
	if ($tip and !$view)
	echo "show_tip('".$tip["title"]."','".$tip["text"]."',".intval($tip["id"]).");";
}
 */

	$s = '';
	foreach ($res as $txt) {

		if (substr_count($ignore, '<' . $txt["user"] . '>'))
			continue;
		if ($pers["chat_last_id"] < $txt["id"])
			$pers["chat_last_id"] = $txt["id"];
		$k = 0;
		if (substr_count($pers["rank"], "<pv>") or $pers["sign"] == 'c1');
		elseif (substr_count($txt["user"], "n="))
			$txt["user"] = 'n';
		if (empty($txt) and $info == 1) {
			$info = 0;
			$txt = $tx;
		}

		if ($txt["time"] == '') $txt["time"] = date("H:i:s");

		if ($txt["private"] == 1 and ($txt["user"] == $pers["user"] or substr_count("|" . $txt["towho"] . "|", "|" . $pers["user"] . "|") or $pers["sign"] == 's'))
			$k = 1;
		if ($txt["private"] <> 1)
			$k = 1;
		if ($txt["clan"] == $pers["sign"] and $txt["clan"] <> '')
			$txt["private"] = 2;
		if ($txt["clan"] <> $pers["sign"] and $txt["clan"] <> '' and $txt["clan"] <> 'none')
			$k = 0;
		//if ($pers["uid"]==5 or $pers["uid"]==955) $k=1;

		// Системные сообщения

		if ($txt["private"] == 1 and ($txt["towho"] == $pers["user"] and $txt["user"] == "s")) {
			$m = explode("|", $txt["message"]);
			$k = 1;
			$m[1] = htmlspecialchars($m[1]);
			if (substr_count($m[0], "saling#")) {
				echo "top.Funcy('salingFORM.php?id=" . str_replace("saling#", "", $m[0]) . "');"; // - продажа
				$k = 0;
			} elseif ($m[1])
				$txt["message"] = "Персонаж <b>" . $m[0] . "</b> передал вам <b>" . $m[1] . "</b> .";			// - передача
		}
		if ($txt["user"] == '#W') {
			echo "top.frames['ch_list'].location='weather.php';";
			$k = 0;
		}
		// КОНЕЦ системным сообщениям
		$txt["message"] = str_replace('"', "", $txt["message"]);
		$txt["message"] = str_replace("'", "", $txt["message"]);

		if ($k == 1) {
			$s .= "'" . $txt["time"] . "•" . $txt["user"] . "•" . $txt["towho"] . "•" . $txt["message"] . "•" . $txt["private"] . "•" . $txt["color"] . "•" . $txt["type"] . "•'";
		}
	}

	if ($pers["curstate"] == 4) {
		foreach (SQL::q("SELECT * FROM fight_log WHERE cfight=" . $pers["cfight"] . " and turn>" . $pers["lasto"] . "") as $txt)
			$s .= "'" . $txt["time"] . "•••" . addslashes($txt["log"]) . "•0•222222•3•',";
	}
	$zz = json_encode($txt, true);

	/*
if($pers["uid"]==5)
{
	$s.= "'•Сервер••Время работы: ".round(time()+microtime()-$server_state,3)."•0•265•0•',";
}
*/
	?>
	// let t = {};
	async function getMsgChat() {
		let respons = await fetch("showChatApi.php?show=all");
		let res = await respons.json();

		if (res.status)
			res.messages.forEach(element => {
				// описываем логику чата здесь
				let smile = 0;
				let q = 0;
				let PRIV_COUNTER = 0;
				let msg = element.message;
				let nick = "";
				let type = 'time';
				let inviz = '';
				let uninviz = '';

				function str_replace2(str, replacement, substr) {
					var w = str.split(replacement);
					return w.join(substr);
				}

				msg = str_replace2(msg, '=)', '//001');
				msg = str_replace2(msg, ':)', '//001');
				msg = str_replace2(msg, ':-)', '//001');
				msg = str_replace2(msg, '=(', '//002');
				msg = str_replace2(msg, ':(', '//002');
				msg = str_replace2(msg, ':-(', '//002');
				msg = str_replace2(msg, ';-)', '//003');
				msg = str_replace2(msg, ';)', '//003');
				msg = str_replace2(msg, ':D', '//004');
				msg = str_replace2(msg, ':-D', '//004');
				msg = str_replace2(msg, ':d', '//004');
				msg = str_replace2(msg, '=d', '//004');
				// msg = str_replace2(msg, '=Гў', '//004');
				msg = str_replace2(msg, '=0', '//005');
				msg = str_replace2(msg, ':-0', '//005');
				msg = str_replace2(msg, ':0', '//005');
				msg = str_replace2(msg, '=[', '//010');
				msg = str_replace2(msg, ':[', '//010');
				msg = str_replace2(msg, ':-[', '//010');
				msg = str_replace2(msg, '=P', '//008');
				msg = str_replace2(msg, '=p', '//008');
				msg = str_replace2(msg, '=Г°', '//008');
				// msg = str_replace2(msg, '=Гђ', '//008');
				msg = str_replace2(msg, '+)', '//118');
				// msg = str_replace2(' ' + msg + ' ', ' ГµГ Г© ', '//046');
				// console.log(msg.indexOf('//'));
				// console.log(msg.indexOf('//') > -1);
				while (msg.indexOf('//') > -1 && q < 3) {
					if (msg.indexOf('//') > -1)
						smile = msg.substr(msg.indexOf('//') + 2, 3);

					if (Number(smile) < 268) {
						// console.log(smile);
						msg = str_replace2(msg, '//' + smile, '<img src=/images/smiles/smile_' + smile + '.gif onclick="top.sm_ins(\'' + smile + '\')">');
						// console.log(msg);
					}
					q++;
				}
				let att = 0;
				let s = '';
				if (element.user == 'w') {
					s += '<font class=user style="color:#990000">Смотрители сообщают.</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'a') {
					s += '<font class=al>Администрация:</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == '^') {
					s += '<span class=red>Гильдия наставников.</span> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 's') {
					s += '<font class=user style="color:#990000">Системная информация.</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'f') {
					s += '<font class=user style="color:#990000">Что-то связано с профессией рыбак (Г«Г Г±ГЄГ®ГўГ®, Г·ГІГ®ГЎ Г°Г»ГЎГі Г­ГҐ Г°Г Г±ГЇГіГЈГ ГІГј)</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'f1') {
					s += '<font class=user style="color:#990000">Рыбак:</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'ft') {
					s += '<font class=user style="color:#990000">Турнир рыбаков::</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'gl') {
					s += '<font class=al>Глава клана:</font> ' + msg + '<br>';
					att = 1;
				}
				if (element.user == 'd') {
					s += `<font class=user style="color:green">Дровосек:</font> ${msg}<br>`;
					att = 1;
				}
				if (element.user == 'o') {
					s += '<font class=user style="color:#990000">Квест:</font> ' + msg + '<br>';
					att = 1;
				}
				//ГЄГ Г°ГІГЁГ­ГЄГЁ Г°Г ГЎГ®ГІГ ГѕГІ Гў Г·Г ГІГҐ тоже непонятно что это
				if (element.user == 'k') {
					s += '<img src=images/signs/c001.gif>ГЄГўГў.</font> ' + msg + '<br>';
					att = 1;
				} else {
					// по завершению убрать
					// s += `<font class=time>${element.time}</font> <font class=user style="color:#${element.color}">${element.user}:</font> ${msg} *<br>`;
				}

				if (att == 0) {
					if (element.user.trim == 'n' && top.ChatFyo == 0) {
						s = '<font class=time>' + element.time + '</font> &nbsp;<i>невидимка</i>';
						if (element.towho != '') {
							s += ' для ';
							towho = element.split('|');
							for (j = 0; j < towho.length - 1; j++) {
								s += '<font class=user onclick="top.say_private(\'' + towho[j] + '\',' + prv + ')">' +
									towho[j] + '</font>';
								if (j < (towho.length - 2)) s += ',';
							}
						}
						s = s + ':' + msg + '<br>';
					} else {
						prv = 0;
						if (element.user == nick || (' |' + element.towho + '|').indexOf('|' + nick + '|') > -1)
							type = 'toyou';
						element.towho = ' ' + element.towho;
						element.towho = element.towho.substr(1, element.towho.length - 1);
						if (element.private == 2 && type == 'toyou') {
							type = 'clan_you';
							prv = 2;
						} else if (element.private == 2) {
							type = 'clan';
							prv = 2;
						}
						if (element.private == 1) {
							type = 'priv';
							prv = 1;
							if (element.user != nick) PRIV_COUNTER++;
						}
						if ((top.ChatFyo == 1 && type != 'time') || top.ChatFyo == 0) {
							s += '<font class=' + type + '>' + element.time + '</font> ' + inviz + '<font class=user onclick="top.say_private(\'' + element.user + '\',' + prv + ')">' + element.user + '</font>' + uninviz + '';
							if (element.towho != '') {
								s += ' пишет ';
								towho = element.towho.split('|');
								for (j = 0; j < towho.length - 1; j++) {
									s += '<font class=user onclick="top.say_private(\'' + towho[j] + '\',' + prv + ')">' +
										towho[j] + '</font>';
									if (j < (towho.length - 2)) s += ',';
								}
							}
							if (element.user != '') s += ':';
							s += ' <font color="#' + element.color + '">' + msg + '</font><br>';
						}
					}
				}
				if (s != '')
					s = "<div>" + s + "</div>";
				top.frames['chmain'].document.getElementById('c1').innerHTML += s;
				top.frames['chmain'].scroll_chat();
			});

	}
	getMsgChat();

	<?php
	// echo "let t = new Array (" . substr($s, 0, strlen($s) - 1) . ");\n";

	unset($s);
	unset($res);
	unset($txt);

	if (@$_POST["message"]) {
		if ($uid == 5 and strpos(" " . $_POST["message"], "cvar") > 0) {
			$m = str_replace('cvar ', '', $_POST["message"]);
			$m = explode(" ", $m);
			for ($i = 2; $i < count($m); $i++) $m[1] .= " " . $m[$i];
			if (SQL::q("UPDATE configs SET `" . $m[0] . "`='" . $m[1] . "'"))
				echo "alert('Внимание! cvar \"$m[0]\" установлен на значение \"$m[1]\"');";
			else
				echo "alert('Внимание! cvar \"$m[0]\" не удалось установить значение \"$m[1]\"');";
		}
	}

	if ($pers["refr"] == 1) echo "top.re_up_ref();";
	if ($flood > 4 and $pers["silence"] <= tme()) {
		say_to_chat("a", 'Персонаж <b>' . $pers["user"] . '</b> замолчал на 15 минут. Флуд.(<b>World Spawn</b>)', 0, '', '*');
		$a["image"] = 'molch';
		$a["params"] = '';
		$a["esttime"] = 900;
		$a["name"] = 'Заклинание молчания';
		$a["special"] = 1;
		light_aura_on($a, $pers["uid"]);
		SQL::q("UPDATE `users` SET silence=" . (tme() + $a["esttime"]) . " WHERE `uid`=" . $pers["uid"] . "");
		$flood = 0;
	}

	if ($a_m <> $pers["a_m"] or $flood <> $pers["flood"])
		SQL::q("UPDATE users SET a_m='" . $a_m . "',flood='" . $flood . "' WHERE uid='" . $uid . "'");

	function is_mat($m)
	{
		global $pers;
		$m = " " . strtolower(trim($m)) . " ";
		$a = explode(" ", $m);
		foreach ($a as $m) {
			if ($m) {
				$m = " " . $m . " ";
				if ((substr_count($m, " бля") or
						substr_count($m, "бля ") or
						substr_count($m, " пизд") or
						substr_count($m, "fuck") or
						substr_count($m, "сука") or
						substr_count($m, "хуё") or
						substr_count($m, "хуе") or
						substr_count($m, " еба") or
						substr_count($m, " ёба") or
						substr_count($m, " бляд") or
						substr_count($m, " блят") or
						(substr_count($m, "хуй") and !substr_count($m, "страх"))
					)
					and !substr_count($pers["rank"], "<pv>")
					and $pers["sign"] != 'c1'
				) return true;
			}
		}
		return false;
	}

	function is_rvs($m)
	{
		global $pers;
		$m = strtolower(trim($m));
		$m = str_replace("/", "", $m);
		$a = explode(" ", $m);
		foreach ($a as $m) {
			$m = " " . $m . " ";
			if ((substr_count($m, "http:")
					or substr_count($m, ".com ")
					or substr_count($m, ".ru ")
					or substr_count($m, ".org ")
					or substr_count($m, ".net ")
					or substr_count($m, ".su "))
				and !substr_count($m, "Администрация")
				and !substr_count($pers["rank"], "<pv>")
				and $pers["sign"] != 'c1'
			) return true;
		}
		return false;
	}

	function is_rkp($m)
	{
		global $pers;
		$m = strtolower(trim($m));
		if ((substr_count($m, "escilon")
				or substr_count($m, "chaosroad")
				or substr_count($m, "neverlands")
				or substr_count($m, "ereality")
				or substr_count($m, "lastworlds")
				or substr_count($m, "dwar"))
			and !substr_count($m, "aАдминистрация")
			and !substr_count($pers["rank"], "<pv>")
			and $pers["sign"] != 'c1'
		) return true;
		return false;
	}
	SQL::q("UPDATE `users` SET online=1, `lasto`='" . (tme()) . "' , chat_last_id = " . $pers["chat_last_id"] . " WHERE `uid`='" . $uid . "';");
	// echo "edit_msg(";
	// echo round(time() + microtime() - $server_state, 3);
	// if (@$_GET["timer"]) echo date(",H,i,s");
	// echo ");\n";
	// echo "console.log(t)";
	?>
</script>