<?php

  function connect($database = null) {
    /* Create Connection */
    $conn = mysqli_connect('localhost', 'hayata_suenaga', 'password', $database);

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