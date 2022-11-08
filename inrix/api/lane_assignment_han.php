<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

#echo $username;
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error)
{
  echo $conn->connect_error;
  die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully\n";

$vin = (string)$_GET['vin'];


$sql = "SELECT * FROM `circledb`.`dim_vehicle` LIMIT 0,1000";

$result = $conn->query($sql);

$target = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $target[] = $row; 
    }
  }

echo $target

$conn->close();
?>






