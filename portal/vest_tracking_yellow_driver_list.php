<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<?php
header("refresh: 60;");
$timestamp=time();
echo date("Y-m-d H:i:s", $timestamp);
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<h1>Vest and Vehicle quick assignment status (readonly)</h1>";

	$ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
	
	$sql_drivers_get_ready_yellow = "select * from VestStatusView where VestStatusKey=3 and RouteKey=2 order by Modified";
	$drivers_get_ready_res_yellow = $conn->query($sql_drivers_get_ready_yellow);

	echo "<h2>Drivers Get Ready Reminder</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_get_ready_res_yellow->num_rows > 0) {
                while($row = $drivers_get_ready_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
	echo "</table>";

	
	$sql_drivers_go_downstairs_yellow = "select * from VestStatusView where VestStatusKey=4 and RouteKey=2 order by Modified";
	$drivers_go_downstairs_res_yellow = $conn->query($sql_drivers_go_downstairs_yellow);

	echo "<h2>Drivers Go Downstairs Reminder</h2>";
	echo "<table border=1>";
	echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
	echo"<tr>";

	if ($drivers_go_downstairs_res_yellow->num_rows > 0) {
    		while($row = $drivers_go_downstairs_res_yellow->fetch_assoc()) {
			echo"<tr>";
			echo"<td>".$row[VestKey]."</td>";
			echo"<td>".$row[RouteColor]." </td>";
			echo"<td>".$row[TeamName]." </td>";
			echo"<td>".$row[DriverFirstName]." </td>";
			echo"<td>".$row[DriverLastName]." </td>";
			echo"<td>".$row[VestStatusString]."</td>";
			echo"<td>".$row[RouteKey]." </td>";
			echo"<td>".$row[DriverKey]." </td>";
			echo"<td>".$row[VestStatusKey]." </td>";
			echo"<td>".$row[Modified]." </td>";
			echo"</tr>";

		
		}
	}
	echo "</table>";

	
	$sql_drivers_go_upstairs_yellow = "select * from VestStatusView where VestStatusKey=10 and RouteKey=2 order by Modified";
        $drivers_go_upstairs_res_yellow = $conn->query($sql_drivers_go_upstairs_yellow);

        echo "<h2>Drivers Finished Driving and Go Upstairs Reminder</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_go_upstairs_res_yellow->num_rows > 0) {
                while($row = $drivers_go_upstairs_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
	echo "</table>";

		
	$sql_drivers_assigned_yellow = "select * from VestStatusView where VestStatusKey=1 and RouteKey=2 order by Modified";
        $drivers_assigned_res_yellow = $conn->query($sql_drivers_assigned_yellow);

        echo "<h2>Drivers Assigned and Stay Upstairs freely</h2>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($drivers_assigned_res_yellow->num_rows > 0) {
                while($row = $drivers_assigned_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[VestKey]."</td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[DriverFirstName]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[VestStatusString]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[VestStatusKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                } 
        }
        echo "</table>";


        $sql_assign_car_res_yellow = "select * from CarStatusView where RouteKey =2 order by CarStatusKey,Modified";
        $assign_car_res_yellow = $conn->query($sql_assign_car_res_yellow);

	echo "<h2>Cars that are Currently Assigned</h2>";
        echo "<table border=1>";
        echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($assign_car_res_yellow->num_rows > 0) {
                while($row = $assign_car_res_yellow->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[CarKey]."</td>";
                        echo"<td>".$row[CarStatusString]." </td>";
                        echo"<td>".$row[VestKey]." </td>";
                        echo"<td>".$row[RouteColor]." </td>";
                        echo"<td>".$row[DriverLastName]." </td>";
                        echo"<td>".$row[CarStatusKey]."</td>";
                        echo"<td>".$row[IsActive]." </td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[DriverKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";

     
                }
        }
	echo "</table>";


?>

</center>
</body>
</html>
