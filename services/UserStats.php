<?php
include_once '../classes/sql.php';
// include '../inc/functions.php';
header("Content-type: application/json; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "POST")
    if (isset($_POST)) {

        $i = 0;
        foreach ($_POST as $key => $w) {
            if ($key == 'uid') {
                $uid = (int)$w;
                continue;
            }
            if ($key == 'ups') {
                $set .= " free_stats=" . $w . ",";
                continue;
            }
            $i++;
            $arr[$i] = $key . "=" . $w . ",";
            $set .= " " . $key . "=" . $w . ",";
        }
        $set = substr($set, 0, -1);
        if (isset($uid)) {
            SQL::q("UPDATE users SET " . $set . " WHERE uid=" . intval($uid) . "");
            http_response_code(200);
            $json = json_encode([
                'status' => true,
                'messages' => $set
            ]);

            echo $json;
        } else {
            $arr = [
                '1' => false,
                '2' => 'Uid not use'
            ];
            $json = json_encode($arr);

            echo $json;
        }
    } else {
        $arr = [
            '1' => false,
            '2' => 'Post not found'
        ];
        $json = json_encode($arr);

        echo $json;
    }
