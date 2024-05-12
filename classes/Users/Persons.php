<?php

namespace Users;

use Exception;
use SQL;

class Persons
{
    private array $persons;

    public function __construct()
    {
        $data = $this::getPersonsArray();
    }

    static function getPersonsArray(): array
    {
        try {
            // $result = [];
            $users = SQL::q("SELECT * FROM users ORDER by uid;");

            return $users;
        } catch (Exception $e) {
            echo "Ошибка получения сведений о пользователях!";
        }
        return [];
    }

    public function getPersonByUid(int $uid)
    {
        return isset($this->persons[$uid]) ? $this->persons[$uid] : [];
    }

    public function getPersons()
    {
        return $this->persons;
    }
}
