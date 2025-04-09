<?php
namespace Worlds;

use SQL;
use Worlds\World;

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
            $weather = SQL::q1("SELECT weather FROM world LIMIT 1");
            self::$_instance = new self($weather['weather']);
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