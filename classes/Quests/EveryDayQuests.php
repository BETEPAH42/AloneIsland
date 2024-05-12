<?php
namespace Quests;

use Quests\Quests;

class EveryDayQuests extends Quests
{
    protected $quest;

    public function showQuest($x, $y)
    {
        $this->quest = $this->getQuestLocation($x, $y);
        // return $this->quest;
    }

    function getQuestOnLocation($x, $y)
    {
        $this->quest = $this->getQuestLocation($x, $y);
        return $this;
    }

    public function getSParam()
    {
        return $this->quest["sParam"];
    }
}