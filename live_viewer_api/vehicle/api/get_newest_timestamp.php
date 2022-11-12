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
  if (array_key_exists('timestamp', $_GET)) {
    $statement = $conn->prepare('SELECT MAX(observed_at) FROM fact_vehicle_observation WHERE observed_at <= ?');
    $limit_timestamp = (double)$_GET['timestamp'];
    $statement->bind_param('d', $limit_timestamp);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $result_array['timestamp'] = (double)$row['MAX(observed_at)'];
    }
  }
  else {
    $statement = "SELECT MAX(observed_at) FROM fact_vehicle_observation";
    $result = $conn->query($statement);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $result_array['timestamp'] = (double)$row['MAX(observed_at)'];
      }
  }
  return $result_array;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>






