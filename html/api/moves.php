<?php
  /* Configure the error output level */
  require(__DIR__ . '/../config/error.php');

  /* Get request parameters */
  $pokemonId = $_POST['pokemon_id'];
  $moveName = $_POST['move'];
  /* Check if parameters are set */
  if (!$pokemonId || !$moveName) die('You need to specify the move name');

  /* Import the connection function from db.php */
  require(__DIR__ . '/../config/db.php');
  /* Connect to the database */
  $conn = connect();

  /* Call the log_move_event stored procedure with two arguments */
  $query = "CALL log_move_event(?, ?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error . "\n");
  $stmt->bind_param('ss', $pokemonId, $moveName);
  if (!$stmt->execute()) die($conn->error . "\n");

  /* Redirect the user */
  header("Location: ../pokemon.php?id=$pokemonId");
  exit();
?>