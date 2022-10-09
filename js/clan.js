document.write('<div style="position:fixed; left:-2px; top:-2px; z-index: 6; width:0px; height:0px; visibility:visible;" id="zcenter" class=inv></div><div style="position:fixed; left:0px; top:0px; z-index: 1; width:100%; height:100%; display:none; text-align:center;" id="center2" class=news onclick="wtwt()">&nbsp;</div>');

var c_showed=0;

function wtwt(a)
{
	if (!c_showed)
	{
	 $("#center2").css("display","block");
	 c_showed=1;
	 $("#zcenter").html('<a class=blocked href="javascript:wtwt()">Закрыть</a><center class=but>'+a+'</center>'); 	
	 $("#zcenter").show(500);
	 }
	else
	{
	 $("#center2").css("display","none");
	 $("#zcenter").hide(500);
	 c_showed=0;
	}
}

function set_status(user,s,st,tr,y,status,wt,uid)
{
	$("#zcenter").css({left:'40%',top:'120px',width:'210px',height:'200px'});
	$("#zcenter").hide(1);
	var txt = '';
	txt += '<b class=user>'+user+'</b>';
	var ts;
	var sel;
	var tsg='',tsz='',tsc='',tsk='',tsb='',tsp='';
	if (s=='g') {ts = 'Глава клана';tsg='SELECTED'}
	if (s=='z') {ts = 'Заместитель главы';tsz='SELECTED'}
	if (s=='c') {ts = 'Казначей';tsc='SELECTED'}
	if (s=='k') {ts = 'Отдел кадров';tsk='SELECTED'}
	if (s=='b') {ts = 'Боевой отдел';tsb='SELECTED'}
	if (s=='p') {ts = 'Производственный отдел';tsp='SELECTED'}
	if (s=='g' || y) sel = "<div class=but><b>"+ts+"</b></div>";
	else
		sel = '<select name=clan_state><option value=z '+tsz+'>Заместитель главы</option><option value=c '+tsc+'>Казначей</option><option value=k '+tsk+'>Отдел кадров</option><option value=b '+tsb+'>Боевой отдел</option><option value=p '+tsp+'>Производственный отдел</option></select>';
	txt += '<form action="main.php?action=addon&gopers=clan&set_params='+user+'" method=post>'
	txt += 'Личный статус: <input type=text class=but name=state value="'+st+'"><br>';
	txt += sel+'<br>';
	if ((s!='g' && !y) || status=='g')
	{
	txt += 'Казна'
	if (tr) 
		txt+= '<input type=checkbox name=clan_tr value=1 CHECKED>';
	else
		txt+= '<input type=checkbox name=clan_tr value=1>';
	}
	txt += '<center class=but2><input type=submit class=login value="Применить"></center>';
	txt += '</form>';
	if ((status=='g' || status=='z') && !y && s!='g')
	{
		txt += '<hr><a class=blocked href="javascript:if(confirm(\'Вы уверены?\'))location = \'main.php?action=addon&gopers=clan&go_out='+user+'\'">Выгнать</a>';
	}
	if ((status=='g' || status=='z') && !y && s!='g' && wt)
	{
		txt += '<hr><a class=blocked href="main.php?action=addon&gopers=clan&clan=edit&who='+user+'">Редактировать(Смотрителям)</a>';
	}
	if ((status=='g' || status=='с' || status=='z'))
	{
		txt += '<hr><a class=blocked href="main.php?action=addon&gopers=clan&sn_all='+uid+'">Раздеть</a>';
	}
	wtwt(txt);
}

function set_status2(user,s,st,tr,y,status,wt,uid)
{
	$("#zcenter").css({left:'15%',top:'20%',width:'70%',height:'60%'});
	$("#zcenter").hide(1);
	wt = 'отсутствует';
	var txt = '';
	txt += '<b class=user>'+user+' ['+st+']</b>';
	txt += '<hr> <a href=\'main.php?clan=glava&action=addon&gopers=clan&pooch=1&uid='+uid+'&pers='+user+'\' class=login> Поощрить</a> |';
	txt += ' <a href=\'main.php?clan=glava&action=addon&gopers=clan&pooch=2&pers='+user+'&uid='+uid+'\' class=login >Наказать</a> |';
	txt += ' <a href=\'main.php?clan=glava&action=addon&gopers=clan&lech=1&pers='+user+'&uid='+uid+'\' class=login> Лечить травму</a>';
	
	if ((status=='g' || status=='z') && !y && s!='g' && wt)
	{
		txt += '<form action="main.php?clan=glava&action=addon&gopers=clan&person=del&who='+user+'&uid='+uid+'" method=post>'
		txt += '<hr>Причина : <input type=text class=but name=prichina value='+wt+'>';
		txt += '<center class=but2><input type=submit class=login  value="Удалить перса"></center>';
		txt += '</form>';
	}

	wtwt(txt);
}

function ch_site(url)
{
	$("#zcenter").css({left:'40%',top:'120px',width:'210px',height:'100px'});
	$("#zcenter").hide(1);
	var txt = '';
	txt = '<form action="main.php?action=addon&gopers=clan" method=post><input type=text class=but name=site value="'+url+'">';
	txt += '<center class=but2><input type=submit class=login value="Применить"></center>'+url+'';
	txt += '</form>';
	wtwt(txt);
}
