<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Assign/Unassign driver and vest</h2>
DriverKey(*): <input type='text' name='DriverKeyV'>
VestKey(*): <input type='text' name='VestKey'>
<input type='submit' value='Check current available vests' name='checkVests' class="button">
<input type='submit' value='Assign a vest to a driver' name='assignVestDriver' class="button">
<input type='submit' value='Unassign vest from driver' name='unassignVestDriver' class="button">
</form>

<?php
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('checkVests', $_POST)) {
                checkVests();
	}
	else if(array_key_exists('assignVestDriver', $_POST)) {
		assignVestDriver();
	}
	else if(array_key_exists('unassignVestDriver', $_POST)) {
		unassignVestDriver();
	}

function checkVests() {
        echo "This is Check current availabe Vests that is selected";
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $check_v_sql ="select * from Vests where DriverKey is NULL";
        $check_v_res = $conn->query($check_v_sql);
        echo "<br>";
        echo "<h3>Show current available Vests</h3>";
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverKey</td><td>RouteKey</td><td>TeamName</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($check_v_res->num_rows > 0) {
                while($row = $check_v_res->fetch_assoc()) {
			echo"<tr>";
			echo"<td>".$row[VestKey]."</td>";
			echo"<td>".$row[DriverKey]."</td>";
			echo"<td>".$row[RouteKey]."</td>";
                        echo"<td>".$row[TeamName]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";


                }
        }
        echo "</table>";
}

function assignVestDriver() {
	echo "This is assign a vest to a driver that is selected";
	$DriverKeyV = empty($_POST['DriverKeyV'])?die("Please input the DriverKey"):$_POST['DriverKeyV'];
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $a_vd_sql_1 = "UPDATE Drivers SET VestKey = $VestKey WHERE DriverKey = $DriverKeyV";
	$conn->query($a_vd_sql_1);
	$a_vd_sql_2 = "UPDATE Vests SET DriverKey = $DriverKeyV,RouteKey = (SELECT RouteKey from Training WHERE DriverKey = $DriverKeyV) WHERE VestKey = $VestKey";
        $conn->query($a_vd_sql_2);
	$a_vd_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)VALUES ($VestKey, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'VestAssigned') )";
	$conn->query($a_vd_sql_3);
	echo "<br>";
        echo "Assign a vest to a driver success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
	$sql_assign_vd_res = "select * from VestStatusView where VestKey = $VestKey";
	$assign_vd_res = $conn->query($sql_assign_vd_res);
	echo "<table border=1>";
	echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
	echo"<tr>";

	if ($assign_vd_res->num_rows > 0) {
    		while($row = $assign_vd_res->fetch_assoc()) {
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

function unassignVestDriver() {
	echo "This is unassign vest from driver that is selected";
	$DriverKeyV = empty($_POST['DriverKeyV'])?die("Please input the DriverKey"):$_POST['DriverKeyV'];
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $una_vd_sql_1 = "UPDATE Drivers SET VestKey = NULL WHERE DriverKey = $DriverKeyV";
	$conn->query($una_vd_sql_1);
	$una_vd_sql_2 = "UPDATE Vests SET DriverKey = NULL,RouteKey = 1 WHERE VestKey = $VestKey";
        $conn->query($una_vd_sql_2);
        echo "<br>";
	echo "Unassign vest from driver success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show VestStatusView</h3>";
        $sql_unassign_vd_res = "select * from VestStatusView where VestKey = $VestKey";
        $unassign_vd_res = $conn->query($sql_unassign_vd_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($unassign_vd_res->num_rows > 0) {
                while($row = $unassign_vd_res->fetch_assoc()) {
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
