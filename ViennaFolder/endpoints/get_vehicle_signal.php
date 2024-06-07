<?php
//declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (array_key_exists("trajectory_id", $_GET) and array_key_exists("signal_name", $_GET)) {
  $trajectory_id = $_GET["trajectory_id"];
	$signal_name = $_GET["signal_name"];

  $time = array(1701389314.333874,
  1701389316.330543,
  1701389318.331073,
  1701389320.333315,
  1701389322.330893,
  1701389324.330345,
  1701389326.331383,
  1701389328.33033,
  1701389330.330672,
  1701389332.333329,
  1701389334.331528,
  1701389336.331134,
  1701389338.331494,
  1701389340.331244,
  1701389342.329876,
  1701389344.331,
  1701389346.331778,
  1701389348.329675,
  1701389350.329441,
  1701389352.33114,
  1701389354.329204,
  1701389356.336824,
  1701389358.331271,
  1701389360.330447,
  1701389362.330709,
  1701389364.330177,
  1701389366.328819,
  1701389368.329246,
  1701389370.328743,
  1701389372.329097,
  1701389374.329531,
  1701389376.330457,
  1701389378.330179,
  1701389380.330864,
  1701389382.33007,
  1701389384.330514,
  1701389386.330317,
  1701389388.329472,
  1701389390.330113,
  1701389392.329445,
  1701389394.330265,
  1701389396.329443,
  1701389398.329187,
  1701389400.329373,
  1701389402.329646,
  1701389404.328629,
  1701389406.329424,
  1701389408.328639,
  1701389410.328867,
  1701389412.328451,
  1701389414.328528,
  1701389416.329102,
  1701389418.328163,
  1701389420.328495,
  1701389422.329196,
  1701389424.328595,
  1701389426.329997,
  1701389428.328251,
  1701389430.327962,
  1701389432.327843,
  1701389434.327549,
  1701389436.327277,
  1701389438.335036,
  1701389440.327297,
  1701389442.327921,
  1701389444.327303,
  1701389446.327062,
  1701389448.327995,
  1701389450.328215,
  1701389452.327395,
  1701389454.32763,
  1701389456.326926,
  1701389458.327221,
  1701389460.326899,
  1701389462.326734,
  1701389464.32668,
  1701389466.326798,
  1701389468.327809,
  1701389470.326684,
  1701389472.327814,
  1701389474.327067,
  1701389476.327449,
  1701389478.327681,
  1701389480.327299,
  1701389482.327582,
  1701389484.327537,
  1701389486.327229,
  1701389488.327665,
  1701389490.327728,
  1701389492.328101,
  1701389494.326639,
  1701389496.328876,
  1701389498.326709,
  1701389500.32665,
  1701389502.327375);
  
  $signal = array(16.46,
  19.66,
  20.59,
  18.73,
  18.7625,
  21.922500000000003,
  24.37,
  26.3775,
  28.0625,
  28.1125,
  25.252499999999998,
  22.7175,
  20.4525,
  11.855,
  9.5,
  15.6525,
  25.7875,
  37.745000000000005,
  46.477500000000006,
  52.4075,
  54.940000000000005,
  55.8175,
  55.1775,
  53.9675,
  53.352500000000006,
  53.192499999999995,
  54.042500000000004,
  55.615,
  56.09,
  56.4375,
  56.3025,
  56.89,
  56.395,
  56.515,
  56.61,
  56.9375,
  57.0625,
  57.1225,
  57.330000000000005,
  57.627500000000005,
  57.612500000000004,
  56.5175,
  54.4025,
  51.315,
  51.06750000000001,
  55.5675,
  55.74250000000001,
  51.175000000000004,
  45.6625,
  39.830000000000005,
  29.134999999999998,
  18.865000000000002,
  17.2825,
  23.637500000000003,
  29.165000000000003,
  32.31,
  31.540000000000003,
  28.405,
  25.727500000000003,
  24.352500000000003,
  25.707500000000003,
  27.875000000000004,
  30.325000000000003,
  32.925,
  34.61,
  35.5675,
  35.995,
  35.9375,
  34.935,
  35.865,
  37.4675,
  37.6775,
  35.845,
  35.665,
  36.1075,
  36.4575,
  37.06,
  37.917500000000004,
  36.605000000000004,
  33.785000000000004,
  28.2975,
  16.92,
  10.02,
  0.0,
  3.9225000000000003,
  6.0925,
  4.654999999999999,
  3.1500000000000004,
  7.55,
  8.100000000000001,
  5.2875,
  3.9675000000000002,
  3.95,
  2.5949999999999998,
  2.7025);

  $result = array("time" => $time, "signal" => $signal);
  
  header("Content-Type: application/json");
  echo json_encode($result);
}
else {
  die("No trajectory id or signal name supplied!");
}
?>
