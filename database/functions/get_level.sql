DELIMITER $$

/* Function for getting the current level of a pokemon */
CREATE FUNCTION get_level
(
  pokemon_id INT
)
/* Specify return value type */
RETURNS INT
/* Current level can vary across queries */
NOT DETERMINISTIC
BEGIN
  DECLARE level INT;
  /* Get the pokemon's level */
  SELECT p.level INTO level
    FROM pokemon p
    WHERE p.id = pokemon_id;
  /* Return the current level of the pokemon */
  RETURN level;
END $$

DELIMITER ;