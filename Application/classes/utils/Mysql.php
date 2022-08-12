<?php

namespace Application\Classes\Utils;

use Exception;
use mysqli;

abstract class Mysql
{
    private static mysqli $mysqli_object;

    /**
     * Подключение к базе данных
     * @param string $host хост сервера
     * @param string $user пользователь
     * @param string $pass пароль
     * @param string $dbname название базы
     * @return void
     */
    public static function connect(string $host, string $user, string $pass, string $dbname): void
    {
        try {
            self::$mysqli_object = new mysqli($host, $user, $pass, $dbname);
            if (self::$mysqli_object->connect_errno) {
                throw new Exception('Connect error: ' . self::$mysqli_object->connect_errno);
            }
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }

    /**
     * Get base
     * @return mysqli
     */
    public static function Db(): mysqli
    {
        return self::$mysqli_object;
    }
}