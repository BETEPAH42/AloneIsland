<?php 

namespace ClassPerson;

use SQL;
use ClassWeapons\WeaponUser;
use PersonException;

class Person 
{
    private string $nicname;
    protected int $health;
    protected int $mana;
    protected int $t_health;
    protected int $t_mana;
    protected int $level;
    public int $uid;
    protected int $experience;
    protected string $location;
    protected string $picture;
    public $abilities;
    public $skills;
    public $weapons;
    protected $speed;
    public array $error;
    private array $allDatas;

    public function __construct($login, $password)
    {
        $data = $this::getPersonArray($login, $password);
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
            $this->abilities = $this->getUserAbilities();
            $this->skills = $this->getUserSkills();
            $this->weapons = $this->getUserWpDb();
            
        } else {
            $this->error = [
                'message' => 'Пользователь не нейден, вероятно указан неправильный логин или пароль',
                'code' => 401,
            ];
        }

    }

    private static function getPersonArray($login, $password)
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

    protected function getUserWpDb()
    {
        return new WeaponUser($this);
    }

    public function getUserWp()
    {
        return $this->weapons;
    }

    protected function getUserSkills()
    {

        return new PersonSkills($this);
    }

    protected function getUserAbilities()
    {

        return new PersonAbilities($this);
    }
}
