DELIMITER $$

CREATE PROCEDURE log_move_event(
  pokemon_id INT,
  move_name VARCHAR(255)
)
BEGIN
  /* Check if the pokemon can learn new move */
  IF (NOT can_learn_move(pokemon_id)) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Pokemon can only learn a new move after leveling up';
  END IF;

  START TRANSACTION;
  /* Insert the pokemon's id and current datetime */
  INSERT INTO event (pokemon_id, happened_at)
    VALUES(pokemon_id, NOW());
  /* Insert the event id generated, the move's name, and the pokemon's
   * current level into the move_learned table */
  INSERT INTO move_learned (event_id, move_name, level)
    VALUES(LAST_INSERT_ID(), move_name, get_level(pokemon_id));

  COMMIT;
END $$

DELIMITER ;