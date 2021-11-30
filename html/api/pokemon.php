<?php
  /* Configure the error output level */
  require(__DIR__ . '/../config/error.php');

  $operation = $_POST['operation'];
  /* Check if parameters are set */
  if (!$operation) die('Required parameter not specified');

  /* Import the connection function from db.php */
  require(__DIR__ . '/../config/db.php');
  /* Connect to the database */
  $conn = connect();

  /* Depending on the operation requested, send query to the database */
  switch($operation) {
    case 'add_pokemon':

      $is_female = $_POST['gender'] === 'female' ? 1 : 0;

      $query = 'INSERT
        INTO pokemon(name, species, trainer_id, level, is_female)
        VALUES(?, ?, ?, ?, ?);';
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error);

      $stmt->bind_param(
        'sssss',
        $_POST['name'],
        $_POST['species'],
        $_POST['trainer_id'],
        $_POST['level'],
        $is_female
      );

      if (!$stmt->execute()) die($conn->error);
      /* Redirect the user back to the trainer page */
      header("Location: ../trainer.php/?id={$_POST['trainer_id']}");
      exit();
      break;

    case 'level_up':
      /* Get pokemon id */
      $pokemonId = $_POST['pokemon_id'];
      /* Call the stored procedure for incrementing pokemon level */
      $query = 'CALL level_up(?)';
      $stmt = $conn->prepare($query);
      if (!$stmt) die($conn->error);
      $stmt->bind_param('i', $pokemonId);
      if (!$stmt->execute()) die($conn->error);
      /* Redirect the user */
      header("Location: ../pokemon.php/?id=$pokemonId");
      exit();

    case 'befriend':
      /* Get pokemon id */
      $pokemonId = $_POST['pokemon_id'];
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
      exit();
  }

?>