<?php
// use Services\Menu;
function autoload($name)
{
    $class = str_replace("\\","/",strtolower($name));
    $file = __DIR__ . "/{$class}.php" ;
    var_dump($file);
    // exit;
    if(file_exists($file))
    {
        require_once $file;
    }

}

spl_autoload_register('autoload');

// $tt = new Menu();