<?php

namespace Users;

use SQL;
use Exceptions\UserException;
use Users\Person;

class User
{
    protected $uid = NULL;
    protected $person = NULL;

    public function __construct($login, $password)
    {
        $userUid = $this->getPersonArray($login, $password);
        if($userUid) {
            $this->uid = $userUid['uid'];
            $this->person = new Person($userUid['uid']);
        }
    }

    protected static function getPersonArray($login, $password)
    {
        try {
            $user = SQL::q1("SELECT uid FROM users WHERE `user`='" . addslashes($login) . "' and `pass`='" . ($password) . "';");
            return $user;
        } catch (UserException $e) {
            echo "Ошибка получения сведений о пользователях!";
        }
        return [];
    }

    public function getUser()
    {
        return $this->person;
    }

    // public function getLevel ()
    // {
    //     return $this->lvl;
    // }

    public function getUID ()
    {
        return $this->uid;
    }

    // public function getNick ()
    // {
    //     return $this->nick;
    // }
}
