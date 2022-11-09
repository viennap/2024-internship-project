<html>
<head>
<title>Vest Tracking Test</title>
</head>
<body>
<center>

<h1>
    <a href="https://blog.csdn.net" alt="CSDN Logo" title="CSDN home">
        CSDN
    </a>
</h1>

<?php
echo "<h1>Vests Tracking Home</h1>";
echo "<h2>Drivers</h2>";

echo "<a href=./vest_tracking_drivers_check.php> check current drivers</a>";
echo "<br>";
echo "<a href=./vest_tracking_drivers_init.php> initialize a driver </a>";
echo "<br>";
echo "<a href=./vest_tracking_drivers_checkin.php> driver check-in/out </a>";
echo "<br>";
echo "<a href=./vest_tracking_driver_status_change.php> drivers status change </a>";

echo "<h2>Routes</h2>";
echo "<a href=./vest_tracking_driver_training.php> assign/unassign drivers&routes </a>";

echo "<h2>Vests</h2>";
echo "<a href=./vest_tracking_vests_check.php> Check current available vests </a>";
echo "<br>";
echo "<a href=./vest_tracking_vests_init.php> initialize a vest </a>";
echo "<br>";
echo "<a href=./vest_tracking_vests_assign.php> assign/unassign drivers&vests </a>";


echo "<h2>Cars</h2>";
echo "<a href=./vest_tracking_cars_assign.php> assign/unassign vests&cars </a>";

echo "<h2>Views</h2>";
echo "<a href=./vest_tracking_vests_view.php> current VestStatusView </a>";
echo "<br>";
echo "<a href=./vest_tracking_cars_view.php> current CarStatusView </a>";

echo "<h2>Drivers List</h2>";
echo "<a href=./vest_tracking_orange_driver_list.php> Orange Driver </a>";
echo "<br>";
echo "<a href=./vest_tracking_yellow_driver_list.php> Yellow Driver </a>";



?>

</center>
</body>
</html>
