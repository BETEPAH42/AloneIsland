<center>
    <font size=3 color=blue>Достижения персонажа:</font>
</center>
<table width=90% align=center>
    <?
    $q_p = sql::q1("SELECT questWitch, questFISH, quest1, quest2, fish_tournir FROM `users` WHERE uid=" . $pers['uid'] . "");
    $q_pr = [
        0 => "<font size=2 color=red>Не приступал</font>",
        1 => "<font size=2 color=blue>Выполняется</font>",
        2 => "<font size=2 color=green>Выполнен</font>"
    ];
    echo "<tr><td width=70%><font class=ym>Квест 'Требование Ведьмы':</font></td><td width=30% class=laar><font size=2>Выполнено " . $q_p['questWitch'] . " раз</font></td></tr>";
    echo "<tr><td width=70%><font class=ym>Квест 'Требование Рыбака':</font></td><td width=30% class=laar><font size=2>Выполнено " . $q_p['questFISH'] . " раз</font></td></tr>";
    echo "<tr><td width=70%><font class=ym>Квест 'Потерянные орехи Судьбы':</font></td><td width=30% class=laar> " . $q_pr[$q_p['quest1']] . " </td></tr>";
    echo "<tr><td width=70%><font class=ym>Квест 'Спасение урожая':</font></td><td width=30% class=laar> " . $q_pr[$q_p['quest2']] . " </td></tr>";
    echo "<tr><td>Турнир РЫБАКОВ</td><td></td></tr>";
    $ft = explode("&", $q_p["fish_tournir"]);
    $ft_pos = explode("-", $ft["1"]);
    echo "<tr><td class=ym>Занято 1 место:</td><td width=30% class=laar>" . (int)$ft_pos["0"] . " раз(а)</td></tr>";
    echo "<tr><td class=ym>Занято 2 место:</td><td width=30% class=laar>" . (int)$ft_pos["1"] . " раз(а)</td></tr>";
    echo "<tr><td class=ym>Занято 3 место:</td><td width=30% class=laar>" . (int)$ft_pos["2"] . " раз(а)</td></tr>";
    echo "<tr><td class=ym>Принято участия в турнире:</td><td width=30% class=laar>" . (int)$ft["2"] . " раз(а)</td></tr>";
    ?>
</table>