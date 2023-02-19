<?php
namespace ClassWeapons;

use SQL;

class WeaponUser extends Weapons
{
    protected $wpUser;
    public function __construct($uid)
    {
        $arrWp = SQL::q("SELECT * FROM wp WHERE uidp = ? ORDER by id;",[$uid]);
        $this->wpUser = $this->structureWp($arrWp);
    }

    public function structureWp($arrayWp) 
    {
        $resWp = [];
        foreach ($arrayWp as $wp) {
            $resWp [$wp['id']] = $wp;
        }
        return $resWp;
    }

    public function getAllWp ()
    {
        return $this->wpUser;
    }

    public function inWp($wId)
    {
        foreach ($this->wpUser as $wp) {
            if($wp['id_in_w'] = $wId) return true;
        }
        return false;
    }
}