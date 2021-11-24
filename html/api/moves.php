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

  $query = "CALL log_move_event(pokemon_id, move_name)
  VALUES (?, ?)";

  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error . "\n");
  $stmt->bind_param($pokemonId, $moveName);
  if (!$stmt->execute()) die($conn->error . "\n");

  header('Location: ../pokemon.php');
  exit();

  /* Prepare the query */
  /* This query needs to insert the data to the event table as well as to the move_learned table.
   * In order to insert a data into the move_learned table, you need the id created when inserting
   * into the event table. The event table needs the time when the event took place.
   * These two queries needs to happen in a single transaction. I might create a function that
   * takes care of inserting data to these two tables. This function takes the following arguments:
   * In order to insert a record to the move_learned table, I nedd the the latest level_up_event for
   * the pokemon that just learned the new move. If there is already a move event that references
   * the same level_up_event_id, the primary key constrain (or unique key constraint) to the
   * level_up_event_id field should prevent the insertion. */
?>