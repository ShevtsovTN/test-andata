<?php

namespace App\classes;

use App\interfaces\ViewInterface;
use Exception;

class View implements ViewInterface
{
    /**
     * @throws Exception
     */
    public function render(string $template, array $params = []): string
    {
        ob_start();

        extract($params ?? []);

        try {
            include(view_path($template));

            http_response_code(200);
            header('Content-Type: text/html');

            return ob_get_clean();
        } catch (Exception $e) {
            ob_clean();
            throw new Exception('Test');
        }
    }
}