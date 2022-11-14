<?php 

$VIN = '';
if(isset($_POST['submit'])) 
{

    $VIN = trim($_POST['cars']);
}
else
{
    header("Location: viz.php");
}

echo("VIN number select is: |" . $VIN. "|<br />");


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

$sql = "SELECT GpsTime,SysTime,Latitude,Longitude FROM GPSDB where Status='A' AND VIN='".$VIN."' ORDER BY GpsTime DESC LIMIT 60" ;

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
        if($count === 0)
        {
            $center_lat =$row['Latitude'];
            $center_long =$row['Longitude'];
        }

        $gpstime[$count] = floatval($row['GpsTime']);

        $coords[$count] = array();
        $coords[$count][0] = floatval($row['Longitude']);
        $coords[$count][1] = floatval($row['Latitude']);
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


<script>
	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
mapboxgl.accessToken = 'pk.eyJ1Ijoic3RyeW0iLCJhIjoiY2tydG4xamU1M2lyejJydGo0OWE4djcxbSJ9.BB-TcmFTyakTjI0J15jonQ';
var center_lat= <?php echo $center_lat; ?>;
var center_long= <?php echo $center_long; ?>;

var Coords= JSON.parse(<?php echo json_encode(json_encode($coords)); ?>);

var geojson = { 
    'type': 'FeatureCollection',
    'features': [
                    {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': {
                            'coordinates': JSON.parse(<?php echo json_encode(json_encode($coords)); ?>),
                            'type': 'LineString'
                            }
                    }
                ]
            };


var gpstime = JSON.parse(<?php echo json_encode(json_encode($gpstime)); ?>);

var G = {};
G["type"] = "FeatureCollection";
G["features"] = [];
G["features"][0] = {};
for (let i = 0; i < Coords.length; i++) {
  G["features"][i] = { "type": "Feature", "properties": { "gpstime": gpstime[i]}, "geometry": { "type": "Point", "coordinates": Coords[i] } };
}


var G_Start = {};
G_Start["type"] = "FeatureCollection";
G_Start["features"] = [];
G_Start["features"][0] = {};
G_Start["features"][0] = { "type": "Feature", "properties": { "gpstime": gpstime[Coords.length-1]}, "geometry": { "type": "Point", "coordinates": Coords[Coords.length-1] } };

var G_end = {};
G_end["type"] = "FeatureCollection";
G_end["features"] = [];
G_end["features"][0] = {};
G_end["features"][0] = { "type": "Feature", "properties": { "gpstime": gpstime[0]}, "geometry": { "type": "Point", "coordinates": Coords[0] } };


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

map.addLayer({
    id: 'end',
    type: 'circle',
    source: {
      type: 'geojson',
      data: G_end // replace this with the url of your own geojson
    },
    paint: {
       'circle-radius': 36,
     "circle-color": '#f72585',
    'circle-stroke-color': '#06d6a0',
    'circle-stroke-width': 4,
    'circle-opacity': 0.9
    }
  });
map.addLayer({
    id: 'start',
    type: 'circle',
    source: {
      type: 'geojson',
      data: G_Start // replace this with the url of your own geojson
    },
    paint: {
       'circle-radius': 8,
     "circle-color": '#ffd500',
    'circle-stroke-color': '#00171f',
    'circle-stroke-width': 4,
    'circle-opacity': 0.9
    }
  });



});

</script>

</body>
</html>
