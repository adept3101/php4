<?php
session_start();

function auth($login, $password) {
    // Подключение к базе данных
    include "db_connect.php";
    $conn = connect();
    
    // Экранирование данных для безопасности
    $login = $conn->real_escape_string($login);
    $password = $conn->real_escape_string($password);
    
    // Запрос для проверки пользователя
    $sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        // Пользователь найден, записываем данные в сессию
        $user = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['is_authorized'] = true;
        
        $conn->close();
        return true;
    } else {
        // Пользователь не найден
        $conn->close();
        return false;
    }
}
?>
