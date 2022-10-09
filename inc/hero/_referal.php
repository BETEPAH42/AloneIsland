<?
echo "<script>var REF_COMP = 0;</script>";
if ($_GET["gopers"] == 'ref_competition') {
    $dn = "19.01.2022";
    $dk = "27.01.2022";

    $m = "SELECT user,referal_counter,referal_rcounter,level FROM users WHERE block='' ORDER BY (referal_rcounter*10+referal_counter*1000+level) DESC LIMIT 0,5";
    $pm1 = SQL::q1($m);
    $pm2 = SQL::q1($m);
    $pm3 = SQL::q1($m);
    $pm4 = SQL::q1($m);
    $pm5 = SQL::q1($m);

    /*echo "<div class=but2>";
echo "<b>Внимание конкурс</b>
<br>Кто приведёт больше людей в игру по своей реферальной ссылке (<b class=ma>http://10.238.156.51/AloneIsland/into.php?id=".$pers["uid"]."</b>) и они достигнут  хотябы <b>3ого</b> уровня с <b>".$dn."</b> до <b>".$dk."</b>(18:00-21:00 по Московскому времени) тот получит <b>200 БР</b>! Второе место получает 10 БР.<br>
Лидеры:<table class=but width=50%>
<tr>
<td>1)</td><td class=user>".$pm1["user"]."[<font class=lvl>".$pm1["level"]."</font>]</td>  <td>".$pm1[1]."</td><td class=user>".$pm1[2]."</td>
</tr><tr>
<td>2)</td><td class=user>".$pm2["user"]."[<font class=lvl>".$pm2["level"]."</font>]</td> <td>".$pm2[1]."</td><td class=user>".$pm2[2]."</td>
</tr><tr>
<td>3)</td><td class=user>".$pm3["user"]."[<font class=lvl>".$pm3["level"]."</font>]</td> <td>".$pm3[1]."</td><td class=user>".$pm3[2]."</td>
</tr><tr>
<td>4)</td><td class=user>".$pm4["user"]."[<font class=lvl>".$pm4["level"]."</font>]</td> <td>".$pm4[1]."</td><td class=user>".$pm4[2]."</td>
</tr><tr>
<td>5)</td><td class=user>".$pm5["user"]."[<font class=lvl>".$pm5["level"]."</font>]</td> <td>".$pm5[1]."</td><td class=user>".$pm5[2]."</td>
</tr>
</table>";
echo "</div>";
*/
    echo "<div class=but2>";
    echo "<b>Внимание конкурс</b>
<br>Кто приведёт больше людей в игру по своей реферальной ссылке (<b class=ma>http://localhost/AloneIsland/into.php?id=" . $pers["uid"] . "</b>) с <b>" . $dn . "</b> до <b>" . $dk . "</b>(18:00-21:00 по Московскому времени) тот получит <b>100 БР</b>! Второе место получает 10 БР.<br>
Лидеры:<table class=but width=50%>
<tr>
<td>1)</td><td class=user>" . $pm1["user"] . "[<font class=lvl>" . $pm1["level"] . "</font>]</td><td class=user>" . $pm1[1] . "</td>
</tr><tr>
<td>2)</td><td class=user>" . $pm2["user"] . "[<font class=lvl>" . $pm2["level"] . "</font>]</td><td class=user>" . $pm2[1] . "</td>
</tr><tr>
<td>3)</td><td class=user>" . $pm3["user"] . "[<font class=lvl>" . $pm3["level"] . "</font>]</td><td class=user>" . $pm3[1] . "</td>
</tr><tr>
<td>4)</td><td class=user>" . $pm4["user"] . "[<font class=lvl>" . $pm4["level"] . "</font>]</td><td class=user>" . $pm4[1] . "</td>
</tr><tr>
<td>5)</td><td class=user>" . $pm5["user"] . "[<font class=lvl>" . $pm5["level"] . "</font>]</td><td class=user>" . $pm5[1] . "</td>
</tr>
</table>";
    echo "</div>";
}
