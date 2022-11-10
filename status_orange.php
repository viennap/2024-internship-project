<html>

<head>

<title>Vest Tracking Test</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="assets/css/main.css" />
<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

<style>
* { box-sizing: border-box; }
.column { float: left; width: 50%; padding: 10px; }
.row:after { content: ""; display: table; clear: both; }
</style>

</head>


<body>
<center>

<div class="row">
<h1> Driver assignment status </h1>
</div>

<div class="row">

<div class="column">
<?php
header("refresh: 60;");
$timestamp=time();
echo "Last refresh:  ";
echo date("Y-m-d H:i:s", $timestamp);
?>
</div>

<div class="column">
<a href="http://ransom.isis.vanderbilt.edu/home.html#status" class=button> Go home </a>
</div>

</div>


<div class="row">

<div class="column" style="background-color:#1b7d35;">

<?php
	$conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');

	$sql_drivers_get_ready_orange = "select * from VestStatusView where VestStatusKey=3 and RouteKey=1 order by Modified";
	$drivers_get_ready_res_orange = $conn->query($sql_drivers_get_ready_orange);

	echo "<h2>Get ready to drive</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverFirstName</td><td>DriverLastName</td></tr>";
        echo "<tr>";

        if ($drivers_get_ready_res_orange->num_rows > 0) {
                while($row = $drivers_get_ready_res_orange->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"</tr>";
                }
        }
	echo "</table>";

?>

</div>

<div class="column" style="background-color:#b8a818; color:#000000;">

<?php
	$sql_drivers_go_downstairs_orange = "select * from VestStatusView where VestStatusKey=4 and RouteKey=1 order by Modified";
	$drivers_go_downstairs_res_orange = $conn->query($sql_drivers_go_downstairs_orange);

	echo "<h2 style='color: #000000;'>Drivers Go to lobby</h2>";
	echo "<table border=1 style='color: #000000;'>";
	echo "<tr><td>VestKey</td><td>DriverFirstName</td><td>DriverLastName</td></tr>";
	echo "<tr>";

	if ($drivers_go_downstairs_res_orange->num_rows > 0) {
    		while($row = $drivers_go_downstairs_res_orange->fetch_assoc()) {
			echo"<tr>";
			echo"<td>".$row[VestKey]." </td>";
			echo"<td>".$row[DriverFirstName]." </td>";
			echo"<td>".$row[DriverLastName]." </td>";
			echo"</tr>";
		}
	}
	echo "</table>";
?>
</div>

</div>


<div class="row">

<div class="column" style="background-color: #960f0f;">
<?php
	$sql_drivers_go_upstairs_orange = "select * from VestStatusView where VestStatusKey=10 and RouteKey=1 order by Modified";
        $drivers_go_upstairs_res_orange = $conn->query($sql_drivers_go_upstairs_orange);

        echo "<h2>Finished driving</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverFirstName</td><td>DriverLastName</td></tr>";
        echo"<tr>";

        if ($drivers_go_upstairs_res_orange->num_rows > 0) {
                while($row = $drivers_go_upstairs_res_orange->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"</tr>";


                }
        }
	echo "</table>";
?>
</div>

<div class="column" style="background-color:#25a8cc; color: #000000;">
<?php
	$sql_drivers_assigned_orange = "select * from VestStatusView where VestStatusKey=1 and RouteKey=1 order by Modified";
        $drivers_assigned_res_orange = $conn->query($sql_drivers_assigned_orange);

        echo "<h2 style='color: #000000;'>Drivers on break</h2>";
        echo "<table border=1 style='color: #000000;'>";
        echo "<tr><td>VestKey</td><td>DriverFirstName</td><td>DriverLastName</td></tr>";
        echo "<tr>";

        if ($drivers_assigned_res_orange->num_rows > 0) {
                while($row = $drivers_assigned_res_orange->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"</tr>";
                }
        }
        echo "</table>";
?>
</div>

</div>


</center>
</body>
</html>
