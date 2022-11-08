<?php

$conn = new mysqli('localhost', 'webuser', 'abcDFF2393@', 'vest_tracking_test');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

echo "Database connection was successful";

?>
