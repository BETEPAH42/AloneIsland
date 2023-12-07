<?php

namespace ClassPerson;

class PersonAbilities
{
    public $power;
    public $reaction;
    public $lucky;
    public $health;
    public $intellect;
    public $willpower;
    public $free_stats;

    function __construct(Person $person)
    {
        $this->power = [
            'name' => 'Сила',
            'value' => $person->getAllDatas()['s1']
        ];
        $this->reaction = [
            'name' => 'Реакиция',
            'value' => $person->getAllDatas()['s2']
        ];
        $this->lucky = [
            'name' => 'Удача',
            'value' => $person->getAllDatas()['s3']
        ];
        $this->health = [
            'name' => 'Здоровье',
            'value' => $person->getAllDatas()['s4']
        ];
        $this->intellect = [
            'name' => 'Интелект',
            'value' => $person->getAllDatas()['s5']
        ];
        $this->willpower = [
            'name' => 'Сила воли',
            'value' => $person->getAllDatas()['s6']
        ];
        $this->free_stats = [
            'name' => 'Свобоные статы',
            'value' => $person->getAllDatas()['free_stats']
        ];
    }
}
