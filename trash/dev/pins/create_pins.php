<?php

$colourArray = $lines = file('colours.txt');
$colourLength = count($colourArray);

$urlFront = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|";

for ($i = 0; $i < $colourLength; $i++) {

  print $urlFront . $colourArray[$i] . "<br>";  

  $handle = fopen($urlFront . $colourArray[$i], "rb");
  $contents = stream_get_contents($handle);
  fclose($handle);

  $fileName = $i . ".png";
  $fp = fopen($fileName, 'w');
  fwrite($fp, $contents);
  fclose($fp);
}

?>