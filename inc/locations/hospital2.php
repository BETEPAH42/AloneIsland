<?
$chp2 = $pers['chp'];
$maxhp = $pers['hp'];
function tme()
{
	global $GLOBAL_TIME;
	return $GLOBAL_TIME;
}
?>
<center>
	<table cellpadding=0 cellspacing=0 border=0 align=center width="90%">
		<tr><? echo "<center><font class=user>Жизненая сила: <b>" . $pers["chp"] . " </b></font></center>"; ?><br></tr>
	</table>

	<?
	if (!empty($_GET["buy"])) {
		if ($chp2 < $maxhp) {
			$hav = SQL::q1("SELECT * FROM `eda` WHERE `id`='" . $_GET["buy"] . "' ORDER BY id ACS;");
			$pers['money'] -= $hav['cost'];
			$itogo = $hav['hp_lost'] + $chp2;
			SQL::q("UPDATE `users` SET chp='" . $itogo . "', money='" . $pers['money'] . "' WHERE user='" . $pers['user'] . "'");
			echo "<center><font color='green'><b>Приятного аппетита!</b></font></center>";
		} else {
			echo "<center><font color='red'><b>Вы итак полны сил, зачем вам еда?</b></font></center>";
		}
	}
	if (@$_GET["gopers"] == "service") {
		if ($_GET["do"] == "healthy" and $pers["dmoney"] >= 1) {
			if ($pers["level"] <= 15)
				$pers["dmoney"] -= 0.25;
			else
				$pers["dmoney"]--;
			$pers["chp"] = $pers["hp"];
			$pers["cma"] = $pers["ma"];
			sql::q("UPDATE users SET chp=hp,cma=ma,dmoney=" . $pers["dmoney"] . " WHERE uid=" . $pers["uid"]);
			echo "<script>location='main.php';</script>";
		}
		if ($_GET["do"] == "notravm" and $pers["dmoney"] >= 1) {
			$pers["dmoney"]--;
			sql::q("UPDATE p_auras SET esttime=0 WHERE uid=" . $pers["uid"] . " and special>2 and special<6 and esttime>" . tme() . ";");
			sql::q("UPDATE users SET dmoney=dmoney-1 WHERE uid=" . $pers["uid"]);
			echo "<script>location='main.php';</script>";
		}
	}
