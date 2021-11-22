<?php

  /* Check if the query parameter is set */
  if (!isset($_GET['id']))
    die('You need to specify the trainer\'s id in the query parameter');

  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');

  /* Get the entry for the request trainer from the trainers table */
  $query = 'SELECT * FROM trainer WHERE id = ?;';
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  /* Get the first row as an associative array */
  $trainer = $result->fetch_assoc();

  /* Get the entry for the request trainer from the trainers table */
  $query = 'SELECT name, species, level, is_female FROM pokemon WHERE trainer_id = ?;';
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  /* Get each row as an associative array */
  $pokemons = $result->fetch_all(MYSQLI_ASSOC);

  $query = "SELECT name FROM species";
  $species = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
  /* Close the connection to the database */
  $conn->close();

?>