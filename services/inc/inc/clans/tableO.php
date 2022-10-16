<?
echo "<center><table width=1000 class=but><tr><td width=20><center><b>ЛВЛ</b></center></td><td width=180><center><b>Опыт:</b></center></td><td width=120><center><b>Свободные статы:</b></center></td><td width=100><center><b>К боевым умениям:</b></center></td><td width=100><center><b>К второстепенным умениям:</b></center></td><td width=100><center><b>LN:</b></center></td></tr>";
$exp1 = sql::q("SELECT * from exp ORDER by level ASC");
foreach ($exp1 as $expan) {
    $i++;
    echo "<tr>
    <td><center>" . $expan['level'] . "</center></td>
    <td><center>" . $expan['exp'] . "</center></td>
    <td><center>" . $expan['stats'] . "</center></td>
    <td><center>" . $expan['free_f_skills'] . "</center></td>
    <td><center>" . $expan['free_m_skills'] . "</center></td>
    <td><center>" . $expan['money'] . "</center></td>
    </tr>";
}
echo "</table>";
