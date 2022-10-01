<?php

function view_path(string $template = ''): string
{
    $path = BASE_PATH . '/resources/templates';
    return !empty($template)
        ? sprintf($path . '/%s.tpl', $template)
        : $path;
}