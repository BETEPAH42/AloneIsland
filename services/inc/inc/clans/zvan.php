<?
################################################ рапорт
function reportx($txt)
{
	echo "<br><center class=but2><center class=puns>".$txt."</center></center><br>";
}

################################################смена отдела
Echo "<center>";
if (@$_POST["dolj"])
{
if ($_POST["dolj"]==1){
	reportx("*** Удачно сменена должность персонажу ".$_GET["pers"]."! ***");
	sql::q("UPDATE users SET clan_state='z', state='1-ый зам' WHERE user='".$_GET["pers"]."'");
	}
	if ($_POST["dolj"]==2){
	reportx("*** Удачно сменена должность персонажу ".$_GET["pers"]."! ***");
	sql::q("UPDATE users SET clan_state='c', state='Казначей' WHERE user='".$_GET["pers"]."'");
	}
if ($_POST["dolj"]==3){
	reportx("*** Удачно сменена должность персонажу ".$_GET["pers"]."! ***");
	sql::q("UPDATE users SET clan_state='k', state='Кадровик' WHERE user='".$_GET["pers"]."'");
	}
if ($_POST["dolj"]==4){
	reportx("*** Удачно сменена должность персонажу ".$_GET["pers"]."! ***");
	sql::q("UPDATE users SET clan_state='b', state='Солдат' WHERE user='".$_GET["pers"]."'");
	}
if ($_POST["dolj"]==5){
	reportx("*** Удачно сменена должность персонажу ".$_GET["pers"]."! ***");
	sql::q("UPDATE users SET clan_state='p', state='Производственник' WHERE user='".$_GET["pers"]."'");
	}
}
################################################
if (@$_POST["dolj2"])
{
reportx("*** Введена новая должность '".$_POST["dolj2"]."' для ".$_GET["pers"]."! ***");
		sql::q("UPDATE `users` SET `state`='".$_POST["dolj2"]."' WHERE `user`='".$_GET["pers"]."'");
}
################################################ тело
echo "<table width='1000' border='2'>
<tr>
<td width='200'><center><font size=2 color=blue>Соклановцы:</font></center></td>
<td width='300'><center><font size=2 color=blue>Отдел [должность]:</font></center></td>
<td width='200'><center><font size=2 color=blue>Отдел клана:</font></center></td>
<td width='200'><center><font size=2 color=blue>Смена должности:</font></center></td>
<td width='100'><center><font size=2 color=blue>Примечания:</font></center></td></tr>";


$sostav = sql::q("SELECT user,rank,online,location,state,level,aura,uid,rank_i,clan_state,lastom,silence,clan_tr FROM `users` WHERE `sign`='".$clan['sign']."' ORDER BY `clan_state` ASC");
$online = 0;
$maxrank = 0;
$dye = $clan["dmoney"];
$money = $clan["money"];
$avglvl = 0;
$allpers = 0;
foreach ($sostav as $perssost) 
{
if ($status=='g' or $status=='z') 
	$onclick = "onclick=\"set_status('".$perssost["user"]."','".$perssost["clan_state"]."','".$perssost["state"]."',".$perssost["clan_tr"].",".(($perssost["uid"]==$pers["uid"])?1:0).",'".$status."',".(($pers["sign"]=="c2")?1:0).",".$perssost["uid"].")\" style='cursor:pointer'";
	else $onclick = '';
	
$online += $perssost["online"];
if ($perssost["rank_i"]>$maxrank) $maxrank=$perssost["rank_i"];
$avglvl += $perssost["level"];
$allpers ++;
echo"<tr><td>";
echo"<img src='images/pr.gif' onclick=\"javascript:top.say_private('".$perssost["user"]."')\" style='cursor:pointer' height=16> 
<font class=user  ".$onclick.">";
echo " ".$perssost["user"]."</font><font class=lvl>[".$perssost["level"]."]</font>";
echo "<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=".$perssost["user"]."','_blank')\" style='cursor:pointer'>";
$color = '#333333';
if ($perssost["clan_state"]=='g') $color = '#990000';
if ($perssost["clan_state"]=='z') $color = '#DD0000';
if ($perssost["clan_state"]=='c') $color = '#009900';
if ($perssost["clan_state"]=='k') $color = '#000099';
if ($perssost["clan_state"]=='b') $color = '#009999';
if ($perssost["clan_state"]=='p') $color = '#00DDDD';
echo "</font></td><td><b style='color:".$color."'>***"._StateByIndex($perssost["clan_state"])."</b>[".$perssost['state']."]***</td>
";
if ($pers["clan_state"]=='g' or $pers["clan_state"]=='z') {
echo "<td><center>";
if ($pers["user"]==$perssost["user"] or $perssost["clan_state"]=='g') echo "<center> <font class=ma>Нельзя </font></center>";
else {
echo "
<form action='main.php?clan=zvan&action=addon&gopers=clan&pers=".$perssost["user"]."' method='POST'>
<select name=dolj>";
if ($perssost["clan_state"]=='z') echo "";
else echo "<option value=1>Заместитель</option>";
if ($perssost["clan_state"]=='c') echo "";
else echo '<option value=2>Казначей</option>';
if ($perssost["clan_state"]=='k') echo "";
else echo '<option value=3>Кадровый отдел</option>';
if ($perssost["clan_state"]=='b') echo "";
else echo '<option value=4 selected>Боевой отдел</option>';
if ($perssost["clan_state"]=='p') echo "";
else echo '<option value=5>Производственный отдел</option>';

echo "</select>
<input type='submit' name='bsubmit' value='Отправить'>
</form>";
}
echo '</center></td>';
}
	else echo "<td><center> <font class=ma>У вас нет прав </font></center></td>";
	
	echo "<td><center>";
	if ($pers["clan_state"]=='g' or $pers["clan_state"]=='z') {
	echo "<form action='main.php?clan=zvan&action=addon&gopers=clan&pers=".$perssost["user"]."' method='POST'>
	<input name=dolj2 class=laar></form>";}
	else echo "<center> <font class=ma>Hет прав </font></center";
	echo "</center></td>";

}
echo "</td><br></table></center>";
