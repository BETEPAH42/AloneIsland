<?
echo "<center><i>Вы смогли подписать бланки у:</i></center><br>";

$sostav = sql::q("SELECT * FROM wp ORDER by ID DESC");
echo "<center>";
$_NOM=0;
foreach ($sostav as $sost)
{
	if ($sost["uidp"]==$pers["uid"] and $sost["id_in_w"]=='14547') 
	{
		echo "".$sost['present'].", ";
		$_NOM++;
	}
}
echo "</center> ";
