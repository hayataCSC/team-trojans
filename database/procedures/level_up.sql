DELIMITER $$

/* Stored procedure for increment a pokemon's level by one */
CREATE PROCEDURE level_up
(
  /* Take pokemon id as an input */
  pokemon_id INT
)
BEGIN
  /* Start a transaction */
  START TRANSACTION;
  /* Increment the level of the pokemon */
  UPDATE pokemon
    SET level = @new_level := level + 1
    WHERE id = pokemon_id;
  /* Log the level up event in the event table */
  INSERT INTO event(pokemon_id, happened_at)
    VALUES(pokemon_id, NOW());
  /* Log the level up event in the level_up table */
  INSERT INTO level_up(event_id, level_reached)
    VALUES(LAST_INSERT_ID(), @new_level);
  /* Commit the transaction */
  COMMIT;
END $$

DELIMITER ;