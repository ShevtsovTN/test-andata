<?php

namespace App\models;

class Comment extends Model
{
    /**
     * Статическое свойство с правилами валидации
     *
     * @var array|string[][]
     */
    public static array $validate = [
        'title' => ['min:3', 'max:50'],
        'content' => ['min:10', 'max:300'],
        'email' => ['min:3', 'max:20', 'email'],
        'name' => ['min:3', 'max:50'],
    ];
}