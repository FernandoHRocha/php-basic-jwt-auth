<?php

require('../vendor/autoload.php');

use app\database\Connection;
use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

header("Access-Control-Allow-Origin: *");

$login = htmlspecialchars(filter_input(INPUT_POST, 'login'));
$password = htmlspecialchars(filter_input(INPUT_POST, 'password'));

if(!$login || !$password) {
    http_response_code(500);
    echo json_encode('Falta parametros');
}

$pdo = Connection::connect();
$prepare = $pdo->prepare('select * from auth where login = :login');
$prepare->execute(['login' => $login]);
$result = $prepare->fetch();

if($result) {
    http_response_code(401);
    echo json_encode('O login já está sendo utilizado por outra pessoa.');
}

$password = password_hash($password, PASSWORD_DEFAULT);
$pdo = Connection::connect();
$prepare = $pdo->prepare("INSERT INTO auth (login, password) VALUES (:login, :password )");
$prepare->execute(
    [
        'login' => $login,
        'password' => $password
    ]
);
$result = $prepare->fetch();

$payload = [
    'exp' => time() + 10,
    'iat' => time(),
    'login' => $login
];

$jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

echo json_encode($jwt);