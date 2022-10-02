<?php

namespace App\providers;

use App\db\Database;
use App\classes\Router;
use Exception;
use ReflectionException;

class AppProvider
{

    private Router $router;
    private Database $db;

    public function __construct()
    {
        $this->router = new Router();
        $this->db = Database::getInstance();
    }

    /**
     * Запуск приложения (подключение к БД, обработка реквеста)
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function run()
    {
        $this->db->setConnection(host: DB_HOST, user: DB_USER, pass: DB_PASSWORD, dbName: DB_NAME);
        return $this->router->processRequest();
    }
}