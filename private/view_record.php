<html>

    <head>

        <title>View Record Function</title>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
        <noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>

        <style>
        * { box-sizing: border-box; }
        .column { float: left; width: 50%; padding: 10px; }
        .row:after { content: ""; display: table; clear: both; }
        </style>

    </head>


    <body>
    <center>

        <div class="row">
            <h1> View Record Function </h1>
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
                <a href="https://ransom.isis.vanderbilt.edu/home.html" class=button> Go Home </a>
            </div>

        </div>

        <?php
            $sqlinfo = require_once('/var/www/config.php');
            $conn = new mysqli($sqlinfo['hostname'],$sqlinfo['username'],$sqlinfo['password'],$sqlinfo['database']);

            $vests_view_record_sql ="insert ignore into VestStatusView_rec select * from VestStatusView";
            $conn->query($vests_view_record_sql);
	    
	    $cars_view_record_sql ="insert ignore into CarStatusView_rec select * from CarStatusView";
            $conn->query($cars_view_record_sql);
        ?>

    </center>
    </body>
    
</html>
