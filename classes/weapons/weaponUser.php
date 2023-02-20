<?php
namespace ClassWeapons;

use SQL;

class WeaponUser extends Weapons
{
    public $wpUser;
    private $uid;
    public function __construct($uid)
    {
        $this->uid = $uid;
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
            if($wp['id_in_w'] == $wId) return true;
        }
        return false;
    }

    public function inWpByName($wName)
    {
        foreach ($this->wpUser as $wp) {
            if($wp['name'] == $wName) return true;
        }
        return false;
    }

    public function removeWp($wId)
    {
        $delete = SQL::q1("DELETE FROM wp WHERE uidp= ? AND id = ? AND weared = 0 LIMIT 1;",[$this->uid,$wId]);
        if($delete) return true;
        else return false;
    }

    public function removeWpByName($wName)
    {
        $delete = SQL::q1("DELETE FROM wp WHERE uidp= ? AND name = ? AND weared = 0 LIMIT 1;",[$this->uid,$wName]);
        if($delete) return true;
        else return false;
    }
}