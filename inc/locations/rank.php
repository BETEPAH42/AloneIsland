<center>
	<table border="0" width="600" cellspacing="0" cellpadding="0" class=but>
		<tr>
			<td align="center" height="55">
				<table border="0" width="500" cellspacing="0" cellpadding="0">
					<tr>
						<td width="500" colspan="8" align=center>
							<div style="border-color:#2B587A;border-bottom-style: solid; border-bottom-width: 1px; padding-bottom: 1px">
								<h2>Рейтинги</h2>
								<i class=gray>Тестируется и дорабатывается...</i>
							</div>
						</td>
					</tr>
					<tr>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=1>Рейтинг войнов</a></b>
						</td>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=2>Рейтинг рыболовов</a></b>
						</td>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=3>Рейтинг алхимиков</a></b>
						</td>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=4>Рейтинг шахтёров</a></b>
						</td>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=5>Рейтинг охотников</a></b>
						</td>
						<td width=16% class=but>
							<b><a class=bg href=main.php?cat=6>Реферальный рейтинг</a></b>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px">
				<script>
					<?
					if (empty($_GET["cat"]) or $_GET["cat"] == 1)
						include("service/top_gamers/A" . date("d-m-y") . ".txt");
					if (@$_GET["cat"] == 2)
						include("service/top_gamers/F" . date("d-m-y") . ".txt");
					if (@$_GET["cat"] == 3)
						include("service/top_gamers/L" . date("d-m-y") . ".txt");
					if (@$_GET["cat"] == 4)
						include("service/top_gamers/M" . date("d-m-y") . ".txt");
					if (@$_GET["cat"] == 5)
						include("service/top_gamers/H" . date("d-m-y") . ".txt");
					if (@$_GET["cat"] == 6)
						include("service/top_gamers/R" . date("d-m-y") . ".txt");

					?>

					function show_list() {
						document.write(sbox2b(1) + '<table width=500 border="0" cellspacing="0" cellpadding="0">');
						for (var i = 0; i < list.length; i++) document.write(hero_string(list[i], i + 1));
						document.write('</table>' + sbox2e());
					}

					function hero_string(element, a) {
						var arr = element.split("|");
						var s;
						var info;
						var bg = '#EEEEEE';
						if (a % 2) bg = '#F5F5F5';
						if (arr[5] == 0)
							info = '<a href=\'info.php?p=' + arr[0] + '\' target=_blank> <img src=images/_i.gif border=0> </a>';
						else
							info = '<img src=images/i.gif onclick="javascript:window.open(\'binfo.php?' + arr[6] + '\',\'_blank\')" style="cursor:pointer">';
						s = '<tr style="background-color:' + bg + '"><td class=items>' + a + '.</td><td><img src=images/_p.gif onclick="javascript:top.say_private(\'' + arr[0] + '\')" style="cursor:pointer"> </td><td> <img src=images/signs/' + arr[2] + '.gif title=\'' + arr[3] + '\'><font class=user onclick="javascript:top.say_private(\'' + arr[0] + '\')"> ' + arr[0] + '</font></td><td>[<font class=lvl>' + arr[1] + '</font>]</td><td>' + info + '</font>';
						s += '</td><td class=ma style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px"> &nbsp;Очки: ' + arr[4];
						s += '</td></tr>';
						return s;
					}
				</script>
			</td>
		</tr>
	</table>
