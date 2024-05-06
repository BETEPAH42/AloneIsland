<?php
require_once '../classes/autoload.php';
use Services\Menus;
$menu = new Menus();
?>
<div class='container_menu_admin_column container-fluid'>
<? foreach($menu->menus as $item):?>
    <div class="element_column" data-edit="<?=$item['id']?>">
        <div class="number"><?=$item['id']?>.</div>
        <input class="text" name="name" type="text" placeholder="Наименование пункта" value="<?=$item['name']?>" require>
        <select name="type" id="type">
            <option disabled>Выбор меню</option>
            <option value="admin" <?=$item['type'] == 'admin' ?'selected':''?>>admin</option>
            <option value="header" <?=$item['type'] == 'header' ?'selected':''?>>header</option>
            <option value="build" <?=$item['type'] == 'build' ?'selected':''?>>build</option>
        </select>
        <input class="text" type="text" placeholder="Наименование пункта" value="<?=$item['url']?>" require>
        <input class="checkbox" type="checkbox" placeholder="Активность" <?= $item['active']? "checked" : "";?> require>
        <div style="display: flex;">
            <div class="button button_edit">Редактировать</div>
            <div class="button button_save disabled" data-id="<?=$item['id']?>">Сохранить</div>
        </div>
    </div>
<? endforeach;?>
</div>