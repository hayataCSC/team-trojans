<?php

  /* Configure the error output level */
  require(__DIR__ . '/../config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/../config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');

  $t_name = htmlentities($_POST['name']);
  $t_phone = htmlspecialchars($_POST['phone']);
  $t_email = htmlspecialchars($_POST['email']);
  /* Construct the query based on the request method */
  $stmt;
  switch ($_POST['query']) {
    case 'POST':
      $query = "INSERT INTO trainer(name, phone, email)
        VALUES(?, ?, ?);";
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error . "\n");
      $stmt->bind_param('sss', $t_name, $t_phone, $t_email);
      break;
    case 'PUT':
      $query = "UPDATE trainer SET name = ?, phone = ?, email = ? WHERE id = ?;";
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error . "\n");
      $stmt->bind_param('sssi', $t_name, $t_phone, $t_email, $_POST['id']);
      break;
    case 'DELETE':
      $query = "DELETE FROM trainer WHERE id = ?;";
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error . "\n");
      $stmt->bind_param('i', $_POST['id']);
      break;
    default:
      die("Unknown request method\n");
  }

  /* Execute the query */
  if(!$stmt->execute()) die($conn->error . "\n");

  /* Rediret the user based on the request method */
  switch($_POST['query']) {
    case 'POST':
      $id = $conn->insert_id;
      header('Location: ../trainer.php/?id=' . $id);
      exit();
    case 'PUT':
      $id = $_POST['id'];
      header('Location: ../trainer.php/?id=' . $id);
      exit();
    case 'DELETE':
      header('Location: ../trainers.php');
      exit();
  }

?>