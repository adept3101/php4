<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

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

function get_material($table_name){
    $conn = connect();

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

/* $material = get_material("material"); */
$result = getData("camera");
