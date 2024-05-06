<?php
use Services\Menus;

$menu = new Menus();

$_max = SQL::q1("SELECT max_online,time_max_online FROM `configs` LIMIT 0,1");
$max = $_max["max_online"];
$tmax = $_max["time_max_online"];
$online = "Макс. Онлайн: <b>" . $max . "</b> | <span class=gray>" . date("d.m.Y H:i", $tmax) . "</span>";
?>
<center width=100% class=but>
	<br>
	<b>Возможности министра</b>
	<br>
	Должность: Создатель мира назначил вас на должность <b><?= $priv["status"] ?></b>
	<br>
	<?= $online ?>
	<p><?= $abb ?></p>
	<div class='container_menu_admin container-fluid'>
		<? foreach($menu->menus as $item):?>
			<a class="element_admin_menu" id="<?=$item['id']?>" href="<?=$item['url']?>">
				<?=$item['name']?>
			</a>
		<? endforeach;?>
		<!-- <a class="add_new_element element_admin_menu">
			Добавить новый пункт
		</a> -->
	</div>
<?php

	if ($_POST) {
		$pass = rand(1000, 9999);
		if (sql::q("UPDATE users SET pass=MD5('" . $pass . "') WHERE user='" . $_POST["user"] . "'"))
			echo $_POST["user"] . " ::  Пароль успешно изменён на " . $pass;
	} ?>
	<div class=but2>
		<form action=main.php method=post>Смена пароля:<br> Логин 
			<input class=login type=text name=user>
			<input type=submit value=OK>
		</form>
	</div>
</center>
