<?php
  /* Configure the error output level */
  require(__DIR__ . '/../config/error.php');

  /* Get request the pokemon id from the request parameter */
  $pokemonId = $_POST['pokemon_id'];
  /* Check if the parameter is set */
  if (!$pokemonId) die('You need to specify the move name');

  /* Import the connection function from db.php */
  require(__DIR__ . '/../config/db.php');
  /* Connect to the database */
  $conn = connect();

  /* Call the have_egg stored procedure with the pokemonId as an argument */
  $query = "CALL have_egg(?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('s', $pokemonId);
  if (!$stmt->execute()) die($conn->error);

  /* Redirect the user */
  header("Location: ../pokemon.php?id=$pokemonId");
  exit();
?>