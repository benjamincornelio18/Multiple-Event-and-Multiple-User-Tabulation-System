<?php

function sanitize_input($data) {
  $data = trim($data);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
