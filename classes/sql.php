<?php

class SQL
{
    /** Init and connect */
    private static function init()
    {
        $dbConnection = null;
        if (in_array($_SERVER['SERVER_NAME'], ['localhost', 'ry.alef.im'])) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL & ~E_NOTICE);
        } else {
            ini_set('display_errors', 'Off');
        }

        if (file_exists(__DIR__ . '/../db.cfg.php')) {
            include __DIR__ . '/../db.cfg.php';
            $dbConnection = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8', DB_USER, DB_PASS);
        } else {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/db.cfg.php')) {
                include $_SERVER['DOCUMENT_ROOT'] . '/db.cfg.php';
                $dbConnection = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8', DB_USER, DB_PASS);
            } else {
                $dbConnection = new PDO('mysql:dbname=game;host=localhost;charset=utf8', 'root', '');
            }
        }

        $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbConnection->exec('set names utf8');
        $dbConnection->exec('SET SESSION group_concat_max_len = 1000000');
        $dbConnection->exec("SET sql_mode=''");

        return $dbConnection;
    }

    public static function q($sql, $params = [])
    {
        $dbConnection = self::init();

        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (Exception $e) {
            if (in_array($_SERVER['SERVER_NAME'], ['hotcard.alef.dev', 'localhost'])) {
                echo '<pre>';

                echo $sql;
                echo "\n\n\n------- \n\n\n";
                print_r($params);
                die();
            } else {
                echo '<pre>';
                print_r($e);
                echo "\n\n\n------- \n\n\n";
                echo $sql;
                die("Q - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру. <br /> <a href='engine/exit.php'>Выход</a>");
            }
        }
    }

    public static function q1($sql, $params = [])
    {
        $dbConnection = self::init();

        try {
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (Exception $e) {
            if (in_array($_SERVER['SERVER_NAME'], ['hotcard.alef.dev', 'localhost'])) {
                echo '<pre>';
                print_r($e);
                echo "\n\n\n------- \n\n\n";
                echo $sql;
                echo "\n\n\n------- \n\n\n";
                print_r($params);
                die();
            } else {
                echo '<pre>';
                print_r($e);
                echo "\n\n\n------- \n\n\n";
                echo $sql;
                echo "\n\n\n------- \n\n\n";
                die("Q1 - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br /> <a href='engine/exit.php'>Выход</a>");
            }
        }
    }

    public static function qi($sql, $params = [], $ignore_exceptions = 0)
    {
        $dbConnection = self::init();
        try {
            $stmt = $dbConnection->prepare($sql);

            if ($stmt->execute($params)) {
                return $dbConnection->lastInsertId();
            }

            return false;
        } catch (Exception $e) {
            if ($ignore_exceptions) {
                return;
            }
            if (in_array($_SERVER['SERVER_NAME'], ['hotcard.alef.dev', 'localhost'])) {
                echo '<pre>';
                print_r($e);
                echo "\n\n\n------- \n\n\n";
                echo $sql;
                echo "\n\n\n------- \n\n\n";
                print_r($params);
                die();
            } else {
                die("Qi - Произошла ошибка в SQL-запросе. Обратитесь к Вашему менеджеру.<br /> <a href='engine/exit.php'>Выход</a>");
            }
        }
    }

    public static function qCount($sql, $params = [])
    {
        $dbConnection = self::init();

        $stmt = $dbConnection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

    public static function qRows()
    {
        $dbConnection = self::init();

        $stmt = $dbConnection->query('SELECT FOUND_ROWS() as num');

        return $stmt->fetchColumn(0);
    }
}
