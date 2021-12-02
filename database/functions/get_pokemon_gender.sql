DELIMITER $$

CREATE FUNCTION get_pokemon_gender
(
  pokemon_id INT
)
RETURNS BOOLEAN
NOT DETERMINISTIC
BEGIN
  SELECT is_female INTO @is_female
    FROM pokemon
    WHERE id = pokemon_id;
  RETURN @is_female;
END $$

DELIMITER ;