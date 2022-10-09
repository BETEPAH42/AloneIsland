<?
if (isset($_REQUEST["store_act"]) && !empty($_REQUEST["store_act"]) && isset($_REQUEST["res_kolvo"]) && !empty($_REQUEST["res_kolvo"]) && isset($_REQUEST["what_res"])) {
	if ($_REQUEST["store_act"] == 'sell') {
		$cur_now = SQL::q1("SELECT count(name) as count FROM `wp` WHERE id_in_w='" . $_REQUEST["what_res"] . "' and uidp=" . $pers["uid"] . "")['count'];
		$resources3 = SQL::q1("SELECT * FROM store_gos WHERE resource_id='" . $_REQUEST["what_res"] . "'");
		if ($_REQUEST["res_kolvo"] > $cur_now)
			$_REQUEST["res_kolvo"] = $cur_now;

		$b_price = $resources3['resource_price'];
		$price_total1 = 0;

		if ($fility2 < 0.125)
			$to_price = (round(((1.125 - $fillity2) * $b_price) * 100)) / 100;
		else
			$to_price = (round(((1 - ($fillity2 - 0.125)) * $b_price) * 100)) / 100;
		$is++;
		$price_total1 = $price_total1 + $to_price;

		SQL::q("UPDATE users SET money=money+" . $price_total1 . " WHERE uid=" . $pers["uid"] . "");
		SQL::q("UPDATE capital SET money=money-" . $price_total1 . " WHERE organization='store'");
	}
}
?>
<script type="text/javascript">
	document.body.bgColor = '#d6cab2';
</script>
<script type="text/javascript" src="/API/store.php"></script>

<div id="back" style="position: absolute; display: none; left: 0; top: 0; width: 100%; z-index: 50; background-color:#000; filter:alpha(opacity=75); -moz-opacity: 0.75; opacity: 0.75;"></div>
<div style="width:332px; height:154px; background-image:url(/design/pop_up.png); background-repeat:no-repeat; background-position:center; left:0px; top:0px; display:none; position:absolute; z-index:100;" id="popup"></div>

<center>
	<table width="950" border="1" style="border-width:1px; border-color:#666666;" cellspacing="0">
		<tr>
			<td colspan="6" align="center"><input type="button" value=" государственный склад " style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:250px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" обменный пункт " disabled="disabled" style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:250px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" касса " style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:250px;" disabled="disabled"></td>
		</tr>
		<tr>
			<td colspan="6" align="center"><input type="button" value=" охота " style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:200px;" onclick="location.href='?room=hunt'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" рыбалка " onclick="location.href='?room=fish'" style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:200px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" собирательство " style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:200px;" disabled="disabled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" шахтерство " onclick="location.href='?room=resources'" style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:200px;"></td>
		</tr>
		<tr>
			<td colspan="6" align="center">Казна Склада: <? echo SQL::q1("SELECT money FROM capital WHERE organization='store'")['money']; ?> LN</b></font>
			</td>
		</tr>
		<? if (isset($price_total1) && $price_total1 > 0) : ?>
			<tr>
				<td colspan="6" align="center">
					<font face="arial" color="#990000"><b>На Ваш счет зачислено <?= $price_total1 ?> LN</b></font>
				</td>
			</tr>
		<? endif; ?>
		<? if (isset($_REQUEST["room"]) && !empty($_REQUEST["room"])) { ?>
			<tr>
				<td colspan="2" align="center">
					<font face="arial"><b>Ресурс</b></font>
				</td>
				<td rowspan="2" width="160" align="center">
					<font face="arial"><b>Склад</b></font>
				</td>
				<td rowspan="2" width="160" align="center">
					<font face="arial"><b>Цена за 1 ед.</b></font>
				</td>
				<td rowspan="2" width="150" align="center">
					<font face="arial"><b>В наличии</b></font>
				</td>
				<td rowspan="2" width="150" align="center">
					<font face="arial"><b>Действия</b></font>
				</td>
			</tr>
			<tr>
				<td width="100" align="center">
					<font face="arial"><b>изображение</b></font>
				</td>
				<td width="180" align="center">
					<font face="arial"><b>наименование</b></font>
				</td>
			</tr>
			<?
			$resources = SQL::q("SELECT * FROM store_gos WHERE resource_type='" . $_REQUEST["room"] . "'");
			foreach ($resources as $b) {
				$current_is = SQL::q1("SELECT count(name) as count FROM `wp` WHERE id_in_w='" . $b['resource_id'] . "' and uidp=" . $pers["uid"] . "")['count'];
				$fillity = ($b['resource_is'] / $b['resource_max']) / 2;
				$price = $b['resource_price'];

				if ($fility < 0.125) :
					$price_total = (round(((1.125 - $fillity) * $price) * 100)) / 100;
				else :
					$price_total = (round(((1 - ($fillity - 0.125)) * $price) * 100)) / 100;
				endif;
			?>
				<tr>
					<td align="center" valign="middle"><img src="../images/weapons/<? echo $b['resource_image']; ?>.gif" border="0" /></td>
					<td align="center" valign="middle">&laquo;<? echo $resources['resource_name']; ?>&raquo;</td>
					<td align="center" valign="middle"><? echo $b['resource_is']; ?>/<? echo $b['resource_max']; ?></td>
					<td align="center" valign="middle"><?= $price_total ?> LN</td>
					<td align="center" valign="middle"><?= $current_is ?> шт.</td>
					<td align="center" valign="middle"><input type="button" value=" сдать " style="background-color:#d6cab2;border:1px solid #000000; font-weight:bold; width:100px;" <? if ($current_is == 0 || $b['resource_is'] >= $b['resource_max']) { ?> disabled="disabled" <? } ?> onclick="sell_res(<?= $b['resource_number'] ?>);" /></td>
				</tr>
		<? }
		} ?>
	</table>
</center>