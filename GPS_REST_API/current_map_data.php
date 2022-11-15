
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error)
{
    echo $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}

#$sql = "SELECT gpstime as GpsTime, systime as SysTime,latitude as Latitude,longitude as Longitude FROM fact_vehicle_ping 
#where Status='A' AND VIN='".$VIN."' ORDER BY GpsTime DESC LIMIT 60" ;
$sql = "WITH vin_pings AS ( SELECT p.*, ROW_NUMBER() OVER (PARTITION BY vin ORDER BY systime DESC) AS rn 
FROM fact_vehicle_ping AS p WHERE p.status = 0 AND ABS((UNIX_TIMESTAMP() * 1000) - p.systime) < (1000*60*60*2)) SELECT vin_pings.*, dim_vehicle.veh_id FROM vin_pings 
join dim_vehicle on vin_pings.vin = dim_vehicle.vin WHERE rn=1";

$result = $conn->query($sql) ;
if (!$result) {
  echo $conn->error;
  die("Query failed: " . $conn->error);
}
$count = 0;

$center_long = 0.0;
$center_lat = 0.0;

$gpstime = array();

$coords = array();

$carnumbers = array();

if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $center_lat = $center_lat + ($row['latitude'] / $result->num_rows);
        $center_long = $center_long + ($row['longitude'] / $result->num_rows);

        $gpstime[$count] = floatval($row['gpstime']);
        $carnumbers[$count] = intval($row['veh_id']);

        $coords[$count] = array();
        $coords[$count][0] = floatval($row['longitude']);
        $coords[$count][1] = floatval($row['latitude']);
        $coords[$count][2] = (int)($row['veh_id']);
        $count = $count + 1;

    }
}

$result = array();
$result['coords'] = $coords;
$result['gpstime'] = $gpstime;
$result['carnumbers'] = $carnumbers;
$result['center_lat'] = $center_lat;
$result['center_long'] = $center_long;

echo json_encode($result);

?>