<?

$cell = sql::q1("SELECT * FROM nature WHERE x=" . $x . " and y=" . $y . "");
// информация о ботах
if ($cell["bot"] <> '') {
    $max_bot_lvl = $cell["blvlmax"];
    $bots = "По слухам странников здесь ";
    if ($max_bot_lvl == 0) $bots .= "обитает малая живность, очень слабые существа...";
    if ($max_bot_lvl > 0 and $max_bot_lvl <= 7) $bots .= "обитает малая живность, слабые существа...";
    if ($max_bot_lvl > 7 and $max_bot_lvl <= 15) $bots .= "обитает средняя живность, агрессивные существа...";
    if ($max_bot_lvl > 15 and $max_bot_lvl <= 20) $bots .= "крепкие существа, но какие так никто и не знает. Это место таит в себе неизвестность...";
    if ($max_bot_lvl > 20 and $max_bot_lvl <= 35) $bots .= "обитают свирепые существа, были случаи жестоких убийств и расправ над бродягами...";
    if ($max_bot_lvl > 35) $bots = "Бегите отсюда! Всякие вошедший на эту территорию может проститься с жизнью в мгновение ока! Сильнейшие существа расправятся с вами при первой возможности!";
} else
    $bots = "Эту местность давно очистили от диких животных... Здесь безопасно как в городе.";
// сведения о карте
if ($cell["type"] == 0)
    $cell_type = "Город. Здесь вам ничего не угрожает. Но остерегайтесь воров!";
if ($cell["type"] == 1)
    $cell_type = "Дорога. Здесь проходят торговые пути. Возможна встреча с разбойниками.";
if ($cell["type"] == 2)
    $cell_type = "Поля, луга, богатые растительностью.";
if ($cell["type"] == 3)
    $cell_type = "Плоскогорье. Пески, сухой жаркий воздух(Анис, осот, финики...)";
if ($cell["type"] == 4)
    $cell_type = "Лесонасождения. Птички поют, зверушки бегают.";
if ($cell["type"] == 5)
    $cell_type = "Здесь давно не осталось ничего живого, кроме зловещих демонов";
if ($cell["type"] == 6)
    $cell_type = "Вода. Здесь вы можете заняться рыбной ловлей.";
if ($cell["type"] == 7)
    $cell_type = "Пещера. Сумрак, плохая видимость. Сверкают сталактиты.";
if ($cell["type"] == 8)
    $cell_type = "Заболоченная местность. Шагайте осторожно, чтобы не застрять здесь навечно.";
// вывод на информации о ресурсах
$resources = "";
if ($cell["fishing"])
    $resources .= "<br><span style='margin: 0 auto;'>Рыба.</span>";
if ($cell["wood"] <> 9 and $cell["wood"] <> 0)
    $resources .= "<br><span style='margin: 0 auto;'>Древесина.</span>";
elseif ($cell["wood"] == 9 and $pers["quest_NY"] == '1_1')
    $resources .= "<br><span style='margin: 0 auto;'>Древесина.</span>";
if ($cell["agriculture"])
    $resources .= "<br><span style='margin: 0 auto;'>Культурные растения.</span>";
if ($cell["herbal"])
    $resources .= "<br><span style='margin: 0 auto;'>Травы для алхимии.</span>";
if ($resources == "")
    $resources = "<br><span style='margin: 0 auto;'>Вы не нашли ресурсы на данной территории.</span>";
    // $resources = "<tr><td><span style= 'margin: 0 auto;'>Вы не нашли ресурсы на данной территории.</span></td></tr>";
// $resources = "<table class=LinedTable>" . $resources . "</table>";
// вывод на информации о ресурсах