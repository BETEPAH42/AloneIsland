<center><font class=but4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i><b>Отдел профессий</b></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></center><br>
<center><table width=80% border=1>
<tr><td colspan=2 align=center> <font class=but3 onclick="location='main.php?adm_prof=prof&les=1'" style='cursor:pointer'>Всё о лесорубстве</font> | <font class=but3 onclick="location='main.php?adm_prof=prof&riba=1'" style='cursor:pointer'>Всё о рыболовстве</font> | <font class=but3 onclick="location='main.php?adm_prof=prof&shahta=1'" style='cursor:pointer'>Всё о шахтёрстве</font> | <font class=but3 onclick="location='main.php?adm_prof=prof&profi=1'" style='cursor:pointer'>Остальные профессии</font></td></tr>
<tr>
<?
if (@$_GET["les"]==1) 
echo "<td width=20% align=center>Пустим какую-нить картинку лесника</td><td width=80% align=center>аздеся бля надо подумать что написать о профессии лесника, но чтоб прикольно смотрелось</td>";

if (@$_GET["riba"]==1) 
echo "<td width=20% align=center>Пустим какую-нить картинку рыбака</td><td width=80% align=center>аздеся бля надо подумать что написать о профессии рыбака, но чтоб прикольно смотрелось</td>";

if (@$_GET["shahta"]==1) 
echo "<td width=20% align=center>Пустим какую-нить картинку шахтёра</td><td width=80% align=center>аздеся бля надо подумать что написать о профессии шахтёра, но чтоб прикольно смотрелось</td>";

if (@$_GET["profi"]==1) 
echo "<td width=20% align=center>Пустим какую-нить картинку для других профессий</td><td width=80% align=center>аздеся бля надо подумать что написать об остальных профессиях, но чтоб прикольно смотрелось</td>";

?>




</tr>
</table></center>