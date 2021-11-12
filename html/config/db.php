<?php

  function connect($database = null) {
    $config = parse_ini_file(__DIR__ . '/../../mysql.ini');

    $dbname = 'pokemon';
    $conn = new mysqli(
      $config['mysqli.default_host'],
      $config['mysqli.default_user'],
      $config['mysqli.default_pw'],
      $dbname);

    if ($conn->connect_errno) {
      /* If connection failed, display error number and error details to the page */
      echo "Failed to connect to the database " . "<br>";
      echo "Error Number: " . $conn->connect_errno . "<br>";
      echo "Details: " . $conn->connect_error . "<br>";
      exit; // Exit from the program
    }

    return $conn;
  }

?>