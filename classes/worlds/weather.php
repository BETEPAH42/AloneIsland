<?php
namespace Worlds;

use SQL;

class Weather 
{
    public $weather;
    public function __construct($idWeather)
    {
        $data = SQL::q1("SELECT * FROM weather WHERE id=" .$idWeather . "");
        $this->weather = $data;
    }  
}