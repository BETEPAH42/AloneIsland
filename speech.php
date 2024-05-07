<META Content="text/html; Charset=utf-8" Http-Equiv=Content-type>
<META Http-Equiv=Cache-Control Content=No-Cache>
<META Http-Equiv=Pragma Content=No-Cache>
<META Http-Equiv=Expires Content=0>
<LINK href=main.css rel=STYLESHEET type=text/css>
<title>Alone Islands</title>

<body topmargin="15" leftmargin="15" rightmargin="15" bottommargin="15" class=fightlong style="overflow:hidden;">

<?php
require_once 'classes/autoload.php';
include_once 'inc/functions.php';

use Users\Person;
use Quests\EveryDayQuests;

function ykind_stat($i) {
		if ($i > 5) return "Настроен враждебно";
		elseif ($i > 3) return "Презирает вас";
		elseif ($i > 2) return "Не любит вас";
		elseif ($i > 0) return "Недолюбливает вас";
		elseif ($i == 0) return "Относится к вам нейтрально.";
	}

	error_reporting(0);

	if ($_COOKIE["uid"]) {
			$pers = catch_user(intval($_COOKIE["uid"]), $_COOKIE["hashcode"], 1);
	}
	$auth = new Person(intval($_COOKIE["uid"]));

	$id = intval($_GET["id"]);
	$rs = SQL::q1("SELECT * FROM residents WHERE id=" . (int)$id . " AND location='" . $pers["location"] . "' AND online=1;");
	$b = SQL::q1("SELECT level FROM bots WHERE id=" . $rs["id_bot"])['level'] ?? 0;
	$rel = SQL::q1("SELECT * FROM relationship WHERE uid=" . $pers["uid"] . " and rid=" . $rs["id"]);

	if (!$rel) {
		$R = 0;
		SQL::q("INSERT INTO `relationship` (`uid` ,`rid` ,`rel` )VALUES ('" . $auth->uid . "', '" . $rs["id"] . "', '0');");
	} else {
		$R = $rel["rel"];
	}

	$R += $pers["kindness"];
	$_SPEECH = $rs["speechid"];
	$s = [];
	if (isset($_GET["say"])) {
		$s = SQL::q1("SELECT * FROM speech WHERE id_from=" . $pers["speechid"] . " and id=" . intval($_GET["say"]));
		if ($s) {
			if ($s["showcounts"] and !$pers["priveleged"]) {
				if (mtrunc($s["showcounts"] - intval(SQL::q1("SELECT `count` FROM u_speech WHERE uid=" . $pers["uid"] . " and sid=" . $s["id"])['count'])))
					$_SPEECH = $s["id"];
			} else {
				$_SPEECH = $s["id"];
			}
		}
	}

	if (isset($_GET["tsay"])) {
		$sp = SQL::q1("SELECT * FROM speech WHERE id=" . intval($pers["speechid"]));
		$s = SQL::q1("SELECT * FROM speech WHERE id=" . intval($_GET["tsay"]));
		if ($s and $s["id"] == $sp["value"] and $sp["action"] == 1) {
			if ($s["showcounts"] and !$pers["priveleged"]) {
				if (mtrunc($s["showcounts"] - intval(SQL::q1("SELECT `count` FROM u_speech WHERE uid=" . $pers["uid"] . " and sid=" . $s["id"])['count'])))
					$_SPEECH = $s["id"];
			} else {
				$_SPEECH = $s["id"];
			}
		}
	}

	echo "<div style='float:left;height:100%;width:200px;border-right-style: solid; border-right-color: #2B587A; border-right-width:1px;'>
		<div class='but'>
				<div valign=center align=center>
					<b class=timef>" . kind_stat($rs["kindness"]) . "</b>
					<b class=user>" . $rs["name"] . "</b>
					<b class=lvl>[" . $b . "]</b>
					<br>
					<span class=gray>" . $rs["description"] . "</span>
					<img src='images/persons/" . $rs["image"] . ".gif' width='115'>
					<br>
					<span class=about>" . ykind_stat(abs($R)) . "</span>
				</div>
		</div>
	</div>";

	echo "<div style='float:right;height:100%;width:70%;'>
		<table width=100% height=80%>
			<tr>
				<td valign=center>";
	try {
		$q = new EveryDayQuests();
		$quest = $q->getQuestOnLocation($pers['x'], $pers['y']);
	}
	catch (Exception $e) {
		var_dump($e);
	}
	catch (Error $er) {
		var_dump($er);
	}

	if ($pers["cfight"])
		echo "<span class=about>Вы не можете разговаривать в бою.</span>";
	else {
		if (!$_SPEECH) {
			echo "<span class=about>Мне нечего тебе сказать.</span>";
		} else {
			echo "<div style='width:80%'>";
			$sp = SQL::q1("SELECT * FROM speech WHERE id=" . $_SPEECH);
			$prehistory = $sp["prehistory"];
			$text = $sp["text"];
			$text = str_replace("%s", $auth->getNick(), $text);
			$text = str_replace("%l", $auth->getLevel(), $text);
			$text = str_replace("%q", $q->getSParam(), $text);

			if ($R == -7) {
				begin_fight("bot=" . $rs["id_bot"], $auth->getNick(), "Нападение", 80, 100, 1, 0);
			}
			if (empty($s)) {
				$sp['action'] = 0;
			}

			if (($sp["relation"] > 0 and $R > $sp["relation"]) or ($sp["relation"] < 0 and $R < $sp["relation"]) or $sp["relation"] == 0) {

				if ($sp["showcounts"]) {
					$c = SQL::q1("SELECT COUNT(*) as count FROM u_speech WHERE uid=" . $auth->getUid() . " and sid=" . $sp["id"]);
					if ($c['count'])
						SQL::q("UPDATE u_speech SET `count`=`count`+1 WHERE uid=" . $auth->getUid() . " and sid=" . $sp["id"]);
					else
						SQL::q("INSERT INTO `u_speech` (`uid` ,`sid` ,`count` )VALUES ('" . $auth->getUid() . "', '" . $sp["id"] . "', '1');");
				}
				## Делаем действие здесь
				/*
			$atype = '<select name=atype id=atype onchange="atype_ch()">';
			$atype .= '<option value=0 SELECTED>Ничего</option>';
			$atype .= '<option value=1>Перейти на речёвку</option>';
			$atype .= '<option value=2>Закрыть окно общения</option>';
			$atype .= '<option value=3>Выдать квест</option>';
			$atype .= '<option value=4>Написать фразу в чат</option>';
			$atype .= '<option value=5>Начать бой с говорящим</option>';
			$atype .= '<option value=6>Выдать опыта</option>';
			$atype .= '<option value=7>Выдать денег</option>';
			$atype .= '<option value=8>Выдать бриллиантов</option>';
			$atype .= '<option value=9>Выдать пергаментов</option>';
			$atype .= '<option value=10>Вылечить травму</option>';
			$atype .= '<option value=11>Телепортировать</option>';
			$atype .= '</select>';
			*/


				if ($sp["action"] == 1)
					echo "<script>location = 'speech.php?id=" . $id . "&tsay=" . $s["value"] . "';</script>";
				if ($sp["action"] == 2)
					echo "<script>top.FuncyOff();</script>";
				if ($sp["action"] == 4)
					say_to_chat($rs["name"], $sp["value"], 1, $pers["user"], '*');
				if ($sp["action"] == 5) {
					begin_fight("bot=" . $rs["id_bot"], $pers["user"], "Сражение", 80, 2, 1, 0);
					echo "<script>top.FuncyOff();</script>";
				}
				if ($sp["action"] == 6) {
					set_vars("exp=exp+" . intval($sp["value"]), $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> подарил вам " . intval($sp["value"]) . " опыта.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 7) {
					set_vars("money=money+" . intval($sp["value"]), $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> подарил вам " . intval($sp["value"]) . " LN.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 8) {
					set_vars("dmoney=dmoney+" . intval($sp["value"]), $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> подарил вам " . intval($sp["value"]) . " БР.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 9) {
					set_vars("coins=coins+" . intval($sp["value"]), $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> подарил вам " . intval($sp["value"]) . " перг.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 10) {
					$a = sql::q1("SELECT name FROM p_auras WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme() . " LIMIT 1;");
					sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>=3 and special<=5 and esttime>" . tme() . " LIMIT 1;");
					if ($a['name'])
						say_to_chat('a', "<b>" . $rs["name"] . "</b> вылечил вас от <b>" . $a['name'] . "</b>.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 11) {
					list($loc, $x, $y) = explode("|", $sp["value"]);
					set_vars("location='" . $loc . "',x=" . $x . ",y=" . $y, $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> телепортировал вас.", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 12) {
					if (!$auth->weapons->removeWp($q->getSParam()))
					{
						$auth->weapons->removeWpByName($q->getSParam());
					}
					// list($loc, $x, $y) = explode("|", $sp["value"]);
					// set_vars("location='" . $loc . "',x=" . $x . ",y=" . $y, $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> забираем квестовую вещь ".$q->getSParam().".", 1, $pers["user"], '*');
				}
				if ($sp["action"] == 13) {
					// $user->WpUser->removeWpByName($q->getSParam());
					// list($loc, $x, $y) = explode("|", $sp["value"]);
					// set_vars("location='" . $loc . "',x=" . $x . ",y=" . $y, $pers["uid"]);
					say_to_chat('a', "<b>" . $rs["name"] . "</b> выдаем квестовую вещь.", 1, $pers["user"], '*');
				}
				//
				#####

				if ($prehistory)
					echo "<i>" . $prehistory . "</i><br><br>";

				if ($sp["answer"])
					echo "<span class=gray><b>Вы:</b> -" . $sp["answer"] . "</span><br>";
				echo "<span class=about><b>" . $rs["name"] . ":</b> -" . $text . "</span>";

				$sps = SQL::q("SELECT * FROM speech WHERE id_from=" . $sp["id"]);
				$table = '<center><br><br><table border=0 width=80% cellspacing=0 cellspadding=0>';

				foreach ($sps as $s) {

					// добавить условие на текстовку для квестов
					if ($s["showcounts"]) {
						if (!mtrunc($s["showcounts"] - intval(
							SQL::q("SELECT `count` FROM u_speech WHERE uid=" . $pers["uid"] . " and sid=" . $s["id"])['count']))) {
							if (!$pers["priveleged"])
								continue;
						}
						$table .= "<tr><td class=gray valign=center style='height:30px'>[ЗАКОНЧИЛОСЬ]<a class=nt href=speech.php?id=" . $id . "&say=" . $s["id"] . "><img src=images/icons/right.png> &nbsp; <u>" . $s["answer"] . "</u></a></td></tr>";
					} 
					else {
						// функционал по проверке наличия квестового предмета
						if ($s['in_wp'] == 'qwp' && ($auth->weapons->inWpByName($q->getSParam()) || $auth->weapons->inWp($q->getSParam()) ) )
							$table .= "<tr><td class=gray valign=center style='height:30px'><a class=nt href=speech.php?id=" . $id . "&say=" . $s["id"] . "><img src=images/icons/right.png> &nbsp; <u>" . $s["answer"] . "</u></a></td></tr>";
							//прописать изъятие вещи из рюкзака

					}
				}
				$table .= "<tr><td class=gray valign=center style='height:30px'><a class=nt href='javascript:top.FuncyOff();'><img src=images/icons/right.png> &nbsp; <u>Я, пожалуй, пойду...</u></a></td></tr>";
				$table .= '</table></center>';
				echo $table;
				echo "</div>";
			} elseif ($sp["relation"] > 0) {
				echo "Я тебя не уважаю, чтобы разговаривать с тобой на такие темы!";
			} elseif ($sp["relation"] < 0) {
				echo "Ты мне слишком симпатичен, чтобы говорить с тобой об этом.";
			}
			SQL::q("UPDATE relationship SET rel=rel+" . $sp["kindup"] . " WHERE uid=" . $pers["uid"] . " and rid=" . $rs["id"]);
			$pers["kindness"] += $sp["kindup"] / SQL::q1("SELECT COUNT(*) as count FROM `residents`")['count'];
			set_vars("speechid=" . $sp["id"] . ",kindness=" . $pers["kindness"], $pers["uid"]);
		}
	}
	echo "</td>
	</tr>
	</table>
</div>";
	?>
</body>