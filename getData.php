<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

function getData($table_name, $conn){
    if ($table_name === 'camera') {
        // Для таблицы camera делаем JOIN с таблицами material и countries
        $sql = "SELECT 
                    c.id, 
                    c.tittle, 
                    c.size, 
                    c.color, 
                    c.cost,
                    m.material as material_name,
                    co.name as country_name
                FROM camera c
                LEFT JOIN material m ON c.material_id = m.id
                LEFT JOIN countries co ON c.country_id = co.id";
        
        $result = $conn->query($sql);
    } else {
        // Для других таблиц оставляем как было
        $result = $conn->query("SELECT * FROM $table_name");
    }

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$input = json_decode(file_get_contents('php://input'), true);
$tableName = $input['tableName'] ?? '';

if ($tableName) {
    $conn = connect();
    $data = getData($tableName, $conn);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    $conn->close();
} else {
    echo json_encode(['error' => 'No table name provided']);
}
?>
