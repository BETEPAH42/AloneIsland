<?php

echo '<div style="width: 20%;"><a class=bga href=main.php?go=administration>Назад в меню</a></div>';

// use ClassPerson\User;
// use UserException;
// use ClassWeapons\Weapon;
// use ClassPerson\Person;
use ClassMenus\Menus;
try {
//    SQL::q1('CREATE TABLE menus 
//         (
//             id INT PRIMARY KEY AUTO_INCREMENT,
//             name VARCHAR(50) NOT NULL,
//             type VARCHAR(30),
//             url VARCHAR(50) NOT NULL,
//             active BOOLEAN DEFAULT(FALSE) NOT NULL
//         );');
// SQL::q1('INSERT INTO menus SET name="Тестовая", type="admin", url="main.php?go=test", active=true;');

   dd(new Menus());
}
catch (Exception $e)
{
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}
catch (Error $er)
{
    echo "<pre>";
    var_dump($er);
    echo "</pre>";
}