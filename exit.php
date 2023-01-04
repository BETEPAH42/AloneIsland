<?php
include_once "configs/config.php";
SQL::q("UPDATE `users` SET `online` = '0',timeonline=timeonline+(" . time() . "-lastvisits) WHERE uid='" . intval($_COOKIE["uid"]) . "';");
include("index.php");
