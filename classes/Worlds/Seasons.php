<?php
namespace Worlds;

use SQL;
use DateTime;
use Worlds\Weather;

class Seasons 
{
    public $season;
    public Weather $weather;
    public $weatherchange;
    protected static array $nameSeason = [
        [
            "id" => 1,
            "name" => "Зима",
            "numberMonth" => [12,1,2]
        ],
        [
            "id" => 2,
            "name" => "Весна",
            "numberMonth" => [3,4,5]
        ],
        [
            "id" => 3,
            "name" => "Лето",
            "numberMonth" => [6,7,8]
        ],
        [
            "id" => 4,
            "name" => "Осень",
            "numberMonth" => [9,10,11]
        ]
    ];
    protected static $_instance = null;

    public function __construct() 
    {
        $this->season = self::getSeason();
        $this->getWeather();
    }

    public function getWeather()
    {
        // $this->weather = Weather::getInstance();
    } 

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;  
        }
        return self::$_instance;
    }

    protected static function getSeason()
    {
        $date = new DateTime();
        foreach (self::$nameSeason as $key=>$season) {
            if(in_array((int)$date->format("m"),$season["numberMonth"])) {
                $season["nextSeason"] = mktime(0,0,0,(3*(int)$season['id']),1,(new DateTime())->format("Y"));
                return $season;
            }
        }
        return false;
    }
}