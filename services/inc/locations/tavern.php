<?
if (@$_GET["quest_get"] == 1) :
	SQL::q("UPDATE users SET  quest1=1 where uid='" . $pers["uid"] . "'");
	say_to_chat("s", 'Квест <b>&laquo;Потерянные Орехи Судьбы&raquo;</b> начался!', "1", $pers["user"], $pers["location"], date("H:i:s"));
elseif (@$_GET["quest_get"] == 2) :
	if ($pers["n_quest1"] == 10) {
		say_to_chat("s", 'Квест <b>&laquo;Потерянные Орехи Судьбы&raquo;</b> выполнен !', "1", $pers["user"], $pers["location"], date("H:i:s"));
		say_to_chat("s", 'За прохождение квеста <b>&laquo;Потерянные Орехи Судьбы&raquo;</b> вы получаете <b>25 000 опыта</b>, <b>1 000 LM</b>, <b>10 Бр.&laquo;Свиток "Мощь предков"&raquo;</b> !', "1", $pers["user"], $pers["location"], date("H:i:s"));
		SQL::q("UPDATE users SET quest1 = 2, exp=exp+25000, money=money+1000, dmoney=dmoney+10 where user='" . $pers["user"] . "'");
		insert_wp(10951, $pers["uid"]);
		SQL::q("DELETE FROM wp WHERE user='" . $pers["user"] . "' AND id_in_w=11111 LIMIT 10");
	}
endif;

