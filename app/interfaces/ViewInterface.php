<?php

namespace App\interfaces;

interface ViewInterface
{
    public function render(string $template, array $params = []): string;
}