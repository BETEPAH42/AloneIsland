<?php
namespace Worlds;

use SQL;

class Weather 
{
    public $weather;
    protected $weatherChange;
    protected static $_instance = null;

    private function __construct($idWeather)
    {
        $data = SQL::q1("SELECT * FROM weather WHERE id=" .$idWeather . "");
        $this->weather = $data;
        return $this;
    }  

    public static function getInstance() {
        // var_dump((World::getInstance())->getWeatherId());
        // die();
        if (self::$_instance === null) {
            self::$_instance = new self((World::getInstance())->getWeatherId());  
        }
        return self::$_instance;
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