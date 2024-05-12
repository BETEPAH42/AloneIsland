<?php
namespace Worlds;

use SQL;
use Worlds\Weather;
use Worlds\Seasons;

class World 
{
    public $weather;
    public $weatherchange;
    public Weather $weatherData;
    public Seasons $seasonData;
    protected static $_instance = null;

    private function __construct() 
    { 
        $data = SQL::q1("SELECT * FROM world");
        $this->weather = $data['weather'];
        $this->weatherchange = $data['weatherchange'];
        $this->seasonData = new Seasons();
        $this->getWeather();
        return $this;
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;  
        }
        return self::$_instance;
    }

    public function setWeatherChange($data)
    {
        print_r($data,true);
        $this->weatherchange = $data;
        return $this;
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