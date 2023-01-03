<?php

if (!file_exists("../service/top_gamers/A" . date("d-m-y") . ".txt")) {
  $top = "var list=new Array(\n";
  $i = 0;
  // var_dump($res);
  foreach (SQL::q("SELECT * FROM `users` WHERE priveleged = 0 and block='' and lasto<>0 ORDER BY (level*1000+exp/(victories+losses+1)+(victories-losses) + rank_i*100) LIMIT 0 , 100") as $r) {
    $stats = floor(($r["level"] * 1000 + $r["exp"] / ($r["victories"] + $r["losses"] + 1) + ($r["victories"] - $r["losses"]) + 100 * $r["rank_i"]));
    if ($i <> 0)
      $top .= ",";
    $i++;
    if ($r["sign"] == '')
      $r["sign"] = "none";
    $r["state"] = str_replace("|", "", $r["state"]);
    $top .= "'" . $r["user"] . "|" . $r["level"] . "|" . $r["sign"] . "|" . $r["state"] . "|" . abs($stats) . "|" . $r["uid"] . "|"; //".$z."|
    $top .= "'";
  }
  $top .= ");";
  $top .= "show_list ('0+');";
  $f = fopen("service/top_gamers/A" . date("d-m-y") . ".txt", "w");
  fwrite($f, $top);
  fclose($f);
}
