<?php 

namespace ClassPerson;

use SQL;
use ClassWeapons\WeaponUser;
use PersonException;

class Person 
{
    public string $nicname;
    public int $health;
    public int $mana;
    public int $t_health;
    public int $t_mana;
    public int $level;
    public int $uid;
    public int $experience;
    public string $location;
    public string $picture;
    public $weapons;
    public array $error;
    private array $allDatas;

    public function __construct($login, $password)
    {
        $data = $this::getPersonArray($login, $password);
        // var_dump($data);
        if($data) {
            $this->nicname = (string)$data['user'];
            $this->level = (int)$data['level'];
            $this->uid = (int)$data['uid'];
            $this->experience = (int)$data['exp'];
            $this->health = (int)$data['hp'];
            $this->t_health = (int)$data['chp'];
            $this->mana = (int)$data['ma'];
            $this->t_mana = (int)$data['cma'];
            $this->location = $data['location'];
            $this->picture = $data['obr'];
            $this->allDatas = $data;
            $this->weapons = $this->WpUser();
        } else {
            $this->error = [
                'message' => 'Пользователь не нейден, вероятно указан неправильный логин или пароль',
                'code' => 401,
            ];
        }

    }

    static function getPersonArray($login, $password)
    {
        try {
            $user = SQL::q1("SELECT * FROM users WHERE `user`='" . addslashes($login) . "' and `pass`='" . ($password) . "';");
            return $user;
        } catch (PersonException $e) {
            echo "Ошибка получения сведений о пользователях!";
        }
        return [];
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getNick ()
    {
        return $this->nicname;
    }

    public function getAllDatas ()
    {
        return $this->allDatas;
    }

    protected function WpUser()
    {
        // echo "<pre>".print_r($this->uid,true)."</pre>";
        return new WeaponUser($this);
    }
}
