<?php

use App\providers\AppProvider;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../configs/app.php';
require __DIR__ . '/../helpers/helper.php';

try {
    $obj = new AppProvider;
    $obj->run();
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}