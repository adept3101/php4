<?php
function connect()
{
  $servername = "localhost";
  $username = "php";
  $password = "3101";
  $dbname = "l";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
  }
  return $conn;
}

function select_tb($table, $conn)
{
  $sql = "SELECT * FROM $table";
  $result = $conn->query($sql);
  return $result;
}
