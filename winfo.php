<head>
	<title>Информация о предмете</title>
	<style>
		/* В данном селекторе мы задаем ширину, высоту, обводку, позиционирование, фоновый цвет, цвет и размер теней */

		.box9 {

			width: 500px;
			min-height: 300px;
			position: absolute !important;
			left: 50%;
			top: 50%;
			border: 1px solid rgba(0, 0, 0, 0.6);
			-webkit-border-radius: 20px;
			-moz-border-radius: 20px;
			border-radius: 20px;
			background: white;
			-webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
			-moz-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.4);
			box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
			margin: -150px 0 0 -250px;
		}

		/* Здесь определяется обводка вокруг рамки */
		.box9:before {
			content: '';
			width: 110%;
			left: 0;
			height: 111%;
			z-index: -1;
			position: absolute;
			-webkit-border-radius: 20px;
			-moz-border-radius: 20px;
			border-radius: 20px;
			border: 1px solid rgba(0, 0, 0, 0.2);
			background: rgba(0, 0, 0, 0.0);
			-webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
			-moz-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
			box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
			-webkit-transform: translate(-5%, -5%);
			-moz-transform: translate(-5%, -5%);
			-o-transform: translate(-5%, -5%);
			transform: translate(-5%, -5%);
		}

		.className {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin: -100px 0 0 -150px;
		}
	</style>
</head>

<body>
	<?
	include('inc/functions.php');
	error_reporting(E_ALL);
	include("configs/config.php");

	if (isset($_GET["w"])) $weap = SQL::q1("SELECT * FROM weapons WHERE id=" . intval($_GET["w"]));
	if (isset($_GET["w_p"])) $weap = SQL::q1("SELECT * FROM wp WHERE id=" . intval($_GET["w_p"]));
	if (!$weap) echo "<div class='box9'><center><br><font size=5>Такой вещи не существует</font></center></div>";
	else {
	?><center>
			<div class='box9'>

				<table cellspacing='0' border='0' width='500' height='300' align='center' background='/images/weapons/bg_weap.png'>
					<tr height=20>
						<td rowspan=2 width=100>
							<center><img src='images/weapons/<?php echo "" . $weap['image'] . ""; ?>'></center>
						</td>
						<td><b><i>Название:</b> <?php echo "" . $weap['name'] . ""; ?></i></td>
					</tr>
					<tr height='75'>
						<td valign='top'><b>Тип:</b> <i><?
														echo type_names($weap['type']);
														echo "</i>. <b>Вид:</b> <i>";
														echo stype_names($weap['stype']);
														echo "<br>Уровень: " . $weap['tlevel'] . ".<br>Прочность: " . $weap['max_durability'] . "/{$weap['max_durability']}.<br>";
														?></i>
							<!--<br><b>Время жизни: </b><i>_____</i></center></td>-->
					</tr>
					<tr height=20>
						<td colspan=2>
							<center><b>Свойства предмета:</b></center>
						</td>
					</tr>
					<tr height=75>
						<td colspan='2' valign='top'>
							<? ?>
						</td>
					</tr>
					<tr height=20>
						<td colspan=2>
							<center>ХАРАКТЕРИСТИКА ПРЕДМЕТА:</center>
						</td>
					</tr>
					<tr height=75>
						<td colspan='2' valign='top'>Тут характеристика</td>
					</tr>
				</table>
			</div>
		</center>
	<?
	}
	?>
</body>
<?
$rune3 = SQL::q1("SELECT * FROM weapons WHERE id='5' LIMIT 1");
foreach ($rune3 as $key => $value4) {
	if (!$value4 or $key > 0) continue;
	//echo $key."->".$value4."<br>";
	$re .= $key . "=" . $value4 . "<br>";
}
echo $rune3['6'];
echo $re;
?>