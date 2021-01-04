<?php
function test_input($data) {
  //$dataa = filter_var($data, FILTER_SANITIZE_STRING);	
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}