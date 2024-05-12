<?php 

namespace Weapons;

use SQL;

class Weapons 
{
    private array $weapons;
    public array $weapon;

    public function __construct()
    {
        $this->weapons = SQL::q("SELECT * FROM weapons ORDER by id");
    }

    public function showAllWeapons() 
    {
        return $this->weapons;
    }
}