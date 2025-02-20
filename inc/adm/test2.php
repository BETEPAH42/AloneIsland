<?php
include_once 'back_button.php';
?>
<br>
<div style="margin: 15px;">
<?
use Worlds;
use Worlds\World;
use Worlds\Weather;

$world = World::getInstance();
$weather = Weather::getInstance();
echo "<pre>";
var_dump($weather);
// var_dump($world);
echo "</pre>";
?>
</div>