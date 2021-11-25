<?php
  /* Configure the error output level */
  require(__DIR__ . '/../config/error.php');

  /* Get pokemon id */
  $pokemonId = $_POST['pokemon_id'];
  $operation = $_POST['operation'];
  /* Check if parameters are set */
  if (!$pokemonId || !$operation) die('You need to specify the move name');

  /* Import the connection function from db.php */
  require(__DIR__ . '/../config/db.php');
  /* Connect to the database */
  $conn = connect();

  /* Depending on the operation requested, send query to the database */
  switch($operation) {
    case 'level_up':
      /* Call the stored procedure for incrementing pokemon level */
      $query = 'CALL level_up(?)';
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error);
      $stmt->bind_param('i', $pokemonId);
      if (!$stmt->execute()) die($conn->error);
      /* Redirect the user */
      header("Location: ../pokemon.php/?id=$pokemonId");

    case 'befriend':
      /* Get the id of the new friend pokemon */
      $friendId = $_POST['new_friend_id'];
      /* Call the stored procedure for adding a friend */
      $query = 'CALL befriend(?, ?)';
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error);
      $stmt->bind_param('ii', $pokemonId, $friendId);
      if (!$stmt->execute()) die($conn->error);
      /* Redirect the user */
      header("Location: ../pokemon.php/?id=$pokemonId");
  }

?>