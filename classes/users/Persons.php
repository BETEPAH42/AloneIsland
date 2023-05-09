<?php 

namespace ClassPerson;

use Exception;
use SQL;

class Persons 
{
    private array $persons;

    public function __construct()
    {
        $this->persons = $this->getPersonsArray();
    }

    public function getPersonsArray() : array
    {
        try {
            $result = [];
            $users = SQL::q("SELECT * FROM users ORDER by uid;");
            foreach ($users as $user) {
                $result [(int)$user["uid"]] = $user; 
            }
            return $result;
        } catch (Exception $e) {
            echo "Ошибка получения сведений о пользователях!";
        }
        return [];
    }

    public function getPersonByUid (int $uid) {
        return isset($this->persons[$uid])? $this->persons[$uid] : [];
    }

    public function getPersons()
    {
        return $this->persons;
    }

}