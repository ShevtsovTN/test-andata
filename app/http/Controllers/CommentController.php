<?php

namespace App\http\Controllers;

use Exception;

class CommentController extends Controller
{
    /**
     * @throws Exception
     */
    public function indexAction()
    {
        $this->page = $this->view->render('index', ['title' => 'TEST']);

        echo $this->getOutput();
    }

    public function createAction()
    {

    }
}