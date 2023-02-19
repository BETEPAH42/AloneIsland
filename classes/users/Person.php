<?php 

namespace ClassPerson;

use SQL;

class Person 
{
    private $uid;
    private array $person;

    public function __construct($id)
    {
        $this->uid = $id;
        $this->person = SQL::q1("SELECT * FROM users WHERE uid = ?;",[$this->uid]);
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getUser()
    {
        return $this->person;
    }

    public function getNick ()
    {
        return $this->person['user'];
    }
}
