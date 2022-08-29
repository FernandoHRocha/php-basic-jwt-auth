<?php

require('../vendor/autoload.php');

use app\database\Connection;
use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

header("Access-Control-Allow-Origin: *");

$login = htmlspecialchars(filter_input(INPUT_POST, 'login'));
$password = htmlspecialchars(filter_input(INPUT_POST, 'password'));

$pdo = Connection::connect();
$prepare = $pdo->prepare('select * from auth where login = :login');
$prepare->execute(['login' => $login]);
$result = $prepare->fetch();

if(!$result) {
    http_response_code(401);
}

if(!password_verify($password, $result->password)) {
    http_response_code(401);
}

$payload = [
    'exp' => time() + 10,
    'iat' => time(),
    'login' => $login
];

$jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

echo json_encode($jwt);