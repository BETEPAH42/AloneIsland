<?php
include_once './classes/sql.php';
function set_vars($vars, $uid)
{
    if (!$uid) {
        global $pers;
        $uid = $pers["uid"];
    }
    if ($vars) {
        // SQL::q("UPDATE users SET ".$vars." WHERE uid=".intval($uid)."");
        sql::q("UPDATE users SET " . $vars . " WHERE uid=" . intval($uid) . "");
        return true;
    } else
        return false;
}
function aq($arr)
{
    echo "Start<br>";

    $pconnect = SQL::q1("SELECT * FROM `users` WHERE `uid`='" . $arr['uid'] . "'");
    // global $resault_aq;
    var_dump($pconnect);
    $res = "";
    foreach ($pconnect as $key => $value) {

        if (
            $pconnect[$key] <> $arr[$key]
            and $key <> 'user'
            and $key <> 'smuser'
            and $key <> 'uid'
            and $key <> 'refr'
            and $key <> 'cfight'
            and $key <> 'lastom'
            and $key <> 'pol'
            and !is_integer($key)
            and $key <> ''
        ) {

            $res .= "`" . $key . "`='" . $arr[$key] . "',";
            // echo $res;
        }
    }
    $res = substr($res, 0, strlen($res) - 1);
    // $resault_aq = $res;
    var_dump($res);
    echo "Ends<br>";
    return $res;
}
// global $pers;
$pers = SQL::q1("SELECT * FROM users WHERE uid=1");
// var_dump(aq($pers['uid']));
$as = SQL::q("SELECT *, count(*) as count FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . time() . " and (turn_esttime<=" . $pers["f_turn"] . ");");
echo "<pre>";
// var_dump($as);
$count = 0;
//$autoAS = Array();
$modified = 0;
foreach ($as as $a) {
    echo "----<br>";

    $count++;
    $params = explode("@", $a["params"]);
    foreach ($params as $par) {
        echo "+++++<br>";
        var_dump($par);
        $p = explode("=", $par);
        var_dump($p);
        if ($p[0] <> 'cma' and $p[0] <> 'chp' and intval($p[1]) != 0) {
            echo $pers[$p[0]] . "<br>";
            $pers[$p[0]] -= $p[1];
            $modified = 1;
        }
    }
    if ($a["special"] == 14) {
        $a["image"] = 68;
        $a["params"] = '';
        $a["esttime"] = 1800;
        $a["name"] = 'Отдышка после шахты';
        $a["special"] = 15;
        light_aura_on($a, $pers["uid"]);
    }
    /*	if($a["autocast"])
			$autoAS[] = $a["autocast"];*/
}
if ($modified) {
    echo "пришли сюда<br>";

    if (true) {
        echo "---><br>";
        SQL::q("DELETE FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . time() . " and (turn_esttime<=" . $pers["f_turn"] . ") and autocast=0");
    }
} elseif ($count) {
    echo "+++><br>";
    SQL::q("DELETE FROM p_auras WHERE uid=" . $pers["uid"] . " and esttime<=" . time() . " and (turn_esttime<=" . $pers["f_turn"] . ") and autocast=0");
}
