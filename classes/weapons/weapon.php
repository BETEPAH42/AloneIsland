<?php 

namespace ClassWeapons;

use SQL;
use ClassWeapons\Weapons;

class Weapon extends Weapons 
{
    public array $weapon;

    public function __construct($id)
    {
        $this->weapon = SQL::q1("SELECT * FROM weapons WHERE id = ?",[$id]);
    }

    function getAllParamWeapon() 
    {
        return $this->weapon;
    }

    public function getUserWeapon($uid,$id)
    {
        
    }
}
