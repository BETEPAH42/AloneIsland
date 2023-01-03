<?php

echo "<center>Разработка административного раздела для трав!!!</center>";
$travs = SQL::q("SELECT * FROM `herbals` ORDER BY id;");
if (@$_GET["giveHerbal"]) {
    $uid = SQL::q1("SELECT uid FROM users WHERE user='" . $_POST["nickfor"] . "'")['uid'];
    if(insert_herbal($_GET["giveHerbal"], $uid)){
            echo "Удачно выдано";
    }
}
?>
<script language=JavaScript src='/js/adm_new.js' type="text/javascript"></script>
<script>
	function give(id) {
		init_main_layer();
		ml.innerHTML += '<form action="main.php?giveHerbal=' + id + '" method=POST>КОМУ: <input class=login type=text value="" name=nickfor size=20><hr><input class=login type=submit value=[OK]></form>';
	}
</script>

<div style="width: 80%;margin: 0 auto;display: flex;">
    <div style="width: 20%;"><a class=bga href=main.php?go=administration>Назад в меню</a><br>Под сортировку</div>
    <div style="width: 80%;">
    <div style="height: 20px; width: 100%; display: flex;flex-wrap: nowrap;flex-direction: row;align-content: center;justify-content: center;align-items: center;">
            <div style="width: 10%;">
                <b>Номер</b> 
            </div>
            <div style="width: 50%;">
                <b>Наименование</b>
            </div>
            <div style="width: 20%;">
                <b>Изображение</b> 
            </div>
            <div style="width: 20%;">
                <b>Действия</b> 
            </div>
        </div>
        <? foreach($travs as $key=>$trava) :?>
        <div style="width: 100%; display: flex;flex-wrap: nowrap;flex-direction: row;align-content: center;justify-content: center;align-items: center; border: 1px; background:grey;">
            <div style="width: 10%;">
                <?=$trava["id"];?>
            </div>
            <div style="width: 50%;">
                <?=$trava["name"];?>
            </div>
            <div style="width: 20%;">
                <img src="/images/weapons/herbals/<?=$trava["image"];?>.gif" alt="" srcset="">
            </div>
            <div style="width: 20%;">
                <input type=button class=login onclick="give(<?=$trava["id"];?>);" value="Выдать">
            </div>
        </div>
        <?endforeach;?>
    </div>
</div>