<?php

namespace App\http\Controllers;

use App\classes\View;

class Controller
{

    protected View $view;
    protected string $page;

    public function __construct()
    {
        $this->view = new View();
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function getOutput(): string
    {
        return $this->getPage();
    }
}