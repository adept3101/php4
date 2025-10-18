<?php
header("Content-Type: application/json; charset=UTF-8");
include "db_connect.php";

$conn = connect();

$id = $conn->real_escape_string($_POST['id']);
$title = $conn->real_escape_string($_POST['tittle']);
$size = $conn->real_escape_string($_POST['size']);
$color = $conn->real_escape_string($_POST['color']);
$cost = $conn->real_escape_string($_POST['cost']);
$material_id = $conn->real_escape_string($_POST['material_id']);

$sql = "INSERT INTO camera (id, tittle, size, color, cost, material_id) VALUES ('$id', '$title', '$size', '$color', '$cost', '$material_id')";
if ($conn->query($sql) === TRUE) {
    echo "Новая запись успешно добавлена";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
