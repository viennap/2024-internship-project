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
  $second_sub_history_time = $second - $history_time;
  $statement = $conn->prepare('SELECT queried_at AS timestamp, segment_id, speed FROM fact_inrix_estimate WHERE ? >= queried_at AND queried_at >= ?');
  $statement->bind_param('dd', $second, $second_sub_history_time);
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






