<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once 'inc/functions.php';
include_once 'inc/connect.php';

// var_dump($_COOKIE["uid"]);
// $uid = intval($_COOKIE["uid"]);
// echo '<br>'.$iud.'<br>';
// if(!$uid)
// {
// 	include("error.html");
// 	exit;
// }
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (empty($_GET["serrors"]) or 1)
    error_reporting(E_ERROR | E_PARSE);
else
    error_reporting(0);

$timer = time() + intval(microtime() * 1000) / 1000;

echo "<style>
    body {
        font-family:verdana;
        font-size:15px;
    }  
    a {color:#333; text-decoration:none}
    a:hover {color:#ccc; text-decoration:none}  
    #mask {
        position:absolute;
        left:0;
        top:0;
        z-index:1000;
        background-color:#000;
        display:none;
    }
  
    #boxes .window {
        position:absolute;
        left:0;
        top:20px;
        width:530px;
        height:300px;
        display:none;
        z-index:3001;
        padding:20px;
    }  
    #boxes #dialog {
        // background:url(images/white.png) no-repeat 0 0 transparent;
        width:600px; 
        height:500px;
        padding:25px;
        z-index:3001;
        background: white;
    }  
	
    .scroler {
		background: #ccc;
		pading-top:15px;
		margin-left:5px;
		width: 80%;
		height:350px;
		overflow:auto;
    }
</style>
 
<script type='text/javascript' src='js/jquery.js'></script>
<script>
    $(document).ready(function() {   
        $('a[name=modal]').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('href');
    
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();
    
        $('#mask').css({'width':maskWidth,'height':maskHeight});
    
        $('#mask').fadeIn('slow',0.8);
        $('#mask').fadeTo('slow',0.8); 
    
        var winH = $(window).height();
        var winW = $(window).width();
    
        $(id).css('top', '55px');
        $(id).css('left', winW/2-$(id).width()/2);
        $(id).fadeIn(2000); 
    });
  
    $('.window .close').click(function (e) {
        e.preventDefault();
        $('#mask, .window').hide();
    }); 
 
    $('#mask').click(function () {
        $(this).hide();
        $('.window').hide();
    }); 
  
    });  
</script>";
//SQL::q("SELECT COALESCE(GET_LOCK('".intval($_COOKIE["uid"])."', 60));");

################################## LOCK

########################################
include_once 'inc/prov.php';
include_once 'inc/up.php';

if ($pers["curstate"] == 0) include_once('inc/pers.php');
if ($pers["curstate"] == 1) include_once('inc/inv.php');
if ($pers["curstate"] == 2) {
    $row = SQL::q1("SELECT inc FROM `locations` WHERE `id` = '" . $pers["location"] . "'");
    include_once("inc/locations/" . $row["inc"]);
}
if ($pers["curstate"] == 3) include_once('inc/naddon.php');
if ($pers["curstate"] == 4) include_once('inc/battle.php');
if ($pers["curstate"] == 5) include_once('inc/self.php');
if ($pers["curstate"] == 6) include_once('inc/friends/list.php');
if ($pers["curstate"] == 16) include_once('inc/adm/map_edit.php');
if ($pers["curstate"] == 17) include_once('inc/adm/new_add.php');
if ($pers["curstate"] == 18) include_once('inc/adm/new_tip.php');
if ($pers["curstate"] == 20) include_once('inc/adm/administration.php');
if ($pers["curstate"] == 21) include_once('inc/adm/media.php');
if ($pers["curstate"] == 22) include_once('inc/adm/weapons.php');
if ($pers["curstate"] == 23) include_once('inc/adm/magic.php');
if ($pers["curstate"] == 24) include_once('inc/adm/bots.php');
if ($pers["curstate"] == 25) include_once('inc/adm/ministers.php');
if ($pers["curstate"] == 26) include_once('inc/adm/users.php');
if ($pers["curstate"] == 27) include_once('inc/adm/quests.php');
if ($pers["curstate"] == 28) include_once('inc/adm/questsR.php');
if ($pers["curstate"] == 29) include_once('inc/adm/questsS.php');
if ($pers["curstate"] == 30) include_once('inc/adm/questsQ.php');
if ($pers["curstate"] == 31) include_once('inc/adm/ava_req.php');
if ($pers["curstate"] == 32) include_once('inc/adm/clans.php');
if ($pers["curstate"] == 33) include_once('inc/adm/fish.php');
if ($pers["curstate"] == 34) include_once('inc/adm/gheralbism.php');
if ($pers["curstate"] == 35) include_once('inc/adm/test.php');

$t = time() + intval(microtime() * 1000) / 1000 - $timer;
/*
	$longes_exec = SQL::q1("SELECT longest_exec FROM configs");
	if ($longes_exec[0]<$t)
	{
		SQL::q("UPDATE configs SET longest_exec=".$t);
		error_reporting(E_ALL & ~E_NOTICE);
	}
	*/
/*if (($t - $sql_queries_timer)>0.4)
	{
		$str1 = '';
		foreach ($_POST as $key => $v)
			$str1.=$key."=".$v.";";
		$str2 = '';
		foreach ($_GET as $key => $v)
			$str2.=$key."=".$v.";";
		say_to_chat ("a",str_replace("'","",'['.($t - $sql_queries_timer).'] POST:'.$str1.' | GET:'.$str2.''),1,'sL','*');
	}*/



if ($_COOKIE["uid"] == 1) {
    // echo "<script>function sysdown(){  jQuery(\"#sysinf\").slideDown(300); }</script>";
    echo "<a href='#dialog' name='modal' class='bga'>Системная информация[" . $t . " | " . $sql_queries_timer . "]</a>";
    echo "<div id='boxes'>  
        <div id='dialog' class='window'> 
        Простое модальное окно | 
            <a href='#'class='close'/>Закрыть его</a><br>
            <font class=time><center>SQL :: [" . $sql_queries_counter . "] > " . $sql_queries_timer . " sec. | ALL :: " . $t . "</center></font>
            <font class=time><center>SQL :: [" . $sql_longest_query . "] > " . $sql_longest_query_t . " sec.</center></font><br><a href=main.php?serrors=1 class='timef'>Показать ошибки </a><hr>
        <div class=scroler title='Подключаемые модули (файлы)'>";

    $included_files = get_included_files();
    foreach ($included_files as $filename) {
        echo "$filename<br />";
    }
    echo "</div>
   </div>
	</div>";
    echo "<script src='js/c.js'></script>
    <script>$(\".LinedTable tr:nth-child(odd)\").css(\"background-color\",\"#ECECEC\");</script>";
    //	echo " <div id='mask'></div>";
}
?>
<script>
    let getCook = document.cookie.split(';');
    let cookNick = '';
    getCook.forEach(m => {
        if (m.indexOf('nick=') > 0) {
            cookNick = m.split('nick=')[1];
        }
    });
    top.frames['chmain'].nick = cookNick;
</script>
