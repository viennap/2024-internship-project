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
  $result_array = array();
  $second = (int)$_GET['second'];
  $lane_num = (int)$_GET['lane_num'];
  $statement = $conn->prepare('SELECT position, target_speed, speed_estimate FROM fact_speed_planner WHERE published_at = ? AND lane_num = ?');
  $statement->bind_param('ii', $second, $second_sub_history_time);
  $statement->execute();
  $result = $statement->get_result();
  while ($row = $result->fetch_assoc()) {
    $result_array[] = $row;
  }
  return $result_array;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>






