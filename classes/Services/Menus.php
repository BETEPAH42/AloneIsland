<?php
namespace Services;

use SQL;

class Menus 
{
    public array $menus;
    public array $extFields = [
        'name',
        'type',
        'url',
    ];
    protected string $field = 'menus';
    protected string $type = 'admin';

    public function __construct()
    {
        $data = SQL::q("SELECT * FROM $this->field;");
        $this->menus = $data;
    }

    public function add ($array) 
    {
        $array['type'] = $this->type;
        if(empty($array['active'])) {
            $array = array_merge($array,['active'=>0]);
        }
        if($this->validationFields($array)) {
            $keys = implode(',',array_keys($array));
            $values = implode('\',\'',$array);
            SQL::q("INSERT INTO $this->field ($keys) VALUES ('$values');");
            return true;
        }
        return false;
    } 

    protected function delete ($id) 
    {
        SQL::q("DELETE FROM $this->field WHERE id=" . $id . ";");
    } 

    protected function validationFields($array)
    {
        foreach ($this->extFields as $key) {
                    pre([$key,!in_array($key,$array) || empty($array[$key])]);
            if(!in_array($key,$array) || empty($array[$key])) {
                return false;
            }
        }
        return true;
    }
}