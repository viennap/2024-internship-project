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


function get_all_data() {
  global $conn;
  $sql = "WITH last_time AS (SELECT max(published_at) as last_time FROM fact_speed_planner) SELECT sp.published_at, sp.position, sp.lane_num, sp.target_speed, sp.max_headway FROM fact_speed_planner sp JOIN last_time lt on sp.published_at = lt.last_time";
  $result = $conn->query($sql);
  $target = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $target[] = $row; 
    }
  }
  return $target;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>






