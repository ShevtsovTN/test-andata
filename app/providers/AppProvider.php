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
     * @throws ReflectionException
     * @throws Exception
     */
    public function run(): void
    {
        $this->db->setConnection(host: DB_HOST, user: DB_USER, pass: DB_PASSWORD, dbName: DB_NAME);
        $this->router->processRequest();
    }
}