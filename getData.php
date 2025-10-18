<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

function getData(){
    $conn = connect();

    $table = "camera";
    $result = select_tb($table, $conn);

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    $conn->close();
}

$result = getData();
