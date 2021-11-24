DELIMITER $$

CREATE PROCEDURE log_move_event(
  pokemon_id INT,
  move_name VARCHAR(255)
)
BEGIN
  /* Create a local variable for storing the current level of the pokemon */
  DECLARE current_level INT;
  /* Get the current level of the pokemon */
  SELECT level INTO current_level
    FROM pokemon
    WHERE id = pokemon_id;
  /* Start a transaction */
  START TRANSACTION;
  /* Insert the pokemon's id and current datetime */
  INSERT INTO event (pokemon_id, happened_at)
    VALUES(pokemon_id, NOW());
  /* Insert the event id generated, the move's name, and the pokemon's
   * current level into the move_learned table */
  INSERT INTO move_learned (event_id, move_name, level)
    VALUES(LAST_INSERT_ID(), move_name, current_level);
  /* Commit the transactioin */
  COMMIT;
END $$

DELIMITER ;