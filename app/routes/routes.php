<?php

use App\http\Controllers\CommentController;

return [
    '/' => [CommentController::class, 'indexAction'],
    '/create' => [CommentController::class, 'createAction']
];