$chp2 = $pers['chp'];
$maxhp = $pers['hp'];
?>
<center>
	<table cellpadding=0 cellspacing=0 border=0 align=center width="90%">
		<tr>
			<td width="500" valign="top"><img border='0' src="images/locations/taverna.jpg"></td>
			<td valign="top">
				<br><br><br><br>
				<a href="main.php?com=1">
					<font size=3 color=red><b>Барная стойка</b></font>
				</a><br>
				<? if ($pers["quest1"] == 0 or $pers["quest1"] == 1) : ?>
					<a href="main.php?com=2">
						<font size=3 color=red><b>Поговорить с незнакомцем</b></font>
					</a><br>
				<? endif; ?>
				<a href="main.php?com=3">
					<font size=3 color=red><b>Подполье</b></font>
				</a><br>
				<? if ($_GET["com"] == '1') : ?>
					<FIELDSET>
						<LEGEND align=center><B>&nbsp;Барная стойка&nbsp;</B></LEGEND>
						<table cellpadding=10 cellspacing=0 border=0 width=100%>
							<tr>
								<td class=freetxt>
									<?
									if (!empty($_GET["buy"])) {
										if ($chp2 < $maxhp) {
											$hav = SQL::q1("SELECT * FROM `eda` WHERE `id`='" . $_GET["buy"] . "';");
											$pers['money'] -= $hav['cost'];
											$itogo = $hav['hp_lost'] + $chp2;
											SQL::q("UPDATE `users` SET chp='" . $itogo . "', money='" . $pers['money'] . "' WHERE user='" . $pers['user'] . "'");
											echo "<center><font color='green'><b>Приятного аппетита!</b></font></center>";
											echo "<script>location='main.php';</script>";
										} else {
											echo "<center><font color='red'><b>Вы итак полны сил, зачем вам еда?</b></font></center>";
										}
									}

									$sql = SQL::q("SELECT * from eda ORDER by ID DESC");
									foreach ($sql as $eda) {
										$i++;
										if ($eda['cost'] < $pers["money"])

											echo "<br><input type=button class=submit onclick=\"location.href='main.php?com=1&buy=" . $eda['id'] . "'\" value='Заказать'>";

										echo "<table height=100 width=100% cellpadding='0' style='border-style: solid; border-width: 1px'><tr><td width='180' align='center'><img src='images/eda/" . $eda['image'] . ".jpg'><br></td><td align='left'>" . $eda['dur'] . "</td><td width='30%'><table cellspacing='0' cellpadding='0'> <tr><td>Стоимость: </td><td>";
										if ($eda['cost'] < $pers["money"]) {
											echo "<font color='green'><b>" . $eda['cost'] . "";
										} else {
											echo "<font color='red'><b>" . $eda['cost'] . "";
										}
										echo "</td></tr><tr><td>Восстанавливает: </td><td><font class='hp'>" . $eda['hp_lost'] . "</td></tr></table></td></tr></table>";
									}

									?>
								</td>
							</tr>
						</table>
					</FIELDSET>
					</font>
			</td>
		</tr>
	<? elseif (@$_GET["com"] == 2) :


	?>
		<script type="text/javascript">
			function get_quest() {
				location.href = 'main.php?com=1&quest_get=1'
			}

			function run_quest() {
				setTimeout("get_quest()", 1000);
			}
		</script>

		<FIELDSET>
			<?
					if ($quetst['quest1'] == "1") {

						echo "<br><Center><font size=3 color=red><b>Вы уже выполняете этот квест!</b></font></center>";
						$n = 0;
						$nuts1 = SQL::q("SELECT id_in_w from wp WHERE user='" . $pers['user'] . "'");
						foreach ($nuts1 as $nuts) {
							if ($nuts['id_in_w'] == 11111) {
								$n++;
							}
						}
						$d = '';
						if ($n == 0) $d = 'ов';
						if ($n == 1) $d = '';
						if ($n == 2) $d = 'а';
						if ($n == 3) $d = 'а';
						if ($n == 4) $d = 'а';
						if ($n >= 5) $d = 'ов';
						echo "<Center><font size=3 color=red><b>Вы собрали $n орех$d.</b></font></center>";
					} else {
						if ($quetst['quest1'] == "2") {
							echo "<br><Center><font size=3 color=red><b>Вы уже выполнели этот квест!</b></font></center>";
						} else {
			?>
					<font size=3 color=red>
						<LEGEND align=center><B>&nbsp;Стол в темном конце зала&nbsp;</B></LEGEND>

						<table cellpadding=10 cellspacing=0 border=0 width=100%>
							<tr>
								<td class=freetxt>
									<div id="unseen_talk_1">Вы подсели к незнакомцу. Его лицо закрыто капюшоном.</div>
									<div id="unseen_talk_2"><a onclick="javascript:getElementById('unseen_talk_2').style.display='none';javascript:getElementById('unseen_talk_3').style.display='';javascript:getElementById('unseen_talk_4').style.display='';" href="javascript:{}">
											<font color=blue>Сказать &laquo;Привет&raquo;</font>
										</a></div>
									<div id="unseen_talk_3" style="display:none">- <b>Привет</b>, - сказали вы. Незнакомец промолчал.</div>
									<div id="unseen_talk_4" style="display:none"><a onclick="javascript:getElementById('unseen_talk_4').style.display='none';javascript:getElementById('unseen_talk_5').style.display='';javascript:getElementById('unseen_talk_6').style.display='';" href="javascript:{}">
											<font color=red>Попробовать начать разговор еще раз</font>
										</a></div>
									<div id="unseen_talk_5" style="display:none">- <b>Сегодня хороший денек, как считаете</b>? - попытались вы еще раз.<br />- <b>Что тебе от меня нужно</b>? - раздался наконец тихий голос незнакомца.</div>
									<div id="unseen_talk_6" style="display:none"><a onclick="javascript:getElementById('unseen_talk_6').style.display='none';javascript:getElementById('unseen_talk_7').style.display='';javascript:getElementById('unseen_talk_8').style.display='';" href="javascript:{}">
											<font color=red>Предложить незнакомцу выпивку</font>
										</a></div>
									<div id="unseen_talk_7" style="display:none">- <b>Купить вам выпивку</b>? - спросили вы. - <b>Нет</b>, - отрезал незнакомец. - <b>Но если ты пообещаешь отстать от меня, я расскажу тебе кое-что интересное</b>. </div>
									<div id="unseen_talk_8" style="display:none"><a onclick="javascript:getElementById('unseen_talk_8').style.display='none';javascript:getElementById('unseen_talk_9').style.display='';javascript:getElementById('unseen_talk_10').style.display='';" href="javascript:{}">
											<font color=red>Выслушать</font>
										</a></div>
									<div id="unseen_talk_9" style="display:none">- <b>Рассказывайте</b>, - согласились вы.<br />- <b>Когда я шел в этот город, мне попался на пути отряд грабителей.Они попытались было убить меня... но, как видишь, я здесь. К сожалению, сражаясь с ними, я обронил очень важную для меня сумку. В ней было десять &laquo;Орехов Судьбы&raquo;. ГРабители явно местные, живут неподалеку от Метрополиса. Если ты найдешь их и заберешь столь дорогие мне вещи, я тебя щедро отблагодарю</b>, - сказал незнакомец. </div>
									<div id="unseen_talk_10" style="display:none"><a onclick="javascript:getElementById('unseen_talk_10').style.display='none';javascript:getElementById('unseen_talk_11').style.display='';javascript:getElementById('unseen_talk_12').style.display='none';run_quest()" href="javascript:{}">
											<font color=red>- <b>Я сделаю это!</b></font>
										</a><br /><a onclick="javascript:getElementById('unseen_talk_10').style.display='none';javascript:location.href='main.php?com=1';" href="javascript:{}">
											<font color=red>- <b>Мне это неинтересно</b></font>
										</a> </div>
									<div id="unseen_talk_11" style="display:none">- <b>Я сделаю это!</b> - сказали вы.<br /><br /><b>Квест "Потерянные &laquo;Орехи Судьбы&raquo;" начался!</b></div>
									<br />
									<div id="unseen_talk_12"><a onclick="javascript:location.href='main.php?com=1';" href="javascript:{}">
											<font color=red>Закончить разговор</font>
										</a></div>
								</td>
							</tr>
						</table>
					</font>
		</FIELDSET>
<?
						}
					}
				elseif ($pers["n_quest1"] == '10' && $pers["quest1"] == '1') :
