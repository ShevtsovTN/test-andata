<?php

namespace App\classes;

use Exception;
use ReflectionClass;
use ReflectionException;

class Router
{

    private string $action;
    private array $params = [];
    private string $controller;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $routes = include(routes());
        $requestUri = explode('?', $_SERVER['REQUEST_URI'])[0];
        if (array_key_exists($requestUri, $routes)) {
            $this->controller = $routes[$requestUri][0];
            $this->action = $routes[$requestUri][1];
        } else {
            throw new Exception('Error request!!!');
        }
    }

    /**
     * Обработка запроса и вызов нужного контроллера
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function processRequest()
    {
        if (class_exists($this->controller)) {
            $ref = new ReflectionClass($this->controller);
            if ($ref->hasMethod($this->action)) {
                if ($ref->isInstantiable()) {
                    $class = $ref->newInstance();
                    $method = $ref->getMethod($this->action);
                    $input = file_get_contents('php://input');
                    if (!empty($input)) {
                        foreach (json_decode(file_get_contents('php://input'), true) as $index => $item) {
                            $this->params['POST'][$index] = htmlspecialchars($item);
                        }
                    }
                    if (!empty($_GET)) {
                        foreach ($_GET as $index => $item) {
                            $this->params['GET'][$index] = htmlspecialchars($item);
                        }
                    }
                    return $method->invoke($class, $this->params);
                }
            }
        }
        throw new Exception('Page request!!!');
    }
}