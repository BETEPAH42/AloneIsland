<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='shortcut icon' href='images/icon.ico'>
<link href="main.css?1" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/squeezebox.css?1" type="text/css" media="screen">
<title>Aloneislands: бесплатная ролевая браузерная онлайн (online) игра MMORPG free games on-line</title>
<meta http-equiv="content-type" content="text/html; charset=windows-1251">
<meta name=description content='Фэнтези онлайн игра с элементами стратегии, квестовой частью и возможностью самим участвовать в создании нового мира , присутвует механика и магия. Возможность выбора расы!'>
<meta name='keywords' content='игра, играть, рпг, онлайн, online, fantasy, фэнтези, квест, алхимия, мир, земли, стратегия, магия, стихия, арена, бои, клан, семья, братство, сражение, тьма, свет, хаос, сумерки, удар, меч, нож, топор, дубина, щит, броня, доспех, шлем, перчатки, амулет, кулон, кольцо, пояс, зелья, карта, замки, шахты, лавка, таверна, артефакты, раритеты, свитки, свиток, школа, од, рыцарь, маг, друид, гоблин, орк, призрак, эльф, отдых, развлечение, чат, общение, знакомства, форум, власть, золото, серебро, телепорт, банк, рынок, мастерская, тактика, больница, храм, бог, демон, защита, сила, удача, ловкость, война, орден, аптека, почта, реторта, ступка, пестик, дистиллятор , механоид , оборотень , осрова , alone , islands , aloneislands , сила , реакция , воля , интелект , оружие , пистолет , балиста'>
<script type="text/javascript" language="javascript" src="js/jquery.js?2"></script>
<script type="text/javascript" language="javascript" src="js/main.js?2"></script>
<script type="text/javascript" language="javascript" src="js/mootools.js"></script>
<script type="text/javascript" language="javascript" src="js/SqueezeBox.js"></script>
<script type="text/javascript" language="javascript" src="js/snow.js?411141"></script>
</head>
<body bgcolor=#CDCBCC>
<center id="maina" style="overflow:hidden;position:absolute;z-index:1;top:0;left:0;width:100%;height:100%;"></center>
<script language='JavaScript'>
window.addEvent('domready', function() {

SqueezeBox.initialize({
size: {x: 350, y: 400},
ajaxOptions: {
method: 'get'
}
});


$$('a.boxed').each(function(el) {
el.addEvent('click', function(e) {
new Event(e).stop();
SqueezeBox.fromElement(el);
});
});

$$('.panel-toggler').each(function(el) {
var target = el.getLast().setStyle('display', 'none');
el.getFirst().addEvent('click', function() {
target.style.display = (target.style.display == 'none') ? '' : 'none';
});
});
});
<?
error_reporting(0);

include ("configs/config.php");
$res = mysqli_connect ($mysqlihost,$mysqliuser,$mysqlipass,$mysqlibase);
mysqli_select_db($mysqlibase, $res);
mysqli_query("SET NAMES cp1251"); 

$lt = localtime();
if ($lt[2]>=10 and $lt[2]<18) $t='day';
if ($lt[2]>=18 and $lt[2]<22) $t='evn';
if ($lt[2]>=22 or $lt[2]<6) $t='night';
if ($lt[2]>=6 and $lt[2]<10) $t='morn';
$page = intval($_GET["next_news"]);
$newt = '';
$news = mysqli_query("SELECT * FROM news ORDER BY date DESC LIMIT ".($page).",".($page+1).";");
while ($new = mysqli_fetch_array($news,mysqli_ASSOC))
$newt .= "<i class=timef>[".date("d.m.Y",$new["date"])."]</i> | <b>".$new["title"]."</b><br><font class=timef>".$new["text"]."</font><br>";
$newt .= '<hr>';
if ($page>0) $newt .= '<a href=index.php?next_news='.($page-1).' class=timef> << </a>';
$newt .= '<a href=index.php?next_news='.($page+1).' class=timef> >> </a>';
?>
</script>
<div style="position:absolute;visibility:hidden; display:block; height:0; top:0;" id="mnews"><center style="width:80%;text-align:left;"><?= $newt; ?></center></div>
<div style="position:absolute;visibility:hidden; display:block; height:0; top:0;" id="mlegend"><font class=timef>Мир, который не похож на наш...<br><i>Добро пожаловать!</i></font></div>
<script>
<?php echo  "index('".$_GET['error']."',0,'".$t."');"; ?>
</script>
</body>
</html>
