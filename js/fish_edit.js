document.write('<div style="position:fixed; left:-2px; top:-2px; z-index: 6; width:0px; height:0px; visibility:visible;" id="zcenter" class=inv></div><div style="position:fixed; left:0px; top:0px; z-index: 1; width:100%; height:100%; display:none; text-align:center;" id="center2" class=news onclick="wtwt()">&nbsp;</div>');

var c_showed = 0;

function wtwt(a) {
	if (!c_showed) {
		$("#center2").css("display", "block");
		c_showed = 1;
		$("#zcenter").html('<a class=blocked href="javascript:wtwt()">Закрыть редактор!</a><center>' + a + '</center>');
		$("#zcenter").show(500);
	}
	else {
		$("#center2").css("display", "none");
		$("#zcenter").hide(500);
		c_showed = 0;
	}
}

function fish_edits(fish, n, lvl, den, noch, cena) {
	$("#zcenter").css({ left: '50%', top: '50%', width: '50%', height: '60%', margin: '25% 0 0 30%' });
	$("#zcenter").hide(10);
	wt = 'отсутствует';
	var txt = '';
	txt += '<img src=images/weapons/fish_new/' + n + '.gif><br>' +
		'<b class=user>' + fish + ' [' + lvl + ']</b>' +
		'<form action=main.php?go=fishin&add=fish_edit&id_f=' + n + ' method=POST>' +
		'<hr><table border=1 width=90%><tr><td width=25% align=center>Наименование</td><td width=25% align=center>Активность днём</td><td width=25% align=center>Активность ночью</td></tr>' +
		'<tr><td><input type=text name=fish value=' + fish + ' size=10></td><td><input type=text name=den value=' + den + ' size=10></td><td><input type=text name=noch value=' + noch + ' size=10></td></tr>' +
		'<tr><td width=25% align=center>Наживка №1</td><td align=center>Наживка №2</td><td align=center>Наживка №3</td></tr>' +
		'<tr><td><input type=text name=fish value=' + fish + ' size=10></td><td><input type=text name=fish value=' + fish + ' size=10></td><td><input type=text name=fish value=' + fish + ' size=10></td></tr>' +
		'<tr><td align=center>Водоёмы</td><td align=center>Вес рыбы в граммах</td><td>Цена за 1 кг.</td></tr>' +
		'<tr><td><input type=text name=fish value=' + fish + ' size=10></td><td><input type=text name=fish value=' + fish + ' size=10></td><td><input type=text name=cena size=10 value=' + cena + ' ></td></tr></table><br>' +
		'<input type=submit value=Ввести></form>';

	wtwt(txt);
}