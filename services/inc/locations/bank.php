<?php
def_page();
switch ($_GET['act']) {
  case 'state':
    bank_state();
    break;
  case '1':
    add_percent(UID);
    bank_change($_POST['acc_act']);
    break;
  case '2':
    bank_trans();
    break;
  case 'trans':
    show_trans();
    break;
  default:
    add_percent(UID);
    bank_state();
    break;
}
function def_page()
{
  $res = sql::q1("SELECT COUNT(*) as count FROM bank_account WHERE `uid`=" . UID . "")['count'];
  if ($res != 1) {
    sql::q("INSERT INTO bank_account(`uid`,`money`,`last_in`) VALUES(" . UID . ",0," . time() . ")");
  }
  echo "<div align=center valign=top class=inv><img border='0' src='images/locations/bank.jpg' ></div>
  <div id=trans></div>";
  $res = sql::q1('SELECT count(watch) as count FROM bank_trans WHERE `watch`=1 AND `uid`=' . UID . "");
  if ($res[0] > 0) {
    echo '<div align = center class=return_win><br>Новых перводов: ' . $res[0] . '<br></div>';
    sql::q('UPDATE bank_trans SET `watch`= 0 WHERE `uid`=' . UID);
  }
}

function bank_state()
{
  $state = sql::q1("SELECT * FROM bank_account WHERE `uid`=" . UID . "");
?>
  <script>
    $(document).ready(
      function() {
        $("#one").slideUp(0);
        $("#two").slideUp(0);
      }
    );

    function slide(id) {
      s_id = '#' + id;
      if ($(s_id).attr('state') == 0) {
        $(s_id).attr('state', '1');
        $(s_id).slideUp(300);
      } else {
        $(s_id).attr('state', '0');
        $(s_id).slideDown(300);
      }
    }
  </script>
<?
  echo "
 <center class=inv><br>
  <table width=70% border='0' cellspacing='0' cellpadding='0' bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>
    <tr>
        <td>Денег на счете:</td>
        <td class=time colspan=5>" . round($state['money'], 0) . " LN</td>
    </tr>
    <tr>
        <td>Последнее изменение счета было произведено:</td>
        <td class=timef colspan=5>" . date("d.m.Y H:i", $state['last_in']) . "</td>
    </tr>
   </table><br>

		<div style='width:70%'><a class='bga' onclick=slide('one')>Положить/Снять LN</a></div>
        <div style='width:70%' id='one' state=1>
		  <form action='main.php?act=1' method='post'>
            <select name='acc_act' class=items><option value='in'>Положить на счет</option><option value='out'>Снять со счета</option></select><input name='money_num' type='text' class=laar> LN
            <input type='submit' value='ОК' class=login>
  	      </form><br></div>

<div style='width:70%'><a class='bga' onclick=slide('two')>Переводы другим игрокам</a></div>
<div style='width:70%' id='two' state=1><form action='main.php?act=2' method='post'>
      Перевести <input name='trans_ln' type='text' class=laar> LN персонажу <input name='trans_to' type='text' class=laar>
       <input type='submit' value='OK' class=login>
	  </form></div>
<div style='width:70%'>
<a href=main.php?act='trans' class=bga>Просмотр банковских операций</a>
</div>
</center>
";
} // end bank_state();


function add_percent($uid)
{
  $perc = 0.01; // 10 %
  $week = 604800; // в неделю...
  $state = sql::q1('SELECT `money`, `last_in` FROM bank_account WHERE `uid`=' . $uid);
  $cur_time = time();
  $time = $cur_time - $state['last_in'];
  $perc_in_time = 1 + ($time / $week) * $perc;
  $mon = $state['money'] * $perc_in_time;
  $mon = round($mon, 2);
  sql::q('UPDATE bank_account SET `money`=' . $mon . ', `last_in`=' . time() . ' WHERE `uid`=' . $uid);
}

