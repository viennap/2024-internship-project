<html>
    <head>

        <title>Current vests</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

        <style>
        * { box-sizing: border-box; }
        .column { float: left; width: 50%; padding: 10px; }
        .row:after { content: ""; display: table; clear: both; }
        </style>

    </head>


    <body>
    <center>

        <div class="row">
            <h1> Current vest list </h1>
        </div>

        <div class="row">

            <div class="column">
                <?php
                    header("refresh: 60;");
                    $timestamp=time();
                    echo "Last refresh:  ";
                    echo date("Y-m-d H:i:s", $timestamp);
                ?>
            </div>

            <div class="column">
                <a href="https://ransom.isis.vanderbilt.edu/home.html#vests" class=button> Go Home </a>
            </div>

        </div>
        
        
        <?php
            header("refresh: 60;");
            echo "<br>";
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
            } }
            echo "</table>";
        ?>

    </center>
    </body>
</html>
