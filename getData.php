<?php
function select_tb($table, $conn){
  $sql = "SELECT * FROM $table";
  $result = $conn->query($sql);
  return $result; 
}
?>