function bank_change($acc_act)
{
  $err_num = 0;
  $mon = floor(mtrunc(intval($_POST['money_num'])));
  if ($mon > 0) {
    $s4et = sql::q1("SELECT money FROM bank_account WHERE `uid`=" . UID . "");
    $client = sql::q1("SELECT money FROM users WHERE `uid`=" . UID . "");
    switch ($_POST['acc_act']) {
      case 'in': // положить деньги
        if ($client['money'] > $mon) {
          $s4et['money'] = $s4et['money'] + $mon;
          $client['money'] = $client['money'] - $mon;
          $trans_id = 0;
        } else {
          $err[] =  'Вы пытаетесь положить в банк сумму, больше чем у вас есть';
        }
        break;
      case 'out': // снять деньги
        if ($s4et['money'] >= $mon) {
          $s4et['money'] = $s4et['money'] - $mon;
          $client['money'] = $client['money'] + $mon;
          $trans_id = 1;
        } else {
          $err[] = 'Вы пытаетесь снять со счета сумму, больше чем у вас есть';
        }
        break;
      default:
        // NOP;
        break;
    }
    if ($acc_act == 'in' || $acc_act == 'out') {
      $res = sql::q("UPDATE bank_account SET `money`=" . $s4et['money'] . ", `last_in` =" . time() . " WHERE `uid`=" . UID . "");
      if ($res) {
        sql::q("UPDATE users SET `money`=" . $client['money'] . " WHERE `uid`=" . UID . "");
      }
      $ans = add_trans(UID, '', $mon, $trans_id);
    } else {
      echo '';
    }
    echo $ans;
  } else {
    $err[] = 'Вы пытаетесь положить сумму, меньше 1 LN';
  }
  if (is_array($err)) {
    foreach ($err as $key) {
      echo '<div width=100% class=puns align=center>' . $key . '</div>';
    }
  }
  bank_state();
} // end bank_change();

function bank_trans()
{
  $mon = floor(mtrunc(intval($_POST['trans_ln'])));
  $tto = $_POST['trans_to'];
  $tto = strtolower($tto);
  $res = sql::q1("SELECT `uid` FROM users WHERE `smuser`='" . $tto . "' AND `uid`<>" . UID . "");
  if (!isset($res) || $res == '') {
    echo '<div class=puns align="center">Персонаж с таким именем не найден</div>';
  } else {
    $pers_from = sql::q1("SELECT `money` FROM bank_account WHERE `uid`=" . UID . "");
    if ($pers_from['money'] >= $mon && $mon > 0) {
      add_percent(UID);
      add_percent($res['uid']);
      $pers_to = sql::q1('SELECT `money` FROM bank_account WHERE `uid`=' . $res['uid']);
      $pers_from['money'] = $pers_from['money'] - $mon;
      $pers_to['money'] = $pers_to['money'] + $mon;
      SQL::q('UPDATE bank_account SET `money`=' . $pers_from['money'] . ' WHERE `uid`=' . UID);
      SQL::q('UPDATE bank_account SET `money`=' . $pers_to['money'] . ' WHERE `uid`=' . $res['uid']);
      $ans = add_trans(UID, $res['uid'], $mon, 2);
      echo $ans;
    }
  }
  bank_state();
} // end of bank_trans();

function add_trans($uid, $uid2 = '', $money, $type)
{

  $max = sql::q1("SELECT MAX(id) as max  FROM bank_trans");
  if (!isset($max['max'])) {
    $max['max'] = 0;
  }
  $max = $max['max'];
  $max++;
  switch ($type) {
    case '0':
      $res = sql::qi("INSERT INTO bank_trans(`id`, `uid`, `trans_id`, `mnum`, `trans_time`) VALUES (" . $max . ", " . $uid . ", 0, " . $money . ", " . time() . ")");
      if ($res) {
        $ans = '<div class=return_win align="center">Вы положили на счет ' . $money . ' LN';
      } else {
        $ans = '<div class=puns align="center">Вам не удалось положить ' . $money . ' LN на счет';
      }
      break;
    case '1':
      $res = sql::qi("INSERT INTO bank_trans(`id`, `uid`, `trans_id`, `mnum`, `trans_time`) VALUES (" . $max . ", " . $uid . ", 1, " . $money . ", " . time() . ")");
      if ($res) {
        $ans = '<div class=return_win align="center">Вы сняли со счета ' . $money . ' LN';
      } else {
        $ans = '<div class=puns align="center">Вам не удалось снять ' . $money . ' LN со счета';
      }
      break;
    case '2':
      $res1 = sql::qi("INSERT INTO bank_trans(`id`, `uid`, `trans_id`, `tto`, `mnum`, `trans_time`) VALUES(" . $max . ", " . $uid . ", 2, " . $uid2 . ", " . $money . ", " . time() . ")");
      $max++;
      $res2 = sql::qi("INSERT INTO bank_trans(`id`, `uid`, `trans_id`, `tfrom`, `mnum`, `watch`, `trans_time`) VALUES(" . $max . ", " . $uid2 . ", 3, " . $uid . ", " . $money . ", 1," . time() . ")");
      $f_lastip = sql::q1('SELECT `lastip`, `user` FROM users WHERE `uid`=' . $uid . '');
      $t_lastip = sql::q1('SELECT `lastip`, `user` FROM users WHERE `uid`=' . $uid2 . '');
      $tto = $t_lastip['user'];
      $tfrom = $f_lastip['user'];
      if ($res1 && $res2) {
        $ans = '<div class=return_win align="center">Вы перевели ' . $money . ' LN на счет игрока ' . $tto[0];
      } else {
        $ans = '<div class=puns align="center">Вам не удалось перевести ' . $money . ' LN на счет игрока ' . $tto[0];
      }
      transfer_log(3, $uid, $tto, 0, $money, 'Банковский перевод', $f_lastip['lastip'], $t_lastip['lastip']);
      transfer_log(6, $uid2, $tfrom, $money, 0, 'Банковский перевод', $t_lastip['lastip'], $f_lastip['lastip']);
      break;
    default:
      //NOP
      break;
  }
  $ans = $ans . '</div>';
  return $ans;
} // end add_trans();

