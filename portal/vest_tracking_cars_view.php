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
$ini= parse_ini_file("mysql_link.ini");
$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
$sql_assign_car_res_yellow = "select * from CarStatusView";
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
