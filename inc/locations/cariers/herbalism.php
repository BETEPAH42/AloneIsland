<?
if (isset($_GET["herbal"]) and $pers["waiter"] < tme()) {
	echo "
	<script>
	top.frames['main_top'].document.getElementById('outer').style.display= 'block';
	</script>
	";
	if ($cell["last_herbal_change"] < (time() - HERBAL_CHANGE)) {
		$w = sql::q1("SELECT COUNT(image) as count FROM `herbals_cell` WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "'");
		if ($w['count'] > HERBAL_COUNT)
			sql::q("DELETE FROM herbals_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "'");
		$h = sql::q1("SELECT * FROM herbals WHERE image%5=" . ($cell["herbal"] - 1) . " ORDER BY RAND() LIMIT 1");
		sql::q("INSERT INTO `herbals_cell` ( `image` , `name` , `time` , `x_y` ) VALUES ('" . $h["image"] . "', '" . $h["name"] . "', '" . (time() - HERBAL_GROW - 1) . "', '" . $cell["x"] . "_" . $cell["y"] . "');");
		sql::q("UPDATE nature SET last_herbal_change=" . time() . " WHERE x=" . $cell["x"] . " and y=" . $cell["y"] . "");
	}
	$pers["waiter"] = round(time() + HLOOK_TIME);
	set_vars("action=1,waiter=" . round(time() + HLOOK_TIME), $pers["uid"]);
	//echo "<script>waiter(".(40).");</script><center class=items><b>Осмотр.</b></center><hr><div id=waiter class=items align=center></div>";
	$res = sql::q("SELECT * FROM herbals_cell WHERE x_y='" . $cell["x"] . "_" . $cell["y"] . "'");
	echo "<table class=lbutton cellspacing=0 cellspadding=0 width=500 border=1 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>";
	$i = 0;
	$herbal_grow = HERBAL_GROW;
	if (WEATHER == 2) $herbal_grow /= 2;
	if (WEATHER == 3) $herbal_grow *= 2;
	if (WEATHER == 1 and date("m") > 5 and date("m") < 9) $herbal_grow *= 3;
	if (WEATHER == 6) $herbal_grow /= 3;
	foreach ($res as $h) {
		if ($i % 3 == 0) echo "<tr>";
		echo "<td bgcolor=#F5F5F5 width=30%>
		<b>" . $h["name"] . "</b><br>
		<img src=images/weapons/herbals/" . $h["image"] . ".gif title='" . $h["name"] . "' style='border-style: outset; border-width: 3; border-color:#FFFFFF;'><hr>";
		if (($h["time"] + $herbal_grow) >= tme())
			echo "<i>Срезано</i>";
		else {
			$w = sql::q1("SELECT id FROM wp WHERE uidp=" . $pers["uid"] . " and weared=1 and p_type=2 and durability>0");
			if ($w["id"])
				echo "<input type=button class=but2 value='Срезать' onclick=\"location='main.php?get_herbal=" . $h["image"] . "&code=" . md5($pers["city"] . $h["name"]) . "'\">";
			else
				echo "<i>Нет инструмента</i>";
		}
		echo "</td>";
		$i++;
		if ($i % 3 == 0) echo "</tr>";
	}
	echo "</table>";
}
