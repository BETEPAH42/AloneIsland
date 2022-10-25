<?php
error_reporting(0);
include_once "classes/sql.php";
include_once "inc/functions.php";
$pers = SQL::q1("SELECT sign FROM users WHERE uid=" . intval($_COOKIE["uid"]) . "");
?>
<script type="text/javascript" src="js/newbutk.js"></script>
<script>
    show_buttons('<?php if ($pers["sign"] <> 'none') echo 1; ?>', <?= date("H") ?>, <?= date("i") ?>, <?= date("s") ?>);
</script>