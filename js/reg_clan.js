document.write('<div style=" position:fixed; left:0px; top:0px; z-index: 80; width:0px; height:0px; visibility:visible;" id="zcenter" class=inv></div><div style="position:fixed; left:0px; top:0px; z-index: 11; width:100%; height:100%; display:none; text-align:center;" id="center3" class=news onclick="wtwt()">&nbsp;</div><div style="position:fixed; left:0px; top:0px; z-index: 1; width:100%; height:100%; display:none; text-align:center;" id="center2" class=news onclick="wtwt()">&nbsp;</div>');
//background-image: url(\'images/map.jpg\');
var c_showed=0;

function wtwt(a)
{
	if (!c_showed)
	{
	 $("#center2").css("display","block");
	 c_showed=1;
	 $("#zcenter").html('<a class=blocked href="javascript:wtwt()">Закрыть</a>'+a); 	
	 $("#zcenter").show(500);
	 }
	else
	{
	 $("#center2").css("display","none");
	 $("#zcenter").hide(500);
	 c_showed=0;
	}
}
function set_clan(user,id,clan,znak,sklon,podpis1,podpis2,user2)
{
	$("#zcenter").css({left:'30%',top:'20%',width:'40%',height:'50%'});
	$("#zcenter").hide(2);
	var pr = 'отсутствует';
	var txt = '';
	if (user==user2) txt += '<center>Вы не имеете права подписи!</center>'; 
	else {
	var n;
	n=1;
	txt += '<center>Заявка № '+id+'</center>';
	txt += '<hr><center>Форма подписи регистрации клана <img src=images/signs/'+znak+'.gif><font color=red><b>'+clan+'</b></font>.</center>';
	txt += '<hr><font align=left>Подал заявку : <b class=user>'+user+'</b></font><br>';	
	if (sklon == 'lekari') sklon='Лекари';
	if (sklon=='') sklon='не выбрана';
	txt += ' Склонность клана : <b>'+sklon+'</b><br>';
	if (podpis1=='') {txt +='';}
	else 
	{
		txt += 'Подтвердил заявку на создание клана: <b class=user>'+podpis1+'</b><br>';
	}
	if (podpis1!='' & podpis2=='') {txt +=''; n=2;}
	else 
	{
		txt += 'Подтвердил заявку на создание клана: <b class=user>'+podpis2+'</b><br>';
	}
	txt += 'Вы желаете подписаться под заявкой?'+n+'<br>';
	txt += '<table width=80% align=center><tr><td width=45%><a class=blocked href="main.php?adm_clan=c&folin=zaivki&podpis='+n+'&clan='+id+'">Да желаю</a></td><td width=10%></td><td width=45%><a class=blocked href="javascript:wtwt()">Нехочу!</a></td></tr></table>';
	}
	wtwt(txt);
}

function reg_clan(user,s,st,tr,y,status,wt,uid)
{
	$("#zcenter").css({left:'20%',top:'20%',width:'60%',height:'60%'});
	$("#zcenter").hide(2);
	var txt = '';
	txt += 'Кто подаёт заявку:<b class=user>'+user+'</b>';
	var sel;
	var tsl='',tsz='',tsc='',tsk='',tsb='',tsp=''; 
	tsl = ''+user+'';
	txt += '<hr><form onclick="main.php?adm_clan=c&folin=zaivka" method=post>'
	txt += 'Название клана: <input type=text class=but name=do_name_cl value="'+st+'"><br>';
	sel = '<hr>Склонность клана: <select name=do_sclon_cl><option value=lekari>Целительство</option><option value=vampires >Вампиризм</option><option value=torgovci onselect="set_znac_clan(\'torgovci\')">Торговля</option></select>';
	txt += sel+'<br>';
	txt += '<hr>Значёк клана:';
	txt += '('+st+')';
	txt += '<center class=but2><input type=submit class=login value="Сохранить"></center>';
	txt += '</form><br>';
	wtwt(txt);
	
}

function reg_clanzaiv(user,id,st,znak,sklon,podpis1,podpis2,uid)
{
	$("#zcenter").css({left:'20%',top:'20%',width:'60%',height:'60%'});
	$("#zcenter").hide(2);
	var txt = '';
	txt += 'Кто подал заявку:<b class=user>'+user+'</b><br>';
	txt += 'Название клана и значёк:<b class=user>'+st+'</b><img src=images/signs/'+znak+'.gif><br>';
	txt += 'Выбранная склонность:<b class=user>'+sklon+'</b><br>';
	if (podpis1=='') txt +='';
	else 
	{
	txt += 'Подтвердил заявку на создание клана: <b class=user>'+podpis1+'</b> ';
	}
	if (podpis2=='') txt +='';
	else 
	{
	txt += 'и <b class=user>'+podpis2+'</b><br>';
	}
	txt += '<hr><center>Принимаем решение:</center>';
	txt += '<table width=80% align=center><tr><td width=30%><a class=blocked href="main.php?adm_clan=c&folin=zaivki&sozdat=1&clan='+id+'">Одобрить создание клана</a></td><td width=5%></td><td width=30%><a class=blocked href="main.php?adm_clan=c&folin=zaivki&sozdat=2&clan='+id+'">Отказать в создании клана</a></td><td width=5%></td><td width=30%><a class=blocked href="main.php?adm_clan=c&folin=zaivki&sozdat=0&clan='+id+'">Убрать подпись</a></td></tr></table>';
	wtwt(txt);
	
}