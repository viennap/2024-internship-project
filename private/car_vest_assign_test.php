<html>
    <head>

        <title>Car to vest assignment</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
        <noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>

        <style>
            * { box-sizing: border-box; }
            .column { float: left; width: 25%; padding: 10px; }
            .row:after { content: ""; display: table; clear: both; }
            @media screen and (max-width: 600px) { .column { width: 100%; } }
        </style>

    </head>

    <body>
    <center>
        
        <div class="row">
            <h1> Car to vest assignment </h1>
        </div>

        <div class="row">
                <a href="http://ransom.isis.vanderbilt.edu/home.html#vehicles" class=button> Go home </a>
        </div>

        <form action='' method='post'>
            <div class="row">
                <div class="column" style="width: 15%"><br></div>
                <div class="column" style="width: 35%">
                    VestKey(*): <input type='text' name='VestKeyC'>
                </div>
                <div class="column" style="width: 35%">
                    CarKey(*): <input type='text' name='CarKey'>
                </div>
                <div class="column" style="width: 15%"><br></div>
            </div>
            <div class="row">
                <div class="column" style="width: 12.5%"><br></div>
                <div class="column">
                    <input type='submit' value='Check current CarStatusView' name='checkCarStatusView' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Assign vest to car' name='assignVestCar' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Unassign vest from car' name='unassignVestCar' class="button">
                </div>
                <div class="column" style="width: 12.5%"><br></div>
	    </div>
	    <div class="row">
                <div class="column" style="width: 15%"><br></div>
                <div class="column" style="width: 35%">
                    <input type='submit' value='Request an orange driver' name='requestOrange' class="button">
                </div>
                <div class="column" style="width: 35%">
                    <input type='submit' value='Request a yellow driver' name='requestYellow' class="button">
                </div>
                <div class="column" style="width: 15%"><br></div>
            </div>
        </form>

        
        <?php
            echo "<br>";
            if(array_key_exists('checkCarStatusView', $_POST)) {
                checkCarStatusView();
            }
            else if(array_key_exists('assignVestCar', $_POST)) {
                assignVestCar();
            }
            else if(array_key_exists('unassignVestCar', $_POST)) {
                unassignVestCar();
	    }
	    else if(array_key_exists('requestOrange', $_POST)) {
                requestOrange();
            }
            else if(array_key_exists('requestYellow', $_POST)) {
                requestYellow();
            }

            function checkCarStatusView() {
                echo "This is check current CarStatusView that is selected";
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                echo "<h3>Show CarStatusView</h3>";
                $sql_check_car_view_res = "select * from CarStatusView";
                $check_car_view_res = $conn->query($sql_check_car_view_res);
                echo "<table border=1>";
                echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($check_car_view_res->num_rows > 0) {
                        while($row = $check_car_view_res->fetch_assoc()) {
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
                } }
                echo "</table>";
            }

            function assignVestCar() {
                echo "This is assign a vest to a car that is selected";
                $VestKeyC = empty($_POST['VestKeyC'])?die("Please input the VestKey"):$_POST['VestKeyC'];
                    $CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $a_sql_1 = "UPDATE Cars as c
                    SET c.VestKey = $VestKeyC
                    WHERE c.CarKey = $CarKey";
                $conn->query($a_sql_1);
                $a_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
                    VALUES ($CarKey, $VestKeyC, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'Driving') )";
                $conn->query($a_sql_2);
                $a_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                    VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Driving') )";
                $conn->query($a_sql_3);
                echo "<br>";
                echo "assign a vest to a car success!";
                echo "<br>";
                echo "<br>";
                echo "<h3>Show VestStatusView</h3>";
                $sql_assign_res = "select * from VestStatusView where VestKey = $VestKeyC";
                $assign_res = $conn->query($sql_assign_res);
                echo "<table border=1>";
                echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($assign_res->num_rows > 0) {
                        while($row = $assign_res->fetch_assoc()) {
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
                } }
                echo "</table>";
                
                echo "<h3>Show CarStatusView</h3>";
                $sql_assign_car_res = "select * from CarStatusView where CarKey = $CarKey";
                $assign_car_res = $conn->query($sql_assign_car_res);
                echo "<table border=1>";
                echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($assign_car_res->num_rows > 0) {
                        while($row = $assign_car_res->fetch_assoc()) {
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
                } }
                echo "</table>";
            }

            function unassignVestCar() {
                echo "This is unassign a vest from a car that is selected";
                $VestKeyC = empty($_POST['VestKeyC'])?die("Please input the VestKey"):$_POST['VestKeyC'];
                $CarKey = empty($_POST['CarKey'])?die("Please input the CarKey"):$_POST['CarKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $una_sql_1 = "UPDATE Cars as c
                    SET c.VestKey = NULL
                    WHERE c.CarKey = $CarKey
                    AND c.VestKey = $VestKeyC";
                $conn->query($una_sql_1);
                $una_sql_2 = "INSERT INTO CarStatus (CarKey, VestKey, CarStatusKey)
                    VALUES ($CarKey, NULL, ( select CarStatusTypes.CarStatusKey from CarStatusTypes where CarStatusTypes.CarStatusShort like 'At Fence') )";
                $conn->query($una_sql_2);
                //$una_sql_3 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                //	VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Done Driving') )";
                //$conn->query($una_sql_3);
                $una_sql_4 = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ($VestKeyC, ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Go Inside') )";
                    $conn->query($una_sql_4);

                echo "<br>";
                echo "unassign a vest from a car success!";
                echo "<br>";
                echo "<br>";
                echo "<h3>Show VestStatusView</h3>";
                $sql_unassign_res = "select * from VestStatusView where VestKey = $VestKeyC";
                $unassign_res = $conn->query($sql_unassign_res);

                echo "<table border=1>";
                echo "<tr><td>VestKey</td><td>RouteColor</td><td>TeamName</td><td>DriverFirstName</td><td>DriverLastName</td><td>VestStatusString</td><td>RouteKey</td><td>DriverKey</td><td>VestStatusKey</td><td>Modified</td></tr>";
                echo"<tr>";

                if ($unassign_res->num_rows > 0) {
                    while($row = $unassign_res->fetch_assoc()) {
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
                } }
                echo "</table>";
                
                echo "<h3>Show CarStatusView</h3>";
                $sql_unassign_car_res = "select * from CarStatusView where CarKey = $CarKey";
                $unassign_car_res = $conn->query($sql_unassign_car_res);
                echo "<table border=1>";
                echo "<tr><td>CarKey</td><td>CarStatusString</td><td>VestKey</td><td>RouteColor</td><td>DriverLastName</td><td>CarStatusKey</td><td>IsActive</td><td>RouteKey</td><td>DriverKey</td><td>Modified</td></tr>";
                echo"<tr>";
                if ($unassign_car_res->num_rows > 0) {
                    while($row = $unassign_car_res->fetch_assoc()) {
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
                } }
                echo "</table>";
	    }

	function requestOrange() {
                echo "This is request an orange driver that is selected";
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
		$sql_orange_ready = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ((select VestKey from VestStatusView where RouteKey = 1 and VestStatusKey in (1,10) order by Modified limit 1), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Be Ready') )";
		$conn->query($sql_orange_ready);
                echo "<br>";
                echo "An orange driver gets ready!";
	}

	function requestYellow() {
                echo "This is request a yellow driver that is selected";
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $sql_yellow_ready = "INSERT INTO VestStatus (VestKey, VestStatusKey)
                            VALUES ((select VestKey from VestStatusView where RouteKey = 2 and VestStatusKey in (1,10) order by Modified limit 1), ( select VestStatusTypes.VestStatusKey from VestStatusTypes where VestStatusTypes.VestStatusShort like 'Be Ready') )";
                $conn->query($sql_yellow_ready);
                echo "<br>";
                echo "A yellow driver gets ready!";

        }
        ?>

    </center>
    </body>
</html>
