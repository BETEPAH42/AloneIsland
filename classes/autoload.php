<?php
function autoload($name)
{
    $class = str_replace("\\","/",$name);
    $file = __DIR__ . "/{$class}.php" ;
    if(file_exists($file))
    {
        require_once $file;
    }

}

spl_autoload_register('autoload');
