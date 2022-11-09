<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Assign/Unassign driver and route</h2>
DriverKey(*): <input type='text' name='DriverKeyR'>
RouteKey(*): <input type='text' name='RouteKey'>
<input type='submit' value='Check current drivers route training' name='checkTraining' class="button">
<input type='submit' value='Assign driver to a trained route' name='assignRouteDriver' class="button">
<input type='submit' value='Unassign driver from route' name='unassignRouteDriver' class="button">
</form>


<?php
echo "<br>";
echo "<a href=./vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('checkTraining', $_POST)) {
                checkTraining();
        }
        else if(array_key_exists('assignRouteDriver', $_POST)) {
		assignRouteDriver();
	}
	else if(array_key_exists('unassignRouteDriver', $_POST)) {
                unassignRouteDriver();
	}

function checkTraining() {
	echo "This is check current drivers route training that is selected";
	$ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
	echo "<h3>Show Current Drivers Route Training</h3>";
        $sql_check_training_res = "select * from Training order by RouteKey,Modified,DriverKey";
        $check_training_res = $conn->query($sql_check_training_res);

        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>RouteKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($check_training_res->num_rows > 0) {
                while($row = $check_training_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";
                }

        }
        echo "</table>";
}


function assignRouteDriver() {
        echo "This is assign driver to a trained route that is selected";
        $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
        $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $a_rd_sql = "INSERT IGNORE INTO Training (DriverKey, RouteKey) VALUES ($DriverKeyR,$RouteKey)";
	$conn->query($a_rd_sql);
	echo "<br>";
        echo "Assign driver to a trained route success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Driver Route Training</h3>";
        $sql_assign_rd_res = "select * from Training where DriverKey = $DriverKeyR";
        $assign_rd_res = $conn->query($sql_assign_rd_res);

        echo "<table border=1>";
        echo "<tr><td>DriverKey</td><td>RouteKey</td><td>Modified</td></tr>";
        echo"<tr>";

        if ($assign_rd_res->num_rows > 0) {
                while($row = $assign_rd_res->fetch_assoc()) {
                        echo"<tr>";
                        echo"<td>".$row[DriverKey]."</td>";
                        echo"<td>".$row[RouteKey]." </td>";
                        echo"<td>".$row[Modified]." </td>";
                        echo"</tr>";
                }

        }
        echo "</table>";
}


function unassignRouteDriver() {
        echo "This is unassign driver from route that is selected";
        $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
        $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
        $ini= parse_ini_file("mysql_link.ini");
	$conn =new mysqli($ini["servername"],$ini["username"],$ini["password"],$ini["dbname"]) or die("DB connection failed.<br/>");
        $una_rd_sql = "DELETE from Training where DriverKey = $DriverKeyR and RouteKey = $RouteKey ";
        $conn->query($una_rd_sql);
	echo "<br>";
        echo "Unassign driver from route success!";
}

?>

</center>
</body>
</html>
