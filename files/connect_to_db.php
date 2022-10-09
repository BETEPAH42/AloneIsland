<?php
include_once "db_user_pass.php";
error_reporting(0);
$dbcnx = @mysqli_connect($dblocation, $dbuser, $dbpassw);
if(! $dbcnx) {
 exit;
}
@mysqli_select_db($dbname, $dbcnx);
?>