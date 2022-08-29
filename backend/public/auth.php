<?php

require('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-roken, x_csrftoken, Cache-Control, X-requested-With");

$token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
$key = new Key($_ENV['JWT_KEY'], 'HS256');
try {
    $decoded = JWT::decode($token, $key);
    echo json_encode($decoded);
} catch(Throwable $e) {
    if($e->getMessage() == 'Expired token') {
        http_response_code(401);
    }

}

echo json_encode($token);