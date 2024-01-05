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
    protected int $experience; /** Боевой опыт */
    protected int $experiencePeace; /** Мирный опыт */
    public string $location;
    public int $x;
    public int $y;
    public int $xf;
    public int $yf;
    protected string $picture;
    public $abilities;
    public $skills;
    public $weapons;
    protected $speed;
    public array $error;
    public array $allDatas;

    public function __construct($uid)
    {
        $data = $this->getPersonArray($uid);
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
            $this->x = (int) $data['x'];
            $this->y = (int) $data['y'];
            $this->xf = (int) $data['xf'];
            $this->yf = (int) $data['yf'];
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

    protected static function getPersonArray($uid)
    {
        try {
            $user = SQL::q1("SELECT * FROM users WHERE `uid`=" . $uid . ";");
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

    public function getLevel ()
    {
        return $this->level;
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

    public static function GetIdByLoginPass($login, $password) 
    {
        try {
            $user = SQL::q1("SELECT uid FROM users WHERE `user`='" . addslashes($login) . "' and `pass`='" . ($password) . "';");
            return $user['uid'];
        } catch (PersonException $e) {
            echo "Ошибка получения сведений о пользователях!";
        }
        return [];
    }
}