?>
<script type="text/javascript">
	function get_quest() {
		location.href = 'main.php?com=1&quest_get=2'
	}

	function run_quest() {
		setTimeout("get_quest()", 3000);
	}
</script>
<FIELDSET>
	<LEGEND align=center><B>&nbsp;Стол в темном конце зала&nbsp;</B></LEGEND>
	<table cellpadding=10 cellspacing=0 border=0 width=100%>
		<tr>
			<td class=freetxt>
				<div id="unseen_talk_1">Вы подсели к незнакомцу. Его лицо закрыто капюшоном.</div>
				<div id="unseen_talk_2"><a onclick="javascript:getElementById('unseen_talk_2').style.display='none';javascript:getElementById('unseen_talk_3').style.display='';javascript:getElementById('unseen_talk_4').style.display='';" href="javascript:{}">
						<font color=red>Сказать &laquo;Я выполнил твое поручение&raquo;</font>
					</a></div>
				<div id="unseen_talk_3" style="display:none">- <b>Я выполнил твое поручение</b>, - сказали вы.</div>
				<div id="unseen_talk_4" style="display:none"><a onclick="javascript:getElementById('unseen_talk_4').style.display='none';javascript:getElementById('unseen_talk_5').style.display='';run_quest();" href="javascript:{}">
						<font color=red>Отдать &laquo;Орехи Судьбы&raquo;</font>
					</a></div>
				<div id="unseen_talk_5" style="display:none">- <b>Вот твои орехи. Все десять штук</b>? - скачали вы.<br />- <b>Спасибо тебе. А теперь - награда.</b>? - раздался тихий голос незнакомца.<br /><br />
					За прохождение квеста вы получаете 25 000 опыта, 1 000 LM, 10 Бр. и&laquo;Свиток Мощь Предков&raquo;.
				</div>
				<br />
				<div id="unseen_talk_12"><a onclick="javascript:location.href='main.php?com=1';" href="javascript:{}">
						<font color=red>Закончить разговор</font>
					</a></div>
			</td>
		</tr>
	</table>
</FIELDSET>
<?
				endif;

?>
<? if ($_GET["com"] == '3') : ?>
	<FIELDSET>
		<LEGEND align=center>
			<font size=3 face="Monotype Corsiva" color=black><B>&nbsp;Подполье&nbsp;</B></font>
		</LEGEND>
		<table cellpadding=10 cellspacing=0 border=0 width=100%>
			<tr>
				<td>

					<?
					$koli = 80 + (20 - ($pers['sp9'] / 5));
					if ($koli < 57) $koli = 57;

					echo "<font size=4 face=\"Monotype Corsiva\" color=black><center><b>Если вы желаете обменять LN на Бр., то курс на сегодняшний день состовляет " . $koli . " LN за 1 Бр.</b></center></font><br>";
					echo "<form action='main.php?com=3&kolvomon' method='POST'><font size=3 color=black>Сколько хотите купить Бр.?&nbsp;&nbsp;<INPUT TYPE=text name=kolvomon  maxlength=4 size=4></font>&nbsp;";
					echo "<input type=submit value='Обменять'></form>";

					if (!empty($_POST["kolvomon"])) {
						if ($_POST["kolvomon"] >= 0) {
							$sumLN = $_POST["kolvomon"] * $koli;

							if ($sumLN < $pers['money']) {
								$LNmin = $pers["money"] - $sumLN;
								$dm = $pers["dmoney"] + $_POST["kolvomon"];
								SQL::q("UPDATE users SET money='" . $LNmin . "', dmoney='" . $dm . "' WHERE user='" . $pers['user'] . "'");
								echo "<center><font size=2 color='#06204e'><b>Вы успешно обменяли " . $_POST["kolvomon"] . " Бр.! За " . $sumLN . " LN </b></font></center>";
							} else {
								echo "<center><font size=3 color='red' face='fonts/Forte.ttf'><b>Вы хотите обменять " . $_POST["kolvomon"] . " Бр.! За " . $sumLN . " LN, но ";
								echo "у вас недостаточно средств для обмена</b></font></center>";
							}
						} elseif ($_POST["kolvomon"] <= 0) echo "<center><font color='red'><b>Введено не правильное число</b></font></center>";
					} else echo "<center><font color='red'><b>Введите количество Бр. которое вы хотите купить!!!</b></font></center>";
					?>
				</td>
			</tr>
		</table>
	<? endif; ?>
	<td>
		</tr>
	</table>