function show_trans()
{
  $max_show = 30;
  $pages_show = 5;
  if (isset($_GET['sort'])) {
    if (intval($_GET['sort']) >= 0 && intval($_GET['sort']) < 5) {
      $sort = intval($_GET['sort']);
    } else {
      $sort = '5';
    }
  } else {
    $sort = '5';
  }
  if ($sort != '5') {
    $trnum = 'SELECT count(id) as count FROM bank_trans WHERE `uid`=' . UID . ' AND `trans_id`=' . $sort;
  } else {
    $trnum = 'SELECT count(id) as count FROM bank_trans WHERE `uid`=' . UID;
  }
  $tr_num = sql::q1($trnum);
  $pages = ceil($tr_num['count'] / $max_show);
  if (isset($_GET['page'])) {
    $cur_page = intval($_GET['page']);
    $page = intval($_GET['page']);
  } else {
    $cur_page = 1;
    $page = $pages;
  }
  if ($tr_num['count'] > $max_show) {
    $start = $tr_num['count'] - $max_show * $cur_page + 1;
    $stop = $max_show;
  } else {
    $start = 0;
    $stop = $tr_num['count'];
  }
  if ($start < 0) {
    $start = 0;
  }
  // иребуется переработка блока
  if ($tr_num['count'] > 0) {
    if ($sort == '5' || !isset($sort)) {
      $sql = 'SELECT * FROM bank_trans WHERE `uid`=' . UID . ' LIMIT ' . $start . ',' . $stop;
    } else {
      $sql = 'SELECT * FROM bank_trans WHERE `uid`=' . UID . ' AND `trans_id`=' . $sort . ' LIMIT ' . $start . ',' . $stop;
    }
    $zapros = sql::q1($sql);
    foreach ($zapros as $trans) {
      if ($trans[2] == 2) {
        $tto = sql::q1('SELECT `user` FROM users WHERE `uid`=' . $trans[3]);
        $trans[3] = $tto['user'];
      }
      if ($trans[2] == 3) {
        $tfrom = sql::q1('SELECT `user` FROM users WHERE `uid`=' . $trans[4]);
        $trans[4] = $tfrom['user'];
      }
      $trans[7] = date("d.m.Y H:i", $trans[7]);
      $res[] = $trans[0] . 'EL' . $trans[1] . 'EL' . $trans[2] . 'EL' . $trans[3] . 'EL' . $trans[4] . 'EL' . $trans[5] . 'EL' . $trans[6] . 'EL' . $trans[7];
    }
    // конец перерабатываемого блока
    $res = array_reverse($res);
    $res = implode("LINE", $res);
    echo '<script language="javascript" src="js/bank.js"></script>
          <script>trans=' . "'" . $res . "';" . '
          show_trans(trans,5);</script>';
    unset($res);
    if ($pages > 1) {
      for ($i = 1; $i < $pages_show; $i++) {
        if ($page - $i > 0) {
          $tmp[] = $page - $i;
        }
        if ($page + $i <= $pages) {
          $tmp[] = $page + $i;
        }
      }
      $tmp[] = $page;
      sort($tmp);
      echo "<center><table width=70% border='0' cellspacing='0' cellpadding='0' bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>";
      echo '<tr><td class=timef colspan=2>Страница ' . $page . ' из ' . $pages . ': ';
      $m = 0;
      $n = 0;
      foreach ($tmp as $key) {
        if ($key == 1) {
          $m = 1;
        }
        if ($key == $pages) {
          $n = 1;
        }
      }
      if ($m == 0) {
        echo '<a class=timef href="main.php?act=trans&page=1&sort=' . $sort . '">Первая</a> ';
      }
      for ($i = 0; $i < count($tmp); $i++) {
        echo '<a class=timef href="main.php?act=trans&page=' . $tmp[$i] . '&sort=' . $sort . '">' . $tmp[$i] . '</a> ';
      }
      if ($n == 0) {
        echo '<a class=timef href="main.php?act=trans&page=' . $pages . '&sort=' . $sort . '">Последняя</a> ';
      }
      echo '<br><br></td></tr></table></center>';
    }
    $in_sum = sql::q1('SELECT sum(mnum) as sum FROM bank_trans WHERE `trans_id`=0 AND `uid`=' . UID);
    $out_sum = sql::q1('SELECT sum(mnum) as sum  FROM bank_trans WHERE `trans_id`=1 AND `uid`=' . UID);
    $tto_sum = sql::q1('SELECT sum(mnum) as sum  FROM bank_trans WHERE `trans_id`=2 AND `uid`=' . UID);
    $tfrom_sum = sql::q1('SELECT sum(mnum) as sum  FROM bank_trans WHERE `trans_id`=3 AND `uid`=' . UID);
    echo "<center><table width=70% border='0' cellspacing='0' cellpadding='0' bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>";
    echo '<tr><td  class=timef>Всего положено на счет: ' . $in_sum['sum'] . ' LN</td></tr>';
    echo '<tr><td  class=timef>Всего снято со счета: ' . $out_sum['sum'] . ' LN</td></tr>';
    echo '<tr><td  class=timef>Всего переведено другим игрокам: ' . $tto_sum['sum'] . ' LN</td></tr>';
    echo '<tr><td  class=timef>Всего получено от других игроков: ' . $tfrom_sum['sum'] . ' LN</td></tr>';
    echo "</table></center>";
  } else {
    echo '<br><div align="center"><table  width=70% border="0" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
    echo '<tr valign="top"><td width=200>Показать переводы:<br><a class=timef  href="main.php?act=trans">Все</a><br>';
    echo '<a class=timef  href="main.php?act=trans&sort=0")>Вклады на счет</a><br>';
    echo '<a class=timef  href="main.php?act=trans&sort=1">Снятие со счета</a><br>';
    echo '<a class=timef  href="main.php?act=trans&sort=2">Переводы другим игрокам</a><br>';
    echo '<a class=timef  href="main.php?act=trans&sort=3">Переводы мне</a><br>';
    echo '<a class=timef  href=main.php >Закрыть транзакции</a></td><td>';
    echo '<strong>Транзакции данного типа отсутсвуют.</strong></td></tr></table></div><br>';
  }
} // end of show_trans();


$res = sql::q("SELECT * FROM `wp` WHERE (`uidp`=" . $pers["uid"] . ") and weared=0 AND `auction` <> '1' AND in_bank=1");

echo "<center><table border=2 width=60% cellspacing=2 cellpadding=2 bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>";
$counter = 0;


foreach ($res as $vesh) {
  $counter++;
  echo "<tr><td align=left class=weapons_box>";
  include("./inc/inc/weapon.php");

  if (strpos(" " . $pers["location"], "bank") > 0 and $v["where_buy"] <> 1 and ($v["where_buy"] <> 2 or $v["p_type"] == 5 or $v["p_type"] == 6 or $v["type"] == "rune") and $v["clan_name"] == "")
    $buttons = "<td><input type=button class=inv_but value='Забрать' onclick=\"location='main.php?getbank=" . $vesh["id"] . "';\"></td>";
  echo "<table border=0 width=100%><tr>" . $buttons . "</tr></table></td></tr>";
}
unset($res);
echo "</table></center>";

if ($counter == 0) echo "<i class=timef>У вас нет вещей в банке.</i>";
?>