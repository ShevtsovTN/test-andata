<?php

namespace App\db;

use App\interfaces\DatabaseInterface;
use Exception;
use mysqli;
use mysqli_result;

class Database implements DatabaseInterface
{

    private static $_instance;
    private mysqli $ins_db;

    private function __construct()
    {

    }

    public static function getInstance(): Database
    {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }

        return self::$_instance = new self();
    }

    /**
     * @throws Exception
     */
    public function setConnection(string $host, string $user, string $pass, string $dbName)
    {
        try {
            $this->ins_db = new mysqli($host, $user, $pass, $dbName);

            if ($this->ins_db->connect_error) {
                throw new Exception(sprintf('Ошибка соединения : %s', $this->ins_db->connect_errno));
            }

            $this->ins_db->query('SET NAMES \'UTF8\'');
        } catch (Exception $e) {
            exit();
        }

    }

    public function query($sql): mysqli_result|bool
    {
        return $this->ins_db->query($sql);
    }

    public function getInstanceDB(): mysqli
    {
        return $this->ins_db;
    }
}