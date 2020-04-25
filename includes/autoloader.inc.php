<?php

spl_autoload_register('myAutoLoader');

function myAutoLoader($className) {
  $path = "classes/";
  $extension = ".class.php";
  $fullPath = $path . strtolower($className) . $extension;

  include_once $fullPath;
}

?>
