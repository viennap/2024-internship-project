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

if (array_key_exists("mac", $_GET)) {
  $mac = $_GET["mac"];
  $statement = $conn->prepare('SELECT * FROM piStatus WHERE wlan0_mac = ? ORDER BY id DESC LIMIT 1');
  $statement->bind_param('s', $mac);
  $statement->execute();
  $result = $statement->get_result()->fetch_assoc();
  header("Content-Type: application/json");
  echo json_encode($result);
  $conn->close();
}
else {
  $conn->close();
  die("No mac supplied!");
}
?>
