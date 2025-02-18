
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb";
$cacheKey = 'liveViewerCache';
$cacheTimeout = 15;

include('./current_map_experimental/vendor/autoload.php');
use Phpfastcache\Helper\Psr16Adapter;
$defaultDriver = 'Files';
$Psr16Adapter = new Psr16Adapter($defaultDriver);
$output = "";
if ($Psr16Adapter->has($cacheKey)) {
    $output = $Psr16Adapter->get($cacheKey);
}
else {
    $conn = new mysqli($servername, $username, $password, $db);
    if ($conn->connect_error)
    {
        echo $conn->connect_error;
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "WITH vin_pings AS ( SELECT p.*, ROW_NUMBER() OVER (PARTITION BY vin ORDER BY systime DESC) AS rn
    FROM fact_vehicle_ping AS p WHERE p.status = 0 AND (p.gpstime < 2147483647700)
    AND ABS((UNIX_TIMESTAMP() * 1000) - p.systime) < (1000*60*60*8))
    SELECT vin_pings.* FROM vin_pings WHERE rn=1";

    $result = $conn->query($sql) ;
    if (!$result) {
    echo $conn->error;
    die("Query failed: " . $conn->error);
    }
    $count = 0;

    $center_long = 0.0;
    $center_lat = 0.0;

		$vin = array();
    $gpstime = array();

    $coords = array();

    $carnumbers = array();

    $systime = array();

    $acc_speed_setting = array();
    $acc_status = array();

    $velocity = array();

    $route = array();

    $is_wb = array();

    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $center_lat = $center_lat + ($row['latitude'] / $result->num_rows);
            $center_long = $center_long + ($row['longitude'] / $result->num_rows);

						$vin[$count] = $row['vin'];
            $gpstime[$count] = floatval($row['gpstime']);
            $systime[$count] = floatval($row['systime']);
            $acc_speed_setting[$count] = floatval($row['acc_speed_setting']);
            $acc_status[$count] = intval($row['acc_status']);
            $carnumbers[$count] = 0;
            $velocity[$count] = floatval($row['velocity']);
            $route[$count] = "Westbound";
            $is_wb[$count] = $row['is_wb'];

            $coords[$count] = array();
            $coords[$count][0] = floatval($row['longitude']);
            $coords[$count][1] = floatval($row['latitude']);
            $coords[$count][2] = 0;
            $count = $count + 1;

        }
    }

    $result = array();
		$result['vin'] = $vin;
    $result['coords'] = $coords;
    $result['gpstime'] = $gpstime;
    $result['carnumbers'] = $carnumbers;
    $result['systime'] = $systime;
    $result['acc_speed_setting'] = $acc_speed_setting;
    $result['acc_status'] = $acc_status;
    $result['velocity'] = $velocity;
    $result['route'] = $route;
    $result['is_wb'] = $is_wb;
    $result['center_lat'] = $center_lat;
    $result['center_long'] = $center_long;
    $output = json_encode($result);
    $Psr16Adapter->set($cacheKey, $output, $cacheTimeout);
}

echo $output;
?>
