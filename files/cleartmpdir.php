<?php
include_once "rmdirr.php";
function cleartmpdir()
{
    $t = time() - 36000;
    $r = sql::q("SELECT * FROM `files_tmp` WHERE `lastused` < '" . $t . "';");
    if ($r) {
        foreach ($r as $a) {
            rmdirr(".tmp/" . $a[1]);
        }
    }
    sql::q("DELETE FROM `files_tmp` WHERE `lastused` < '" . $t . "';");
}
