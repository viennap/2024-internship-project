<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (array_key_exists("trajectory_id", $_GET)) {
  $trajectory_id = $_GET["trajectory_id"];
	echo $trajectory_id;

  header("Content-Type: application/json");
  echo json_encode($result);
}
else {
  die("No trajectory id supplied!");
}
?>
