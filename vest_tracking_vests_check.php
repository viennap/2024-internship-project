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
echo "<a href=./home.html> go home </a>";
	$sqlinfo = require_once('/var/www/config.php');
        $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
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


?>

</center>
</body>
</html>
