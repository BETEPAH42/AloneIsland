<?php
namespace Worlds;

use SQL;
use Worlds\Weather;

class World 
{
    public $weather, $weatherchange;
    public $weatherData;
    protected static $_instance = null;

    private function __construct() 
    { 
        $data = SQL::q1("SELECT * FROM world");
        $this->weather = $data['weather'];
        $this->weatherchange = $data['weatherchange'];
        $this->getWeather();
        return $this;
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;  
        }
        return self::$_instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    } 
   
    protected function getWeather()
    {
        $weather = new Weather($this->weather);
        $this->weatherData = $weather;
    }

}