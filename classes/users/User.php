<?php

namespace ClassPerson;

use SQL;
use ClassPerson\Person;
use ClassWeapons\Weapon;
use ClassWeapons\WeaponUser;

class User extends Person
{
    protected $user = NULL;
    protected $nick = NULL;
    protected $lvl = NULL;
    private $userAllParam = NULL;
    public $WpUser = NULL;

    public function __construct($id)
    {
        $params = SQL::q1("SELECT * FROM users WHERE uid = ?;",[$id]);
        $this->uid = $params['uid'];
        $this->lvl = $params['level'];
        $this->nick = $params['user'];
        $this->userAllParam = $params;
        $this->WpUser = $this->WpUser();
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLevel ()
    {
        return $this->lvl;
    }

    public function getUID ()
    {
        return $this->uid;
    }

    public function getNick ()
    {
        return $this->nick;
    }
}
