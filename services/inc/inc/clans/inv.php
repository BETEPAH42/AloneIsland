<?
$pers["sign"] = $clan["sign"];

$url = '';
if (!$delete_button1) {
	$delete_button1 = '';
	$delete_button2 = '';
	$url = 'action=addon&gopers=clan&clan=w&';
}


if (@$_GET["get_item"] and $pers["clan_tr"]) {
	$v = sql::q1("SELECT * FROM wp WHERE id=" . intval($_GET["get_item"]) . " and clan_sign='" . $clan["sign"] . "'");
	if (@$v["id"] and $v["weared"] == 0) {
		sql::q("UPDATE wp SET uidp=" . $pers["uid"] . ",user='" . $pers["user"] . "' WHERE id=" . intval($_GET["get_item"]) . " and clan_sign='" . $clan["sign"] . "'");
	}
	if (@$v["id"] and $v["weared"] == 1) {
		sql::q("UPDATE wp SET  weared='0'	WHERE id=" . intval($_GET["get_item"]) . " and clan_sign='" . $clan["sign"] . "'");
	}
}
?>

<center>
	<table border=0 width=600 class=but>
		<tr>
			<td align=center>
				<script>
					show_imgs_sell('<?= $url; ?>inv=<?= $clan["sign"]; ?>');
				</script>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<?
				if ($_FILTER["lavkatype"] != 'napad')
					$stype = "`stype`='" . $_FILTER["lavkatype"] . "'";
				else
					$stype = "`type` = 'napad' ";

				if ($_FILTER["lavkatype"] <> 'all')
					$enures = sql::q("SELECT * FROM `wp` WHERE " . $stype . " and clan_sign='" . $pers["sign"] . "'");
				else
					$enures = sql::q("SELECT * FROM `wp` WHERE clan_sign='" . $pers["sign"] . "'");
				$check = 0;
				foreach ($enures as $v) {
					if ($v["max_durability"] and !$v["durability"]) continue;
					echo "<div class=but2>";
					$check++;
					if ($v["weared"] == 0 and $pers["clan_tr"]) echo "<a href=info.php?" . $v["user"] . " class=user target=_blank>" . $v["user"] . "</a> <input type=button class=but onclick=\"location='main.php?action=addon&gopers=clan&clan=w&get_item=" . $v["id"] . "'\" value='Взять'>";

					elseif ($pers["clan_tr"]) {
						echo "<font class=hp>Вещь надета на персонаже </font><a href=info.php?" . $v["user"] . " class=user target=_blank>" . $v["user"] . "</a>";
						if ($v["weared"] == 1 and $pers["clan_tr"] and $pers["clan_state"] == 'g') echo "<input type=button class=but onclick=\"location='main.php?action=addon&gopers=clan&clan=w&get_item=" . $v["id"] . "&pers=" . $v["user"] . "'\" value='Забрать'>";
					}
					if ($delete_button1)
						echo $delete_button1 . $v["id"] . $delete_button2;
					echo "</div>";
					$vesh = $v;
					include("inc/inc/weapon.php");
				}
				if ($clan["treasury"] < $check) {
					$tr = sql::q1("SELECT COUNT(*) as count FROM wp WHERE clan_sign='" . $pers["sign"] . "'")['count'];
					sql::q("UPDATE clans SET treasury=" . $tr . " WHERE sign='" . $pers["sign"] . "'");
				}
				?>
	</table>
</center>