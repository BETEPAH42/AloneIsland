<?
error_reporting(0);
include ("configs/config.php");

setcookie("referalUID",intval($_GET["id"]),time()+3600);

include ('index.php');
?>