<?php 

namespace ClassPerson;

Class PersonAbilities
{
    public $power;
    public $reaction;
    public $lucky;
    public $health;
    public $intellect;
    public $willpower;

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
    }
}