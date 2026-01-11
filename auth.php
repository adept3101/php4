<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

function register(){

    $conn = connect();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $login = $conn->real_escape_string($data['login'] ?? '');
    $password = $conn->real_escape_string($data['password'] ?? '');

    $sql = "INSERT INTO users (login, password) VALUES ('$login', '$password')";

    if ($conn->query($sql) === TRUE) {
        $result = ['success' => true, 'message' => 'Новая запись успешно добавлена'];
    } else {
        $result = ['success' => false, 'error' => $conn->error];
    }

    $conn->close();
    return $result;
}

$res = register();

echo json_encode($res);

