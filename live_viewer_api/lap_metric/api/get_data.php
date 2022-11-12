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
  $tolerance = (double)$_GET['tolerance'];
  $second_sub_tolerance_time = $second - $tolerance;
  $statement = $conn->prepare("SELECT vin, lap_uuid, start_time, start_position, end_time, end_position, avg_speed, fuel_economy, pct_time_infeasible 
  FROM fact_lap_metrics a INNER JOIN ( SELECT vin, MAX(end_time) AS end_time FROM fact_lap_metrics WHERE ? >= end_time AND end_time >= ? GROUP BY vin ) b 
  ON a.vin = b.vin AND a.end_time = b.end_time ORDER BY a.vin, a.end_time");
  $statement->bind_param('dd', $second, $second_sub_tolerance_time);
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






