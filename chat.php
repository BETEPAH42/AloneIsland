<META Content="text/html; Charset=utf-8" Http-Equiv=Content-type>
<LINK href=ch_main.css rel=STYLESHEET type=text/css>

<script type="text/javascript" src="js/jquery.js"></script>
<SCRIPT type="text/javascript" src="js/tools/scrollto.js"></SCRIPT>

<body style="margin:0;" background="images/DS/chat_bg.jpg" scroll="no">
    <div style="position:absolute;width:30%;z-index:3;text-align:right;height:14px;top:0px;left:68%;display:block;">
        <div id="tbox" onmouseover="jQuery('#tbox').stop();jQuery('#tbox').animate({opacity:'1'},200);" onmouseout="jQuery('#tbox').stop();jQuery('#tbox').animate({opacity:'0.2'},200);">
            <a class="ActiveBc" href="javascript:changeChatOrientation(1)" id=ch1>Общий</a>
            <a class="ActiveBc" href="javascript:changeChatOrientation(2)" id=ch2>Торговый</a>
            <a class="ActiveBc" href="javascript:changeChatOrientation(3)" id=ch3>Лог&nbsp;Боя</a>
        </div>
    </div>
    <div id="menu" class="menu" style="display:none;"></div>
    <div id="chat" style="position:absolute;z-index:0;overflow-x:hidden;overflow-y:auto;display:block;width:100%;height:100%;">
        <div id="c1" style="display:none;"></div>
        <div id="c2" style="display:none;"></div>
        <div id="c3" style="display:none;"></div>
        <div id=scrollitem style="display:block;"></div>
    </div>
    <?php
    require_once 'classes/sql.php';
    include_once 'inc/functions.php';
    $images = "images";
    $pers = catch_user(intval($_COOKIE["uid"]));

    ?>
    <script src="js/chat.js"></script>
    <script src="js/ch_msg.js"></script>
    <script>
        <?php
        if (!$pers) {
            // echo "document.getElementById('#chat').innerHTML = '" . $pers['user'] . "';";
            echo "\jQuery(\"#chat\").html(\"Error:: Authentification;\");";
        }
        echo "let nick = '" . $pers["user"] . "';";
        if (substr_count($pers["rank"], "<molch>") or $pers["diler"] == '1' or $pers["priveleged"])
            echo "molch=1;";
        else
            echo "molch=0;";
        ?>
        changeChatOrientation(1);
    </script>
</body>