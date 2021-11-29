DELIMITER $$

CREATE FUNCTION can_learn_move
(
  pokemon_id INT
)
RETURNS BOOLEAN
NOT DETERMINISTIC
BEGIN
  /* Create a local variable for storing the current level of the pokemon */
  DECLARE current_level INT;
  /* Create a local variable for counting the number of move event records
   * with specific attributes */
  DECLARE count INT;
  /* Get the current level of the pokemon */
  SET current_level = get_level(pokemon_id);
  /* Check if the same pokemon and level combination exists in the move_learned table */
  SELECT COUNT(*) INTO count
    FROM event e
    INNER JOIN move_learned m
      ON e.id = m.event_id
    WHERE e.pokemon_id = pokemon_id AND m.level = current_level;
  /* Returns true if new move can be learned */
  RETURN count = 0;
END $$

DELIMITER ;