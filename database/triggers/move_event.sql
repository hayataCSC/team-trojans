DELIMITER $$

CREATE PROCEDURE log_move_event(
  pokemon_id INT,
  move_name VARCHAR(255)
)
BEGIN
  /* Create a local variable for storing the current level of the pokemon */
  DECLARE current_level INT;
  /* Create a local variable for counting the number of move event records
   * with specific attributes */
  DECLARE count INT;
  /* Get the current level of the pokemon */
  SELECT level INTO current_level
    FROM pokemon
    WHERE id = pokemon_id;
  /* Check if the same pokemon and level combination exists in the move_learned table */
  SELECT COUNT(*) INTO count
    FROM event e
    INNER JOIN move_learned m
      ON e.id = m.event_id
    WHERE e.pokemon_id = pokemon_id AND m.level = current_level;
  IF (count > 0) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Pokemon can only learn a new move after leveling up';
  END IF;
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