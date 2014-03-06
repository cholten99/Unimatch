<?php

if (isset($_SERVER["DB1_HOST"])) {

print "One!<br>";

  // Pagodabox
  $db['default']['hostname'] = $_SERVER["DB1_HOST"].':'.$_SERVER["DB1_PORT"];
  $db['default']['username'] = $_SERVER["DB1_USER"];
  $db['default']['password'] = $_SERVER["DB1_PASS"];
  $db['default']['database'] = $_SERVER["DB1_NAME"];
  $db['default']['port'] = $_SERVER["DB1_PORT"];
} else {
  // my localhost configuration here
}

print "Two!<br>";

$con = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']); // database info

if (!$con) {

print "Three!" . mysql_error() . "<br>";

  die('Could not connect: ' . mysql_error()); // if can't connect
}

print "Four!<br>";

mysql_select_db($db['default']['database'], $con);

?>