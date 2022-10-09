<LINK href="css/vkladki.css" rel="STYLESHEET" type="text/css">
<center>Маяк Безмолвия</center>
<script type="text/javascript">
	$(document).ready(function() {
		$('ul.tabs').each(function() {
			$(this).find('li').each(function(i) {
				$(this).click(function() {
					$(this).addClass('current-tab').siblings().removeClass('current-tab')

						.parents('div.tab-div').find('div.tab-content').slideUp().eq($(this).index()).slideDown();
				});
			});
		});
	});
	//обновление ДИВА каждую секунду
</script>

<center>
	<table width=80%>
		<tr>
			<td width=190 valign=top><br><img src='images/locations/mayk_ribak.gif'><br>Мастер своего дела, настоящие профессионал, похоже, он знает язык рыб и уеет заманивать их в свои сети, так как улов его всегда богат и изобилует различными, даже редкими породами рыб.</td>
			<td width=5></td>
			<td valign=top>

				<div class="tab-div">
					<ul class="tabs">
						<li>Квесты</li>
						<li class="current-tab">Лавка Рыбака</li>
						<li>Профессия</li>
						<li>Турнир рыбаков</li>
					</ul>
					<br>
					<div class="tab-content">
						Квесты</div>
					<div class="tab-content visible">
						<?
						echo "<center><b><i>Лавка рыбака</b></i></center><br>";
						$prims = sql::q("SELECT * FROM weapons WHERE where_buy=0 and q_s>0 ORDER by sp6 ASC");
						$z = 0;
						echo "<center><table width=90% style={padding-top:10px; background-image: url(images/tabs2.png)}>";
						foreach ($prims as $prim) {
							if ($prim["type"] == "fishing") {

								if ($z <> 2) {
									if ($z == 0) echo "<tr>";
									$sprim = round($prim["price"] / $prim["max_durability"], 2);
									echo "<td width=50%><FIELDSET><LEGEND align=center>" . $prim["name"] . "</LEGEND><table width=100%><tr><td width=58>";
									echo "<img src='../images/weapons/fishing_prim/" . $prim["image"] . ".gif'></td><td valign=top>Цена за 1 шт.: " . $sprim . " LN<br>";
									if ($pers["sp6"] >= $prim["sp6"]) {
										$color = "green";
										$knopka = "Приобрести <input type=text name=kolvo value=0 maxlength=4 size=4> шт.<br>
				<input valing=left type=submit value=Купить>";
									} else {
										$color = "red";
										$knopka = "";
									}

									echo "Требуемый навык: <font color=" . $color . ">" . $prim["sp6"] . "</font><br>
			В наличии: {$prim["q_s"]} шт. <br>
			<form action='main.php?mayk=2&primanka=" . $prim['id'] . "&nameprim=" . $prim['name'] . "&sum=" . $sprim . "&kolvo' method='POST'>";

									echo $knopka . "</form>";
									echo "</td></tr></table></FIELDSET></td>";
									$z++;
									if ($z == 2) {
										$z = 0;
										echo "</tr>";
									}
								}
							}
						}
						if ($_POST["kolvo"] <> "" and $_POST["kolvo"] > 0) {
							$sumprim = $_POST["kolvo"] * $_GET["sum"];
							if ($pers["money"] > $sumprim) {
								// переделать функцию, так чтобы не только помещала в рюкзак, но и снимала деньги, прибавляля к существующим и уменьшала колличество в наличии на маяке (в БД создать торговую точку Маяк)
								buy_prim_mayk($_GET["primanka"], $pers["uid"], $_POST["kolvo"]);
								$pers["money"] = $pers["money"] - $sumprim;
								SQL::q("UPDATE `users` SET money='" . $pers["money"] . "' WHERE user='" . $pers['user'] . "'");
								echo "Вы купили " . $_GET['nameprim'] . " в количестве " . $_POST['kolvo'] . " шт. за " . $sumprim . "";
							} else echo "<center>У вас недостаточно средств.</center>";
						} elseif ($_POST["kolvo"] < 0) echo "<center>Введены не правильные символы, поставлен знак -.</center>";

						echo '</table></center>'; ?>
					</div>
					<div class="tab-content">
						<?
						echo "<center><b><i>Получение профессии \"Рыбак\"</b></i></center><br>";
						if ($pers["prof_osn"] <> "fishing" and $pers["prof_osn"] <> "none" and $pers["prof_osn"] <> "") {
							echo "Сейчас вы обладаете профессией " . prof_pers($pers["prof_osn"]) . "!<br>";
							echo "<input type=button onclick=\"location.href='main.php?mayk=3&prof=1'\" value='Получить профессию рыбака'>";
						} elseif ($pers["prof_osn"] <> "fishing") {
							echo "У вас нет профессии.<br>";
							echo "<input type=button onclick=\"location.href='main.php?mayk=3&prof=1'\" value='Получить профессию рыбака'>";
						} else echo "Вы уже обучены профессии 'Рыбак'";

						if ($_GET['prof'] == 1 and $pers["prof_osn"] <> "fishing") {
							set_vars("prof_osn='fishing', prof_osnLVL='0'", UID);
							say_to_chat('f1', 'Вы получили профессию <b>Рыбак</b>!', 1, $pers["user"], '*', 0);
						}

						echo "
	</div>
<div class='tab-content' id='tournir'>
    <center><b><i>Турниры \"Рыбаков\"</b></i></center><br>";
						include('quest/tournir_fish.php');
						echo "</div>";
						?>
					</div><!-- .section -->

			</td>
		</tr>
	</table>
</center>