<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

function deleteData()
{
    $conn = connect();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !is_array($data)) {
        error_log("JSON decode failed or empty data");
        return ['success' => false, 'error' => 'No data received or invalid JSON'];
    }

    $id = $conn->real_escape_string($data['id'] ?? '');

    if (empty($id)) {
        return ['success' => false, 'error' => 'ID is required for deletion'];
    }

    $check_sql = "SELECT id FROM camera WHERE id = '$id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows === 0) {
        return ['success' => false, 'error' => 'Record with this ID does not exist'];
    }

    // Удаляем запись
    $sql = "DELETE FROM camera WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $result = ['success' => true, 'message' => 'Запись успешно удалена'];
    } else {
        $result = ['success' => false, 'error' => $conn->error];
    }

    $conn->close();
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $res = deleteData();
} 
 else {
    $res = ['success' => false, 'error' => 'Method not allowed'];
}

echo json_encode($res);
