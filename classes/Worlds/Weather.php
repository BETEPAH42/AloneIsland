<?php
namespace Worlds;

use SQL;

class Weather 
{
    protected $weather;
    public function __construct($idWeather)
    {
        $data = SQL::q1("SELECT * FROM weather WHERE id=" .$idWeather . "");
        $this->weather = $data;
        return $this;
    }  

    public function newWeather()
    {
        $this->weather = SQL::q1("SELECT * FROM weather ORDER BY RAND()");
        return $this;
    }

    public function getWeather()
    {
        return $this->weather;
    }

    public function chengeWeather($time)
    {
        if ($time < time()) {
            $this->newWeather();
            return $this;
        }
        return $this;
    }
}