<center>
	<table border="1">
		<tr>
			<font size=3 color=red>
				<center><b>Лечебница</b></center>
		</tr>
		<tr>
			<td width="440">
				<img width="440" src="images/locations/shop.jpg">
			</td>
			<?

			/*
switch ($_GET['act']){
  case 'state':
    show_travm();
  break;
  case '1':
    show_lechenie();
  break;
}
*/
			?>

			<script>
				$(document).ready(
					function() {
						$("#one").slideUp(0);
						$("#two").slideUp(0);
					}
				);

				function slide(id) {
					s_id = '#' + id;
					if ($(s_id).attr('state') == 0) {
						$(s_id).attr('state', '1');
						$(s_id).slideUp(300);
					} else {
						$(s_id).attr('state', '0');
						$(s_id).slideDown(300);
					}
				}
			</script>
			<td width="500" valign="top">
				<center>
					<div style='width:70%'><a class='bga' onclick=slide('one')>Лечение травм</a></div>
					<div style='width:70%' id='one' state=1>
						<?
						$hav3 = '';
						$tra = '';
						$hav1 = SQL::q("SELECT special FROM p_auras WHERE uid=" . $pers['uid'] . " and special>2 and special<6");
						$hav2 = SQL::q1("SELECT name FROM p_auras WHERE uid=" . $pers['uid'] . "");

						foreach ($hav1 as $h12) {
							$i++;
							$tra = $hav2;
							$tr1 = '';
							$hav3 = $tra['name'];
							if ($h12['special'] == 3) {
								$tr1 .= "легкая травма";
								$mon_travm = 15;
							}
							if ($h12['special'] == 4) {
								$tr1 .= "средняя травма";
								$mon_travm = 30;
							}
							if ($h12['special'] == 5) {
								$tr1 .= "тяжёлая травма";
								$mon_travm = 50;
							}
							echo "<center><font class=user>У вас " . $hav3 . " (" . $tr1 . ")</b></font></center><center><input type=button class=submit onclick=\"location.href='main.php?do=notravm'\" value='Вылечить?'></center><br>";
						}
						if ($_GET["do"] == "notravm") {
							$pers["money"] - $mon_travm;
							sql::q("UPDATE p_auras SET `esttime`= 0 WHERE `name`='" . $hav3 . "'");
							sql::q("UPDATE users SET money=money-'" . $mon_travm . "' WHERE uid=" . $pers['uid']);
							echo "<script>location='main.php';</script>";
						}

						if ($hav3 == '') {
							echo "<center><font class=user>У вас нет травм</b></font></center>";
						}
						?>
						<form action='main.php?act=state' method='post'>
						</form><br>
					</div>
				</center>
				<center>
					<div style='width:70%'><a class='bga' onclick=slide('two')>Услиги по лечению</a></div>
					<div style='width:70%' id='two' state=1>

						<?
						echo "<center><Font size=2><b>У Вас " . $pers['chp'] . " здоровья из " . $pers['hp'] . ". </b></font></center>";
						$raz_hp = $pers['hp'] - $pers['chp'];

						$mon_hp = ($raz_hp / 100) * 5;
						if ($_GET["do"] == "lechen") {
							$time_lechen = $raz_hp / 10;
							$pers["waiter"] = tme() + $time_lechen;
							$mon_raz = $pers["money"] - $mon_hp;
							SQL::q("UPDATE `users` SET chp='" . $pers['hp'] . "', money=" . $mon_raz . ", tire=0, waiter=" . $pers['waiter'] . " WHERE user='" . $pers['user'] . "'");
							echo "<br><Center><Font size=2 color='red'><b>Вас лечат подождите:</b></font></center>";
							echo "<div id=waiter class=items align=center>Вас лечат</div>";
							echo "<script>waiter('$time_lechen');</script>";
						}




						if ($pers['chp'] < $pers['hp']) {
							if ($pers["waiter"] < tme()) {
								echo "<center><font class=user>Хотите поправить здоровье за " . $mon_hp . " LN?</font></center>";
								echo "<center><input type=button class=submit onclick=\"location.href='main.php?do=lechen'\" value='Лечиться?'></center><br>";
							}
						} else {
							echo "<br><center><Font size=3 color='red'><b>Вам не требуется лечение!</b></font></center>";
						}

						?>
						<form action='main.php?act=2' method='post'>
						</form>
					</div>

				</center>

			</td>
		</tr>
	</table>
</center>