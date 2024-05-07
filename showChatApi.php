<?php
header("Content-type: application/json; charset=utf-8");

require_once 'classes/autoload.php';
include_once 'inc/functions.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === "GET") {
    $tme =  time();
    if ($_GET['show'] == 'all') {
        $res = SQL::q("SELECT * FROM `chat` WHERE `show`= 0 and `time`< " . $tme . ";");
        SQL::q("UPDATE `chat` SET `show`= 1 WHERE `time`< " . $tme . ";");
        if (count($res) > 0) {
            http_response_code(200);

            $json = json_encode([
                'status' => true,
                'messages' => $res
            ]);
            echo $json;
        } elseif (count($res) == 0) {
            http_response_code(200);

            $json = json_encode([
                'status' => false,
                'messages' => "No messages"
            ]);
            echo $json;
        }
    } else {
        $json = json_encode([
            'status' => false,
            'messages' => "No messages"
        ]);
        echo $json;
    }
}
