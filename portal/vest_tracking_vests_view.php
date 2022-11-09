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
$sql_drivers_assigned_yellow = "select * from VestStatusView";
$drivers_assigned_res_yellow = $conn->query($sql_drivers_assigned_yellow);

        echo "<h2>Current VestStatusView</h2>";
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


?>

</center>
</body>
</html>
