<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

/* $data = json_decode(); */

function getData($table_name){
    
    $conn = connect();

    /* $table = "camera"; */
    $result = select_tb($table_name, $conn);

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    $conn->close();
}

/* $result = getData("camera"); */
/* $res = getData("material") */

$input = json_decode(file_get_contents('php://input'), true);
$tableName = $input['tableName'] ?? '';

if ($tableName) {
    getData($tableName);
} else {
    echo json_encode(['error' => 'No table name provided']);
}


$res = getData($data);
