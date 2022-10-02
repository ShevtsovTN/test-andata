<?php

use App\providers\AppProvider;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../configs/app.php';
require __DIR__ . '/../helpers/helper.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Accept: application/json");
header("Content-type: application/json");

/**
 * Точка входа в приложение
 */
try {
    $obj = new AppProvider;
    echo json_encode($obj->run());
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}