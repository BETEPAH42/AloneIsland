<?
	$punishs = sql::q("SELECT * FROM puns WHERE uid=".$pers["uid"]." and type=6");
	echo '<table border="1" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF bgcolor=#F5F5F5 align=center>';
	foreach($punishs as $punish)
	{
		echo "<tr>";
		echo "<td bgcolor=#DDFFDD class=timef>".date("d.m.y H:i:s",$punish["date"])."</td>";
		echo "<td class=user>".$punish["who"];
		echo " <a href=info.php?p=".$punish["who"]." target=_blank>";
		echo "<img src=images/i.gif></a>&nbsp;";
		echo "</td>";
		echo "<td class=return_win>".$punish["reason"]."</td>";
		echo "</tr>";
	}
	echo '</table>';
