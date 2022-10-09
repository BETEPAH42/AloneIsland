<?
function start_t1($a = 20, $STEP = 1, $tour = 1, $quest_id = 1)
{
	global $t1, $pers;
	$users = "SELECT * FROM users WHERE tour=" . $tour . ";";
	if ($tour == 1)
		$bots = "SELECT * FROM bots WHERE level=10 ORDER BY RAND() LIMIT 0,20;";
	if ($tour == 2)
		$bots = "SELECT * FROM bots WHERE level=15 ORDER BY RAND() LIMIT 0,20;";
	if ($tour == 3)
		$bots = "SELECT * FROM bots WHERE level=22 ORDER BY RAND() LIMIT 0,20;";
	$us1 = array();
	$r = array();
	$u_counter = 0;
	for ($i = 0; $i < $a; $i++) {
		$u = SQL::q1($users);
		$u1 = $u;
		if (!$u) {
			$u = SQL::q1($bots);
			if (!$u)
				$u = $u1;
			$us1[] = "bot=" . $u["id"] . "";
			$r[] = $u["rank_i"];
		} else {
			$us1[] = $u["user"];
			$r[] = $u["rank_i"];
			$u_counter++;
		}
	}
	$c1 = count($us1);
	for ($i = 0; $i < $c1; $i++)
		for ($j = 0; $j < $i; $j++)
			if ($r[$j] < $r[$j + 1]) {
				$tmp = $r[$j];
				$r[$j] = $r[$j + 1];
				$r[$j + 1] = $tmp;
				$tmp = $us1[$j];
				$us1[$j] = $us1[$j + 1];
				$us1[$j + 1] = $tmp;
			}
	$p1 = '';
	$p2 = '';
	for ($i = 0; $i < $c1; $i++)
		if ($i % 2 == 0)  		$p1 .= $us1[$i] . "|";
		else				$p2 .= $us1[$i] . "|";
	if (!$u_counter)
		sql::q("UPDATE quest SET finished=1,time=" . tme() . " WHERE id=" . $quest_id);
	return begin_fight($p1, $p2, "Турнир №1. Этап №" . $STEP . ".", "80", "900", "1", 0, TOUR1, 1);
}
