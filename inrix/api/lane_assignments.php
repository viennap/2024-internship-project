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
  $sql = "SELECT lane_num FROM dim_vehicle WHERE vin = '" . urldecode($_GET["vin"]) . "' LIMIT 1";
  $result = $conn->query($sql);
  return $result->fetch_row()[0] ?? false;
}

header("Content-Type: application/json");
echo json_encode(get_all_data());
$conn->close();
?>






