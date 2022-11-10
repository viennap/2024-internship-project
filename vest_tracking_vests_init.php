<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<form action='' method='post'>
<h2>Initialize a vest</h2>
VestKey(*): <input type='text' name='VestKey'>
<input type='submit' value='Initialize a vest' name='initialVest' class="button">
<input type='submit' value='Delete a vest(only need to input VestKey)' name='deleteVest' class="button">
</form>
<?php
echo "<br>";
echo "<a href=http://ransom.isis.vanderbilt.edu/vest_tracking_home.php> go home </a>";
echo "<br>";
if(array_key_exists('initialVest', $_POST)) {
                initialVest();
        }
        else if(array_key_exists('deleteVest', $_POST)) {
                deleteVest();
        }


function initialVest() {
        echo "This is Initialize a vest that is selected";
        $VestKey = empty($_POST['VestKey'])?die("Please input the Firstname"):$_POST['VestKey'];
        //$TeamName = empty($_POST['TeamName'])?die("Please input the TeamName"):$_POST['TeamName'];
	$sqlinfo = require_once('/var/www/config.php');
	$conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
        $ini_v_sql =  "INSERT IGNORE INTO Vests (VestKey,  RouteKey, TeamName )
                        VALUES ($VeatKey, 1, 'CIRCLES Crew')";
        $conn->query($ini_v_sql);
        echo "<br>";
        echo "Vest initialization success!";
        echo "<br>";
        echo "<br>";
        echo "<h3>Show Initialized Vest Information</h3>";
        $sql_ini_v_res = "select * from Vests order by Modified DESC limit 1";
        $ini_v_res = $conn->query($sql_ini_v_res);
        echo "<table border=1>";
        echo "<tr><td>VestKey</td><td>DriverKey</td><td>RouteKey</td><td>TeamName</td><td>Modified</td></tr>";
        echo"<tr>";
        if ($ini_v_res->num_rows > 0) {
                while($row = $ini_v_res->fetch_assoc()) {
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

function deleteVest() {
        echo "This is Delete a vest that is selected";
        $VestKey = empty($_POST['VestKey'])?die("Please input the VestKey"):$_POST['VestKey'];
        $conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');
        $delete_v_sql =  "DELETE from Vests where VestKey = $VestKey";
        $conn->query($delete_v_sql);
        echo "<br>";
        echo "Delete vest success!";
}


?>

</center>
</body>
</html>
