<?php

namespace App\services;

use App\models\Comment;
use Exception;

class CommentService
{
    /**
     * Метод сервиса для получения комментариев
     *
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        return Comment::all(!empty($data['GET']) ? $data['GET'] : []);
    }

    /**
     * Метод сервиса для создания комментария
     *
     * @throws Exception
     */
    public function create(array $data): array
    {
        if (isset($data['POST'])) {
            $comment = new Comment();
            $comment->name = $data['POST']['name'];
            $comment->email = $data['POST']['email'];
            $comment->title = $data['POST']['title'];
            $comment->content = $data['POST']['content'];
            $comment->created_at = date('Y-m-d H:i:s');
            $id = $comment->save();

            return Comment::find($id);
        }
        throw new Exception('Wrong data for creating', 422);
    }
}