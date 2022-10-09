<?
error_reporting(0);

include("../configs/config.php");

$vsego = SQL::q1("SELECT COUNT(user) as count FROM `users` WHERE `online`='1'")['count'];

echo "Всего персонажей " . $vsego . "\n";

$res = SQL::q("SELECT sign,user,aura,level,state,diler FROM `users` WHERE `online`='1' ORDER BY `level` DESC");

foreach ($res as $row) {
    $i++;
    $tr = '';
    if (strpos(" @" . $row["aura"], "@l_tr1|") <> 0) $tr .= "Легкая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr2|") <> 0) $tr .= "Легкая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr3|") <> 0) $tr .= "Легкая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr4|") <> 0) $tr .= "Легкая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr5|") <> 0) $tr .= "Средняя травма.";
    if (strpos(" @" . $row["aura"], "@l_tr6|") <> 0) $tr .= "Средняя травма.";
    if (strpos(" @" . $row["aura"], "@l_tr7|") <> 0) $tr .= "Средняя травма.";
    if (strpos(" @" . $row["aura"], "@l_tr8|") <> 0) $tr .= "Тяжёлая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr9|") <> 0) $tr .= "Тяжёлая травма.";
    if (strpos(" @" . $row["aura"], "@l_tr10|") <> 0) $tr .= "Тяжёлая травма.";
    if ($row["sign"] <> 'none' and $row["sign"] <> '')
        $clan = SQL::q1("SELECT name FROM clans WHERE sign='" . $row["sign"] . "'");
    else $clan[0] = '';
    $row["state"] = $clan[0] . "[" . str_replace("|", "!", $row["state"]) . "]";
    echo $row["user"] . "|" . $row["level"] . "|" . $row["sign"] . "|" . $row["state"] . "|";
    if (strpos(" " . $row["aura"], "molch|") <> 0) echo "." . "|";
    else echo "|";
    echo $tr . "|";
    echo $row["diler"];
    echo "\n";
}
