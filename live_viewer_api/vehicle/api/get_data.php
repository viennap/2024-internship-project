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
  $second = (double)$_GET['second'];
  $history_time = (double)$_GET['history_time'];
  $lane_num = (int)$_GET['lane_num'];
  $only_recent = (bool)((int)$_GET['only_recent']);
  $second_sub_history_time = $second - $history_time;
  $query = "";
  if (only_recent){
    $query = "WITH max_obs AS ( SELECT vin, max(observed_at) AS max_obs_at FROM fact_vehicle_observation WHERE 1=1 AND lane_num = ? AND ? >= observed_at AND observed_at >= ? GROUP BY 1) 
    SELECT mo.max_obs_at AS observed_at, vo.position, vo.speed, vo.lane_num, vo.vin, vo.source, vp.acc_status, vp.is_wb, vp.latitude, vp.longitude FROM max_obs mo 
    JOIN fact_vehicle_observation vo ON 1=1 AND mo.vin = vo.vin AND mo.max_obs_at = vo.observed_at JOIN fact_vehicle_ping vp ON 1=1 AND vp.gpstime = mo.max_obs_at AND vp.vin = mo.vin";
  else {
    $query = "SELECT observed_at, position, speed, lane_num, vin, source, acc_status, is_wb, latitude, longitude  FROM fact_vehicle_observation 
    WHERE lane_num = ? AND ? >= observed_at AND observed_at >= ? ORDER BY vin, observed_at";
  }
  $statement = $conn->prepare($query);
  $statement->bind_param('idd', $lane_num, $second, $second_sub_history_time);
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






