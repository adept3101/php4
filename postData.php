<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

function postData()
{
    $conn = connect();

    // Получаем JSON данные
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !is_array($data)) {
        error_log("JSON decode failed or empty data");
        return ['success' => false, 'error' => 'No data received or invalid JSON'];
    }

    $id = $conn->real_escape_string($data['id'] ?? '');
    $title = $conn->real_escape_string($data['tittle'] ?? '');
    $size = $conn->real_escape_string($data['size'] ?? '');
    $color = $conn->real_escape_string($data['color'] ?? '');
    $cost = $conn->real_escape_string($data['cost'] ?? '');
    $material_id = $conn->real_escape_string($data['material_id'] ?? '');

    if (empty($id) || empty($title)) {
        return ['success' => false, 'error' => 'Required fields are missing'];
    }

    $sql = "INSERT INTO camera (id, tittle, size, color, cost, material_id) VALUES ('$id', '$title', '$size', '$color', '$cost', '$material_id')";

    if ($conn->query($sql) === TRUE) {
        $result = ['success' => true, 'message' => 'Новая запись успешно добавлена'];
    } else {
        $result = ['success' => false, 'error' => $conn->error];
    }

    $conn->close();
    return $result;
}

$res = postData();

echo json_encode($res);
