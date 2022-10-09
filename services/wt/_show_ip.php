<?
$b = (substr_count($you["rank"], "<block>")) ? 1 : 0;
$ips = sql::q("SELECT * FROM ips_in WHERE uid=" . $pers["uid"] . "");
if (empty($_POST["mlt"])) {
	if (@$_POST and $b) {
		$q = '';
		foreach ($_POST as $key => $v)
			if ($v == "1") $q .= str_replace("ip_", "`date`=", $key) . ' or ';

		$q = substr($q, 0, strlen($q) - 3);
		sql::q("DELETE FROM ips_in WHERE uid=" . $pers["uid"] . " and (" . $q . ")");
	}
	echo '<form method=post name=ips><table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
	foreach ($ips as $ip) {
		echo "<tr>";
		echo "<td width=10><input type=checkbox name=ip_" . $ip["date"] . " value=1></td>";
		echo "<td width=100 class=timef>" . date("d.m.y H:i", $ip["date"]) . "</td>";
		echo "<td width=100 class=items aling=center>" . $ip["ip"] . "</td>";
		echo "</tr>";
	}
	echo "</table><input type=hidden name=mlt value=0><input type=button class=login value='Выделить все' onclick='select_all_checks()'><input type=submit class=login value='Удалить выделенные'><input type=button class=login value='Найти мультов с выделенными адресами' onclick='document.ips.mlt.value=1;document.ips.submit();'></form>";
} else {
	if (@$_POST) {
		$q = '';
		foreach ($_POST as $key => $v)
			if ($v == "1" and $key <> 'mlt') $q .= str_replace("ip_", "`date`=", $key) . ' or ';

		$q = substr($q, 0, strlen($q) - 3);
		$ips = sql::q("SELECT ip FROM ips_in WHERE uid=" . $pers["uid"] . " and (" . $q . ")");
		$q = '';
		$ch_str = '';
		foreach ($ips as $ip)
			if (!substr_count($ch_str, "<" . $ip["ip"] . ">")) {
				$q .= "`ip`='" . $ip["ip"] . "' or ";
				$ch_str .= "<" . $ip["ip"] . ">";
			}

		$q = substr($q, 0, strlen($q) - 3);
		$mults = sql::q("SELECT uid,date FROM ips_in WHERE uid<>" . $pers["uid"] . " and (" . $q . ")");
		$counter = 0;
		echo '<table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
		foreach ($mults as $mult) {
			$counter++;
			$m = sql::q1("SELECT user,level,sign FROM users WHERE uid=" . $mult["uid"] . "");
			echo "<tr>";
			echo "<td width=100 class=timef>" . date("d.m.y H:i", $mult["date"]) . "</td>";
			echo "<td width=200 align=center>";
			echo "<img src=images/signs/" . $m["sign"] . ".gif><font class=user>" . $m["user"] . "</font>";
			echo "[<font class=lvl>" . $m["level"] . "</font>]";
			echo "<a href=info.php?p=" . $m["user"] . " target=_blank>";
			echo "<img src=images/i.gif></a>";
			echo "</td>";
			echo "</tr>";
		}
		if ($counter == 0)
			echo "<tr><td>Не найдено ниодного мульта!</td></tr>";
		echo "</table>";
	}
}
