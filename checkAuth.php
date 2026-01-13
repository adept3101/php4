<?php
session_start();

function checkAuth() {
    if (isset($_SESSION['is_authorized']) && $_SESSION['is_authorized'] === true) {
        return true;
    } else {
        return false;
    }
}

function getCurrentUser() {
    if (checkAuth()) {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'login' => $_SESSION['user_login'] ?? null,
            'is_authorized' => true
        ];
    } else {
        return [
            'is_authorized' => false
        ];
    }
}
?>
