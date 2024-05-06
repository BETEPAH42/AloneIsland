<?php
include_once 'back_button.php';
?>
<div id="admin_menu"></div>
<script>
    $.ajax({
    method:'POST',
    url: "/ajax/admin_menu.php",
    data: { name: "John", location: "Boston" }
    }).done(function($data) {
        $("#admin_menu").html($data);
        // console.log($data)
    });
</script>
<?php
try {
//    SQL::q1('CREATE TABLE menus 
//         (
//             id INT PRIMARY KEY AUTO_INCREMENT,
//             name VARCHAR(50) NOT NULL,
//             type VARCHAR(30),
//             url VARCHAR(50) NOT NULL,
//             active BOOLEAN DEFAULT(FALSE) NOT NULL
//         );');
// SQL::q1('INSERT INTO menus SET name="Тестовая", type="admin", url="main.php?go=test", active=true;');
// $test = new Menus();
// $array = [
//     'name'=> 'test',
//     'url'=>'url',
// ];
// $test->add($array);
//    dd($test->add($array));
// $tt = new Services\Menu();

}
catch (Exception $e)
{
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}
catch (Error $er)
{
    echo "<pre>";
    var_dump($er);
    echo "</pre>";
}