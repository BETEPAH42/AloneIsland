<?php

namespace Quests;

use SQL;

class Quests 
{

    protected $quests;

    public function __construct()
    {
        $this->quests = SQL::q("SELECT * FROM quest");
    }

    public function getQuest($id)
    {
        return $this->getData($id);
    }

    public function getQuestLocation($x, $y)
    {
        return $this->thisQuest($x, $y);
    }

    protected function getData ($id)
    {
        foreach($this->quests as $quest) {
            if($quest['id'] == $id) {
                return $quest;
            }
        }
        return false;
    }

    protected function thisQuest($x, $y) 
    {
        foreach($this->quests as $quest) {
            if($quest['lParam'] == $x && $quest['zParam'] == $y && !$quest['finished']) {
                return $quest;
            }
        }
        return false;
    }
}
