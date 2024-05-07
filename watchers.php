<META Content='text/html; charset=utf-8' Http-Equiv='Content-type'>
<LINK href='main.css' rel='STYLESHEET' type='text/css'>
<?php
error_reporting(E_ALL);
include_once "classes/autoload.php";
include('inc/functions.php');

if (empty($_GET["p"])) $_GET["p"] = $key;
$_GET["p"] = trim(str_replace("'", "", $_GET["p"]));
if (@$_GET["id"])
	$pers = sql::q1("select * from `users` where `uid`=" . intval($_GET["id"]) . "");
else {
	$pers = SQL::q1("select * from `users` where `user`='" . $_GET["p"] . "'");
	echo "<script>var frameResizer = '<b class=user>" . $pers["user"] . "</b>';</script>";
}
$chars = SQL::q1("SELECT * FROM `chars` WHERE `uid`='" . $pers["uid"] . "'");
$locname = SQL::q1("SELECT * FROM `locations` WHERE `id`='" . $pers["location"] . "' ;");

$_GET["id"] = $pers["uid"];

if (substr_count($pers["aura"], "invisible")) {
	$pers["online"] = 0;
	$pers["chp"] = $pers["hp"];
	$pers["cma"] = $pers["hp"];
	$pers["cfight"] = 0;
}
$you = catch_user(addslashes($_COOKIE["uid"]));
if ($you["pass"] != $_COOKIE["hashcode"] or $you["block"]) die("<i>Доступ запрещён.</i>");
if (!$pers["uid"]) die("<center><b>Нет такого персонажа.</b></center>");


echo "<script>var nick='" . $pers["user"] . "';</script>";
echo '<script type="text/javascript" src="js/watchers.js?2"></script>';

if ($you["diler"]) $you["rank"] .= "<diler><molch><pv><prison><block><w_pom><b_info><punishment>"; //<prison><block><w_pom><b_info><punishment>

#filter
foreach ($_POST as $key => $value) $_POST[$key] = filter($value);
foreach ($_GET  as $key => $value) $_GET[$key]  = filter($value);
####

if (($you["sign"] == 'c2' or substr_count($you["rank"], "<pv>"))) {
	include("services/wt/functions.php");
	if (@$_GET["do_w"] == "ip") include("services/wt/_show_ip.php");
	if (@$_GET["do_w"] == "mpb") include("services/wt/_mpb.php");
	if (@$_GET["do_w"] == "rmpb") include("services/wt/_rmpb.php");
	if (@$_GET["do_w"] == "w_z") include("services/wt/_w_z.php");
	if (@$_GET["do_w"] == "pass") include("services/wt/_pass.php");
	if (@$_GET["do_w"] == "sells") include("services/wt/_sells.php");
	if (@$_GET["do_w"] == "battles") include("services/wt/_battles.php");
	if (@$_GET["do_w"] == "onecomp") include("services/wt/_onecomp.php");
	if (@$_GET["bug"]) {
		echo 'BUG OFF : % <b>' . $pers["user"] . '</b>"';
		set_vars("cfight=0 , curstate=0, apps_id=0", $pers["uid"]);
	}
	if (@$_GET["clan_go_out"] and substr_count($you["rank"], "<block>")) {
		echo 'CLAN OFF : % <b>' . $pers["user"] . '</b>"';
		set_vars("sign='none',state='',rank=''", $pers["uid"]);
	}
	if (@$_GET["wear_out"] and substr_count($you["rank"], "<block>")) {
		echo 'Wear out : % <b>' . $pers["user"] . '</b>"';
		remove_all_weapons();
	}
}
