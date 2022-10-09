<?
// создание ЖИВЦА
if (@$_POST["liver"])
{	
insert_wp(15649,UID,-1,0,$pers["user"],$_POST["liver_w"]);
SQL::q("DELETE FROM `wp` WHERE `id` = '".$_POST["liver"]."' LIMIT 1;");
//SQL::q();//сделать удаление записи по рыбке и готово
say_to_chat('s','Живец в количестве 1 шт. помещён в рюкзак.',1,$pers["user"],'*',0);
}
