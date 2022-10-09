<?
$pers["chp"] = floor($pers["chp"]);
$pers["cma"] = floor($pers["cma"]);

$cans = sql::q("SELECT uid2 FROM turns_f WHERE uid1=" . $pers["uid"] . "");
$uid_query = '';
foreach ($cans as $c)
	$uid_query .= ' and uid<>' . $c["uid2"] . '';

if ($pers["chp"] > 0) {
	if (@$_GET["vs_id"]) {
		$idvs = intval(base64_decode($_GET["vs_id"]));
		if ($idvs > 0) $persvs = sql::q1("SELECT * FROM users WHERE uid=" . $idvs . " and fteam<>" . $pers["fteam"] . " and chp>0" . $uid_query);
		else $persvs = sql::q1("SELECT * FROM bots_battle WHERE id=" . $idvs . " and chp>0 and fteam<>" . $pers["fteam"] . "");
	}
	if (empty($_GET["vs_id"]) or !$persvs["user"]) {
		$persvs = sql::q1("SELECT * FROM users WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0" . $uid_query);
		if (!$persvs["uid"])
			$persvs = sql::q1("SELECT * FROM bots_battle WHERE cfight=" . $pers["cfight"] . " and fteam<>" . $pers["fteam"] . " and chp>0");
	}
	//if (!$persvs["user"]) SQL::q("UPDATE users SET cfight=0, curstate=0,refr=1,exp_in_f=0,kills=0,fexp=0 WHERE uid=".$pers["uid"]."");

	$persvs["chp"] = floor($persvs["chp"]);
	$persvs["cma"] = floor($persvs["cma"]);
}
