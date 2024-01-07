<?php
namespace ClassMenus;

use SQL;

class Menus 
{
    public array $menus;
    public function __construct()
    {
        $data = SQL::q('SELECT * FROM menus');
        $this->menus = $data;
    }
}