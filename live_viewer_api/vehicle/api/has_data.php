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
  $statement = $conn->prepare('SELECT COUNT(*) FROM fact_vehicle_observation WHERE lane_num=? AND ? >= observed_at AND observed_at >= ?');
  $lane_num = (int)$_GET['timestamp'];
  $start = (double)$_GET['start'];
  $stop = (double)$_GET['stop'];
  $statement->bind_param('idd', $lane_num, $start, $stop);
  $statement->execute();
  $result = $statement->get_result();
  if ($result->num_rows == 0) {
    $result_array['count'] = 0
  }
  else {
    $row = $result->fetch_assoc();
    $result_array['count'] = (int)$row['COUNT(*)']
  }
  return $result_array;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>