</center>
<?
if ($_GET["cat"] == 2) {
	$wins = sql::q1("SELECT MAX(victories)as max FROM `users` WHERE sign<>'sl'")['max'];
	$wins_u = sql::q1("SELECT user,level,sign FROM `users` WHERE `victories`='" . $wins['max'] . "'");
	$lozes = sql::q1("SELECT MAX(losses) as max FROM `users` WHERE sign<>'sl'");
	$lozes_u = sql::q1("SELECT user,level,sign FROM `users` WHERE `losses`='" . $lozes['max'] . "'");
	$hunt = sql::q1("SELECT MAX(sp10) as max FROM `users` WHERE sign<>'sl'");
	$hunt_u = sql::q1("SELECT user,level,sign FROM `users` WHERE ROUND(sp10*10)='" . round($hunt['max'] * 10) . "'");
	$money = sql::q1("SELECT MAX(money) as max FROM `users` WHERE sign<>'sl'");
	$money_u = sql::q1("SELECT user,level,sign FROM `users` WHERE `money`='" . $money['max'] . "'");
	$f = sql::q1("SELECT MAX(losses+victories) as max FROM `users` WHERE sign<>'sl'");
	$f_u = sql::q1("SELECT user,level,sign FROM `users` WHERE losses+victories='" . $f['max'] . "'");
	$exp = sql::q1("SELECT MAX(exp) as max FROM `users` WHERE sign<>'sl'");
	$exp_u = sql::q1("SELECT user,level,sign FROM `users` WHERE exp='" . $exp['max'] . "'");
	$hp = sql::q1("SELECT MAX(hp) as max FROM `users` WHERE sign<>'sl'");
	$hp_u = sql::q1("SELECT user,level,sign FROM `users` WHERE hp='" . $hp['max'] . "'");
	$ma = sql::q1("SELECT MAX(ma) as max FROM `users` WHERE sign<>'sl'");
	$ma_u = sql::q1("SELECT user,level,sign FROM `users` WHERE ma='" . $ma['max'] . "'");
	if (!file_exists("service/records/" . date("d-m-y") . ".txt")) {
		$rekords = '
	<table border="0" width="600" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		побед</span></td>
		<td align="center" class="items">' . $wins . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $wins_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $wins_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $wins_u['user'] . '\')"> ' . $wins_u['user'] . '</font></td><td>[<font class=lvl>' . $wins_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $wins_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		поражений</span></td>
		<td align="center" class="items">' . $lozes['max'] . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $lozes_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $lozes_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $lozes_u['user'] . '\')"> ' . $lozes_u['user'] . '</font></td><td>[<font class=lvl>' . $lozes_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $lozes_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		умений &quot;Охота&quot;</span></td>
		<td align="center" class="items">' . round($hunt['max'] * 10) . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $hunt_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $hunt_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $hunt_u['user'] . '\')"> ' . $hunt_u['user'] . '</font></td><td>[<font class=lvl>' . $hunt_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $hunt_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		Игровой Валюты</span></td>
		<td align="center" class="items">' . round($money['max'], 2) . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $money_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $money_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $money_u['ser'] . '\')"> ' . $money_u['ser'] . '</font></td><td>[<font class=lvl>' . $money_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $money_u['ser'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		боёв</span></td>
		<td align="center" class="items">' . $f['max'] . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $f_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $f_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $f_u['user'] . '\')"> ' . $f_u['user'] . '</font></td><td>[<font class=lvl>' . $f_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $f_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		опыта</span></td>
		<td align="center" class="items">' . $exp['max'] . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $exp_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $exp_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $exp_u['user'] . '\')"> ' . $exp_u['user'] . '</font></td><td>[<font class=lvl>' . $exp_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $exp_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		</span><font class=hp>HP</font></td>
		<td align="center" class="items">' . $hp['max'] . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $hp_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $hp_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $hp_u['user'] . '\')"> ' . $hp_u['user'] . '</font></td><td>[<font class=lvl>' . $hp_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $hp_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		</span><font class=ma>MA</font></td>
		<td align="center" class="items">' . $ma['max'] . '</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\'' . $ma_u['user'] . '\')" style=cursor:hand> </td><td> <img src=images/signs/' . $ma_u['sign'] . '.gif><font class=user onclick="javascript:top.say_private(\'' . $ma_u['user'] . '\')"> ' . $ma_u['user'] . '</font></td><td>[<font class=lvl>' . $ma_u['level'] . '</font>]</td><td><img src=../images/info.gif onclick="javascript:window.open(\'info.php?p=' . $ma_u['user'] . '\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
</table>
	';
		$f = fopen("service/records/" . date("d-m-y") . ".txt", "w");
		fwrite($f, $rekords);
		fclose($f);
	} else include("service/records/" . date("d-m-y") . ".txt");
}
?>