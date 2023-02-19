<?php
namespace ClassQuests;

use ClassQuests\Quests;

class EveryDayQuests extends Quests
{
    public $quest;

    public function showQuest($x, $y)
    {
        $this->quest = $this->getQuestLocation($x, $y);
        return $this->quest;
    }

    
}