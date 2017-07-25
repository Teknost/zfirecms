<?php

// Manages huge strings into tighter lengths with minimal formatting

function truncateString($string,$length=400,$append="…") {
  $string = trim(str_replace('"', '', strip_tags($string)));
  if(strlen($string) > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }
  return $string;
}

?>
