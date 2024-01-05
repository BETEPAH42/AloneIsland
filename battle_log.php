<?php
error_reporting(0);
include("configs/config.php");
include("inc/functions.php");
$bid = intval($_GET["id"]);
$page = intval($_GET["page"]);
$results = intval($_GET["results"]);
define("C_LIST", 12);
$pages = SQL::q1("SELECT COUNT(*) as count FROM fight_log WHERE cfight=" . $bid . " ");
$limits = $page['count'] * C_LIST;
if (!$results) {
	$res = "SELECT * FROM fight_log WHERE cfight=" . $bid . " ORDER BY turn ASC LIMIT " . $limits . "," . C_LIST . ";";
	foreach (SQL::q($res) as $txt)
		$s .= "['" . $txt["time"] . "','" . str_replace("'", '"', $txt["log"]) . "'],";
}

$battle = SQL::q1("SELECT travm,oruj,type,result,ltime,timeout FROM fights WHERE id=" . $bid . "");
if ($results) {
	echo "<div id=info style='visibility:hidden;height:0px;top:-10000;position:absolute;z-index:2;'>" . $battle["result"] . "</div>";
}
$s = substr($s, 0, strlen($s) - 1);
$injury = $battle["travm"];
$ins = $battle["oruj"];
$finished = ($battle["type"] == 'f') ? 1 : 0;
$ltime = $battle["timeout"] + $battle["ltime"] - tme();
echo "<script>
			var bid = " . $bid . ";
			var page = " . $page . ";
			var pages = " . intval($pages / C_LIST) . ";
			var log = [" . $s . "];
			var inj = " . $injury . ";
			var ins = " . $ins . ";
			var fin = " . $finished . ";
			var ltime = " . $ltime . ";
			var results = " . $results . ";
	</script>";
?>
<SCRIPT src="js/battle_log.js?12"></SCRIPT>
<SCRIPT SRC='js/c.js'></SCRIPT>
<SCRIPT SRC='js/end.js'></SCRIPT>