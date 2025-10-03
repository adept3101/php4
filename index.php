<?php
include "db_connect.php";

$conn = connect();

$table = "camera";
$result = select_tb($table, $conn);
echo "<h1>Фотоаппараты</h1>";
if ($result->num_rows > 0) {
	#echo "<table border=1><tr><th>ID</th><th>tittle</th></tr>";
	echo "<table border=1><tr><th>ID</th><th>tittle</th><th>size</th><th>color</th><th>cost</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["tittle"]."</td><td>".$row["size"]."</td><td>".$row["color"]."</td><td>".$row["cost"]."</td></tr>";
    }
  echo "</table>";
} else {
    echo "Нет данных";
}

$conn->close();
?>

