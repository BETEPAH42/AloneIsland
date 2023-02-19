<?php

namespace ClassPerson;

use SQL;
use ClassPerson\Person;
use ClassWeapons\Weapon;
use ClassWeapons\WeaponUser;

class User extends Person
{
    public int $uid;
    private $user;
    public $nick;
    public array $WpUser;

    public function __construct($id)
    {
        $this->uid = $id;
        $this->user = SQL::q1("SELECT * FROM users WHERE uid = ?;",[$this->uid]);
        $this->WpUser = $this->WpUser();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLevel ()
    {
        return $this->user['level'];
    }

    public function WpUser()
    {
        $wp = new WeaponUser($this->uid);
        return $wp->getAllWp();
    }
}
