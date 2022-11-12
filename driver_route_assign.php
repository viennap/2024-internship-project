<html>
<head>

        <title>Driver to route assignment</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

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
            <h1> Driver to route assignment </h1>
        </div>

        <div class="row">
                <a href="https://ransom.isis.vanderbilt.edu/home.html#drivers" class=button> Go home </a>
        </div>

        <form action='' method='post'>
            <div class="row">
                <div class="column" style="width: 15%"><br></div>
                <div class="column" style="width: 35%">
                    DriverKey(*): <input type='text' name='DriverKeyR'>
                </div>
                <div class="column" style="width: 35%">
                    RouteKey(*): <input type='text' name='RouteKey'>
                </div>
                <div class="column" style="width: 15%"><br></div>
            </div>
            <div class="row">
                <div class="column" style="width: 12.5%"><br></div>
                <div class="column">
                    <input type='submit' value='Check current route' name='checkTraining' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Assign driver to route' name='assignRouteDriver' class="button">
                </div>
                <div class="column">
                    <input type='submit' value='Unassign driver from route' name='unassignRouteDriver' class="button">
                </div>
                <div class="column" style="width: 12.5%"><br></div>
            </div>
        </form>


        <?php
            echo "<br>";
            echo "<a href=./home.html> go home </a>";
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
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
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
                } }
                echo "</table>";
            }


            function assignRouteDriver() {
                echo "This is assign driver to a trained route that is selected";
                $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
                $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
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
                } }
                echo "</table>";
            }


            function unassignRouteDriver() {
                echo "This is unassign driver from route that is selected";
                $DriverKeyR = empty($_POST['DriverKeyR'])?die("Please input the DriverKey"):$_POST['DriverKeyR'];
                $RouteKey = empty($_POST['RouteKey'])?die("Please input the RouteKey"):$_POST['RouteKey'];
                $sqlinfo = require_once('/var/www/config.php');
                $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);
                $una_rd_sql = "DELETE from Training where DriverKey = $DriverKeyR and RouteKey = $RouteKey ";
                $conn->query($una_rd_sql);
                echo "<br>";
                echo "Unassign driver from route success!";
            }

        ?>

    </center>
    </body>
</html>
