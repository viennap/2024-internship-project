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

  // get upper bound timestamp (in milliseconds since epoch), or use NOW
  if(isset($_GET['second'])) {
    $second = (double)$_GET['second'];
  }
  else {
    $second = time() * 1000;    // convert UNIX seconds to milliseconds
  }

  // get look back amount of time (in milliseconds)
  if(isset($_GET['history_time'])) {
    $history_time = (double)$_GET['history_time'];
  }
  else {
    $history_time = 900000;    // 15 minutes
  }

  // create absolute lower bound timestamp (in milliseconds since epoch)
  $second_sub_history_time = $second - $history_time;

  // get lane number if given, otherwise use all lanes
  if(isset($_GET['lane_num'])) {
    $lane_num = (int)$_GET['lane_num'];
    $all_lanes = False;
  }
  else {
    $lane_num = NULL;
    $all_lanes = True;
  }

  // see if the query is for only the most recent data point or for all within the time range
  if(isset($_GET['only_recent'])) {
    $only_recent = (bool)((int)$_GET['only_recent']);
  }
  else {
    $only_recent = True;
  }

  // build the query
  $query = "";
  if ($only_recent){
    // get only one data point from every vehicle
    if ($all_lanes == False && !is_null($lane_num)) {
      //
      $query = "
      WITH max_obs AS ( SELECT vin, max(observed_at) AS max_obs_at FROM fact_vehicle_observation
        WHERE lane_num = ? AND observed_at BETWEEN ? AND ? GROUP BY vin )
      SELECT max_obs.max_obs_at AS observed_at, vo.position, vo.speed, vo.lane_num, vo.vin, vo.source,
        vp.acc_status, vp.is_wb, vp.latitude, vp.longitude
      FROM max_obs
      JOIN fact_vehicle_observation AS vo ON max_obs.vin = vo.vin AND max_obs.max_obs_at = vo.observed_at
      JOIN fact_vehicle_ping AS vp ON max_obs.max_obs_at = vp.gpstime AND max_obs.vin = vp.vin";
    }
    else {
      $query = "
      WITH max_obs AS ( SELECT vin, max(observed_at) AS max_obs_at FROM fact_vehicle_observation
        WHERE observed_at BETWEEN ? AND ? GROUP BY vin )
      SELECT max_obs.max_obs_at AS observed_at, vo.position, vo.speed, vo.lane_num, vo.vin, vo.source,
        vp.acc_status, vp.is_wb, vp.latitude, vp.longitude
      FROM max_obs
      JOIN fact_vehicle_observation AS vo ON max_obs.vin = vo.vin AND max_obs.max_obs_at = vo.observed_at
      JOIN fact_vehicle_ping AS vp ON max_obs.max_obs_at = vp.gpstime AND max_obs.vin = vp.vin";
    }
  }
  else {
    // get all data points from every vehicle
    if ($all_lanes == False && !is_null($lane_num)) {
        $query = "
        SELECT observed_at, position, speed, lane_num, vin, source
        FROM fact_vehicle_observation
        WHERE lane_num = ? AND observed_at BETWEEN ? AND ?
        ORDER BY vin DESC, position DESC, observed_at DESC";
    }
    else {
        $query = "
        SELECT observed_at, position, speed, lane_num, vin, source
        FROM fact_vehicle_observation
        WHERE observed_at BETWEEN ? AND ?
        ORDER BY vin DESC, position DESC, observed_at DESC";
    }
  }
  $statement = $conn->prepare(addslashes($query));
  echo $query;
  echo "<br>";
  echo $second_sub_history_time;
  echo "<br>";
  echo $second;
  echo "<br>";
  echo $all_lanes;
  echo "<br>";
  echo $lane_num;
  echo "<br>";

  // populate parameters (only need $lane_num if calling for specific one)
  if ($all_lanes == False && !is_null($lane_num)) {
    $statement->bind_param('idd', $lane_num, $second_sub_history_time, $second);
  }
  else {
    $statement->bind_param('dd', $second_sub_history_time, $second);
  }

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






