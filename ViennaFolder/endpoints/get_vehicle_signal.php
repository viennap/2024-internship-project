<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (array_key_exists("trajectory_id", $_GET) and array_key_exists("signal_name", $_GET)) {
  $trajectory_id = $_GET["trajectory_id"];
	$signal_name = $_GET["signal_name"];
	echo $trajectory_id;

  header("Content-Type: application/json");
  echo json_encode($result);
}
else {
  die("No trajectory id or signal name supplied!");
}
?>
