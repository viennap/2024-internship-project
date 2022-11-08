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
// echo "Connected successfully\n";

$vin = (string)$_GET['vin'];
// $vin = "$vin";

$gpstime = $_GET['gpstime'];
$systime = $_GET['systime'];

$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

$status = (string)$_GET['status'];
// $status = "$status";

$position = $_GET['position'];
$velocity = $_GET['velocity'];
$acceleration = $_GET['acceleration'];

$relative_leadervel = $_GET['relative_leadervel'];
$relative_distance = $_GET['relative_distance'];

$left_relvel = $_GET['left_relvel'];
$left_yaw = $_GET['left_yaw'];

$right_relvel = $_GET['right_relvel'];
$right_yaw = $_GET['right_yaw'];

$acc_speed_setting = $_GET['acc_speed_setting'];
$acc_status = $_GET['acc_status'];

$is_wb = $_GET['is_wb'];

//echo $status;
$sql = "INSERT INTO fact_vehicle_ping (vin,gpstime,systime,latitude,longitude,status,position,velocity,acceleration,relative_leadervel,relative_distance,left_relvel,left_yaw,right_relvel,right_yaw,acc_speed_setting,acc_status,is_wb)
VALUES ('$vin',$gpstime,$systime,$latitude,$longitude,'$status',$position,$velocity,$acceleration,$relative_leadervel,$relative_distance,$left_relvel,$left_yaw,$right_relvel,$right_yaw,$acc_speed_setting,$acc_status,$is_wb)";


if (mysqli_query($conn, $sql)) {
    echo "Insert successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


$conn->close();
?>






