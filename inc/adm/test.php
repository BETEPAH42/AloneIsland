<?php

echo '<div style="width: 20%;"><a class=bga href=main.php?go=administration>Назад в меню</a></div>';

use ClassPerson\User;
use ClassWeapons\Weapon;
use ClassPerson\Persons;
try {
    // $user = new User(UID);
    $users = new Persons();
    echo "<pre>";
    // var_dump($user->WpUser->inWpByName('Кориандр'));
    var_dump($users->getPersonByUid(300));
    echo "</pre>";
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