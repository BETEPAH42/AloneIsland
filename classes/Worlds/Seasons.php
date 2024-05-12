<?php
namespace Worlds;

use SQL;
use DateTime;

class Seasons 
{
    public $season;
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

    public function __construct() 
    {
        $this->season = self::getSeason();
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