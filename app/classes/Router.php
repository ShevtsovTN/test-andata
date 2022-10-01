<?php

namespace App\classes;

use Exception;
use ReflectionClass;
use ReflectionException;

class Router
{

    private string $action = 'indexAction';
    private array $params = [];
    private string $controller = 'App\\http\\Controllers\\';
    private string $requestUrl;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $this->requestUrl = substr($requestUri, strlen(SITE_URL));
        $url = explode('/', rtrim($this->requestUrl, '/'));

        if (!empty($url[0])) {
            $this->controller .= sprintf('%sController', ucfirst($url[0]));
        } else {
            $this->controller .= 'CommentController';
        }

        if (!empty($url[1])) {
            $this->action = sprintf('%sAction', $url[1]);
        }

        if (!empty($url[2])) {
            $count = count($url);
            $key = [];
            $value = [];

            for ($i = 2; $i < $count; $i++) {
                if ($i % 2 == 0) {
                    $key[] = $url[$i];
                } else {
                    $value[] = $url[$i];
                }
            }
            if (!$this->params = array_combine($key, $value)) {
                throw new Exception('Error request!!!');
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function processRequest(): void
    {
        if (class_exists($this->controller)) {
            $ref = new ReflectionClass($this->controller);
            if ($ref->hasMethod($this->action)) {
                if ($ref->isInstantiable()) {
                    $class = $ref->newInstance();
                    $method = $ref->getMethod($this->action);

                    $method->invoke($class, $this->params);
                }
            }
        } else {
            throw new Exception('Page request!!!');
        }
    }
}