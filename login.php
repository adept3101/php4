<?php
header("Content-Type: application/json; charset=UTF-8");
include "auth.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$login = $data['login'] ?? '';
$password = $data['password'] ?? '';

if (authorizeUser($login, $password)) {
    echo json_encode([
        'success' => true,
        'message' => 'Авторизация успешна',
        'user_login' => $_SESSION['user_login']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный логин или пароль'
    ]);
}
?>
