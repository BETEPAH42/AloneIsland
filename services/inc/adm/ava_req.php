<?php
echo "<table width=10%><tr><td><a class=bga href=main.php?go=administration>Назад в меню</a></td></tr></table>";
if (@$_GET["deny"]) {
	$id = intval($_GET["deny"]);
	$r = sql::q1("SELECT * FROM avatar_request WHERE uid=" . $id);
	if ($r) {
		//echo dirname("../.././images/tmp/");
		unlink("images/tmp/ava_" . $r["uid"] . ".gif");
		sql::q("DELETE FROM avatar_request WHERE uid=" . $id);
		set_vars("dmoney=dmoney+30", $r["uid"]);
	}
}
if (@$_GET["accept"]) {
	$id = intval($_GET["accept"]);
	$r = sql::q1("SELECT * FROM avatar_request WHERE uid=" . $id);
	if ($r) {
		$p = sql::q1("SELECT user,level,pol FROM users WHERE uid=" . $r["uid"]);
		//echo dirname("../.././images/tmp/");
		rename("images/tmp/ava_" . $r["uid"] . ".gif", "images/persons/" . $p["pol"] . "_-" . $r["uid"] . ".gif");
		sql::q("DELETE FROM avatar_request WHERE uid=" . $id);
		set_vars("obr='-" . $r["uid"] . "'", $r["uid"]);
	}
}

$req = sql::q("SELECT * FROM avatar_request");
echo "<center>";
echo "<table class=but width=80%><tr>";
$i = 0;
foreach ($req as $r) {
	$i++;
	$p = sql::q1("SELECT user,level FROM users WHERE uid=" . $r["uid"]);
	echo "<td width=20% class=but2><b class=user>" . $p["user"] . " <b class=lvl>[" . $p["level"] . "]</b><a target=_blank href='info.php?id=" . $r["uid"] . "'><img src=images/i.gif></a>
	<br><img src='images/tmp/ava_" . $r["uid"] . ".gif'>
	<br><a class=button href='main.php?accept=" . $r["uid"] . "'>Одобрить</a>
	<a class=button href='main.php?deny=" . $r["uid"] . "'>Удалить</a></td>";
	if ($i % 5 == 4)
		echo "</tr><tr>";
}
echo "</tr></table>";
echo "<center>";
