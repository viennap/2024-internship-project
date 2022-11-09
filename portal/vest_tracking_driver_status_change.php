<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Change driver status</h2>
VestKey(*): <input type='text' name='VestKey'>
<input type='submit' value='Check current VestStatusView' name='checkVestStatusView' class="button">
<input type='submit' value='Get driver ready' name='driverReady' class="button">
<input type='submit' value='Get driver downstairs' name='driverDownstairs' class="button">
</form>


<?php
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('checkVestStatusView', $_POST)) {
        	checkVestStatusView();
}
	else if(array_key_exists('driverReady', $_POST)) {
                driverReady();
        }
        else if(array_key_exists('driverDownstairs', $_POST)) {
		driverDownstairs();
	}

function checkVestStatusView() {
	echo "This is check current VestStatusView that is selected";
	$ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
	echo "<h3>Show VestStatusView</h3>";
        $sql_check_vest_view_res = "select * from VestStatusView order by TeamName,VestStatusKey";
        $check_vest_view_res = $conn->query($sql_check_vest_view_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($check_vest_view_res->num_rows > 0) {
                while($row = $check_vest_view_res->fetch_assoc()) {
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
}

function driverReady() {
	echo "This is get driver ready that is selected";
	$VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
	$ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
	$sql_ready = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Be Ready') )";
	$conn->query($sql_ready);
	echo "<br>";
        echo "Driver gets ready!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_ready_res = "select * from VestStatusView where VestKey = $VestKey";
        $ready_res = $conn->query($sql_ready_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($ready_res->num_rows > 0) {
                while($row = $ready_res->fetch_assoc()) {
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

}

function driverDownstairs() {
        echo "This is get driver downstairs that is selected";
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $sql_downstairs = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Downstairs') )";
        $conn->query($sql_downstairs);
        echo "<br>";
        echo "Driver goes downstairs!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_downstairs_res = "select * from VestStatusView where VestKey = $VestKey";
        $downstairs_res = $conn->query($sql_downstairs_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($downstairs_res->num_rows > 0) {
                while($row = $downstairs_res->fetch_assoc()) {
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

}


?>

</center>
</body>
</html>
