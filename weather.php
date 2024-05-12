<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'classes/autoload.php';
// include_once "configs/config.php";
include_once 'inc/functions.php';

use Worlds\World;
$worlds = World::getInstance();
$weather = $worlds->weatherData;
$season = $worlds->seasonData;

$changew  = 'НЕИЗВЕСТНО';
if ($worlds->weatherchange > time()) {
	$changew = tp($worlds->weatherchange - time());
} else {

	$weather->newWeather();
	// $ww = SQL::q1("SELECT * FROM weather WHERE season=5 or season=" . $season_id . " ORDER BY RAND()");
	$worlds->setWeatherChange(time() + rand(1, 3) * $weather->getWeather()["time"]);
	$changew = tp($worlds->weatherchange - time());
	SQL::q("UPDATE world SET weather = " . $weather->getWeather()["id"] . ", weatherchange = " . $worlds->weatherchange . "");
	say_to_chat("a", "Произошла смена погоды.", 0, '', '*');
	SQL::q("UPDATE nature SET fish_population=fish_population+fish_population*0.5+" . rand(0, 100) . " WHERE fishing>0 and fish_population<600");
}

$changes =  tp( $season->season['nextSeason'] - time())
/*
	$w = $weather["weather"];
	if ($w == 1)
	{
		Play("Summer","hot");
	}
	if ($w == 2)
	{
		Play("Summer","rain");
	}
	if ($w == 3)
	{
		Play("Summer","hrain");
	}
	if ($w == 4)
	{
		Play("Summer","wind");
	}
	if ($w == 5)
	{
		Play("Summer","storm");
	}
	if ($w == 6)
	{
		Play("Summer","fog");
	}
	if ($w == 7)
	{
		Play("Summer","gsnow");
	}
	if ($w == 8)
	{
		Play("Summer","snow");
	}
	
	*/
// if (date("H") > 21 or date("H") < 7) $ww["id"] += 10;
?>

<meta HTTP-EQUIV="Page-Enter" CONTENT="BlendTrans(Duration=0.5)">
<link href="css/main.css" rel=STYLESHEET type="text/css">

<body style="background-color:transparent;">
	<center>
		<br>
		<br>
		<table border="0" width="95%" cellspacing="0" cellpadding="0" style="border-bottom-style: solid; border-top-style: solid; border-top-width: 3px; border-bottom-width: 2px; border-color:#777799">
			<tr>
				<td align="center" valign="bottom"></td>
				<td align="center"><a href=ch.php class=bga>НАЗАД</a></td>
				<td align="center" valign="bottom"></td>
			</tr>
			<tr>
				<td align="center" class="bnick" height="21">&nbsp;</td>
				<td align="center" class="dark" height="21"><b class=user><?= $weather->getWeather()["name"]; ?></b>[<?= $changew; ?>]<br>
					<img border="0" src="images/weather/seasons/<?= $season->season["id"]; ?>.gif" width="100" height="100"><img border="0" src="images/weather/<?= $season->season["id"]; ?>.gif" width="100" height="100"><br><?= str_replace(';', '<br>', $season->season["describe"]); ?>
				</td>
				<td align="center" class="bnick" height="21">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" bgcolor="#AAAAAA" height=4></td>
				<td align="center" bgcolor="#AAAAAA" height=4></td>
				<td align="center" bgcolor="#AAAAAA" height=4></td>
			</tr>
			<tr>
				<td align="center" class="bnick" valign="top"></td>
				<td align="center" class="about"><b class=user><?= $season->season["name"]; ?></b>[<?= $changes ?>]<br>
				</td>
				<td align="center" class="bnick" valign="top"></td>
			</tr>
		</table>
	</center>
	<script>
		var interv = setTimeout("location = 'ch.php?rand=" + Math.random() + "'", 15000);
	</script>
</body>