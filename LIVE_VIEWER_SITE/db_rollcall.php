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
#echo "Connected successfully\n";

function get_vins() {
  global $conn;
  $sql = "SELECT VIN FROM LIVE_VIEWER_VEHICLE_DATA GROUP BY VIN";
  $result = $conn->query($sql);
  $vins = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $vins[] = $row['VIN'];
    }
  }
  return $vins;
}

function get_latest_data_for_vin($vin) {
  global $conn;
  $statement = $conn->prepare('SELECT * FROM LIVE_VIEWER_VEHICLE_DATA WHERE VIN = ? ORDER BY id DESC LIMIT 1');
  $statement->bind_param('s', $vin);
  $statement->execute();
  $result = $statement->get_result();
  return $result->fetch_assoc();
}

function get_all_data() {
  $vins = get_vins();
  $all_data = array();
  foreach ($vins as $vin) {
    $all_data[$vin] = get_latest_data_for_vin($vin);
  }
  return $all_data;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>
