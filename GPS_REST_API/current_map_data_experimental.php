
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$servername = "127.0.0.1";
$username = "circles";
$password = "wjytxeu5";
$db = "circledb_experiment";

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    echo $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}
$sql = "WITH vin_pings AS ( SELECT p.* FROM fact_vehicle_ping AS p
WHERE p.status = 0 AND (p.systime < 2147483647700) AND (p.systime - ?) BETWEEN 0 AND ?)
SELECT vin_pings.*, dim_vehicle.veh_id, dim_vehicle.route FROM vin_pings
join dim_vehicle on vin_pings.vin = dim_vehicle.vin ORDER BY systime";

$statement = $conn->prepare($sql);
$t_origin = (int)$_GET['t_origin'];
$t_range = (int)$_GET['t_range'];
$statement->bind_param('ii', $t_origin, $t_range);
$statement->execute();
$result = $statement->get_result();

if (!$result) {
    echo $conn->error;
    die("Query failed: " . $conn->error);
}

$center_long = 0.0;
$center_lat = 0.0;
$count = 0;

function create_sub_list() {
	$result = array();
	$gpstime = array();
	$coords = array();
	$carnumbers = array();
	$systime = array();
	$acc_speed_setting = array();
	$acc_status = array();
	$velocity = array();
	$route = array();
	$is_wb = array();

	$result['coords'] = $coords;
	$result['gpstime'] = $gpstime;
	$result['carnumbers'] = $carnumbers;
	$result['systime'] = $systime;
	$result['acc_speed_setting'] = $acc_speed_setting;
	$result['acc_status'] = $acc_status;
	$result['velocity'] = $velocity;
	$result['route'] = $route;
	$result['is_wb'] = $is_wb;
	return $result;
}

$final_result = array();
$final_result['time_entries'] = create_sub_list();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $center_lat = $center_lat + ($row['latitude'] / $result->num_rows);
    $center_long = $center_long + ($row['longitude'] / $result->num_rows);

    $final_result['time_entries']['gpstime'][$count] = floatval($row['gpstime']);
    $final_result['time_entries']['systime'][$count] = floatval($row['systime']);
    $final_result['time_entries']['acc_speed_setting'][$count] = floatval($row['acc_speed_setting']);
    $final_result['time_entries']['acc_status'][$count] = intval($row['acc_status']);
    $final_result['time_entries']['carnumbers'][$count] = intval($row['veh_id']);
    $final_result['time_entries']['velocity'][$count] = floatval($row['velocity']);
    $final_result['time_entries']['route'][$count] = $row['route'];
    $final_result['time_entries']['is_wb'][$count] = intval($row['is_wb']);

    $final_result['time_entries']['coords'][$count] = array();
    $final_result['time_entries']['coords'][$count][0] = floatval($row['longitude']);
    $final_result['time_entries']['coords'][$count][1] = floatval($row['latitude']);
    $final_result['time_entries']['coords'][$count][2] = (int)($row['veh_id']);
    $count = $count + 1;

  }
}

$final_result['center_lat'] = $center_lat;
$final_result['center_long'] = $center_long;
$output = json_encode($final_result);

echo $output;
?>
