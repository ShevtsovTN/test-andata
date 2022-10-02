<?php

namespace App\http\Controllers;

use App\classes\Validator;
use App\models\Comment;
use App\services\CommentService;
use Exception;

class CommentController
{

    private CommentService $service;
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator(Comment::$validate);
        $this->service = new CommentService();
    }

    /**
     * Получение списка комметариев
     *
     * @throws Exception
     */
    public function indexAction(array $params = []): array
    {
        return $this->service->index($params);
    }

    /**
     * Создание комментария
     *
     * @throws Exception
     */
    public function createAction(array $params = []): array
    {
        $params = $this->validator->validated($params);
        return $this->service->create($params);
    }
}