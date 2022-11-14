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
$sql = "WITH vin_pings AS ( SELECT p.*, ROW_NUMBER() OVER (PARTITION BY vin ORDER BY gpstime DESC) AS rn 
FROM fact_vehicle_ping AS p ) SELECT vin_pings.*, dim_vehicle.veh_id FROM vin_pings join dim_vehicle on vin_pings.vin = dim_vehicle.vin WHERE rn=1";

$result = $conn->query($sql) ;

$count = 0;

$center_long = 0.0;
$center_lat = 0.0;

$gpstime = array();

$coords = array();

if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $center_lat = $center_lat + ($row['latitude'] / $result->num_rows);
        $center_long = $center_long + ($row['longitude'] / $result->num_rows);

        $gpstime[$count] = floatval($row['gpstime']);

        $coords[$count] = array();
        $coords[$count][0] = floatval($row['longitude']);
        $coords[$count][1] = floatval($row['latitude']);
        $coords[$count][2] = (int)($row['veh_id']);
        $count = $count + 1;

    }
}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Circles Experiment</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

<style>
body { margin: 0; padding: 0; }
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<div id="map"></div>
<p><?php echo json_encode($coords); ?></p>

<script>
	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
mapboxgl.accessToken = 'pk.eyJ1Ijoic3RyeW0iLCJhIjoiY2tydG4xamU1M2lyejJydGo0OWE4djcxbSJ9.BB-TcmFTyakTjI0J15jonQ';
var center_lat= <?php echo $center_lat; ?>;
var center_long= <?php echo $center_long; ?>;

var Coords= JSON.parse(<?php echo json_encode(json_encode($coords)); ?>);

var gpstime = JSON.parse(<?php echo json_encode(json_encode($gpstime)); ?>);

var G = {};
G["type"] = "FeatureCollection";
G["features"] = [];
G["features"][0] = {};
for (let i = 0; i < Coords.length; i++) {
  G["features"][i] = { "type": "Feature", "properties": { "gpstime": gpstime[i]}, "geometry": { "type": "Point", "coordinates": Coords[i] } };
}

var map = new mapboxgl.Map({
container: 'map',
style: 'mapbox://styles/mapbox/light-v10',
center: [<?php echo $center_long; ?>,  <?php echo $center_lat; ?>],
zoom: 16
});

map.on('load', function() {
  map.addLayer({
    id: 'datapoints',
    type: 'circle',
    source: {
      type: 'geojson',
      data: G // replace this with the url of your own geojson
    },
    paint: {
       'circle-radius': 14,
     "circle-color": "#4361ee",
    'circle-stroke-color': 'white',
    'circle-stroke-width': 1,
    'circle-opacity': 0.5
    }
  });
});

</script>

</body>
</html>
