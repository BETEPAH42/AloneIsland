<Center><table width='900'><tr><td>
<?
Echo "<center><Table width='90%'><tr><td width='25%'><center><a href='main.php?adm_clan=c' class=blocked>Отдел кланов</a></center></td>
<td width='25%'><center><a href='main.php?adm_prof=prof' class=blocked>Отдел профессий</a></center></td>
<td width='25%'><center><a href='main.php?adm_battle=prof' class=blocked>Боевой отдел</a></center></td>
<td width='25%'><center><a href='main.php?adm_magic=prof' class=blocked>Отдел магии</a></center></td></tr></table></center>";
?>
</td></tr>
</table></Center>
<?
if (@$_GET["adm_clan"]=="c") include("administrat/adm_clan.php");
elseif (@$_GET["adm_prof"]=="prof") include("administrat/adm_prof.php");
elseif (@$_GET["adm_battle"]=="prof") include("administrat/adm_battle.php");
elseif (@$_GET["adm_magic"]=="prof") include("administrat/adm_magic.php");
?>