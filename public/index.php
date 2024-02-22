<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {

    date_default_timezone_set('America/Mexico_City');
    $httpOrigin = $_SERVER["HTTP_ORIGIN"] ?? "";
    $allowOrigin = ["http://localhost:4002", "http://127.0.0.1:3000", ""];
    if(in_array($httpOrigin, $allowOrigin)){
        header("Access-Control-Allow-Origin: {$httpOrigin}");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Auth-Token, Authorization");
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    }
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
