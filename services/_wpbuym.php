<?

error_reporting(0);
include_once "../configs/config.php";
include_once "../inc/functions.php";

################################## LOCK
$uid = intval($_COOKIE["uid"]);

########################################
$DONT_CHECK = 1;
include("../inc/prov.php");

if (isset($_GET["buy"]) and $_GET["kolvo"] > 0 and $_GET["kolvo"] < 100) {
	$v = sql::q1("SELECT price,q_s,where_buy,name,id,max_durability FROM `weapons` WHERE `id`='" . $_GET["buy"] . "' ;");
	$kolvo = intval($_GET["kolvo"]);
	if ($kolvo > $v['q_s']) $kolvo = $v['q_s'];
	if ($v["where_buy"] == 0 and $v["q_s"] > 0) {

		if ($pers["money"] < ($v["price"] * $kolvo))
			echo "<b><font class=hp>Не хватает денег.</b></font>";
		else {
			for ($i = 1; $i <= $kolvo; $i++)
				insert_wp($v["id"], $pers["uid"], $v["max_durability"], 0, $pers["user"]);
			$pers["money"] -= $v["price"] * $kolvo;
			set_vars("money=money-" . ($v["price"] * $kolvo), $pers["uid"]);
			sql::q("UPDATE `weapons` SET `q_s`=q_s - " . $kolvo . " WHERE `id`='" . $v["id"] . "'");
			echo "<script>top.frames['main_top'].success('" . $v["name"] . "'," . $v["price"] . "," . $kolvo . ");</script>";
		}
	}
}
