DELIMITER $$

/* Function for getting the id of the pokemon that is owned by the same
 * trainer who owns the input pokemon. Since a trainer can only leave up to two
 * pokemons at the day care, the return value is either NULL or the id of
 * a single pokemon */
CREATE FUNCTION get_partner
(
  pokemon_id INT
)
/* Specify return value type */
RETURNS INT
/* Output is not always the same for the same pokemon_id */
NOT DETERMINISTIC
BEGIN
  DECLARE trainer_id INT;
  DECLARE partner_id INT;
  /* Get the trainer id of the input pokeon */
  SELECT p.trainer_id INTO trainer_id
    FROM pokemon p
    WHERE id = pokemon_id;
  /* Get the pokemon that is owned by the same trainer */
  SELECT p.id INTO partner_id
    FROM pokemon p
    WHERE p.trainer_id = trainer_id AND p.id != pokemon_id;
  /* Return the id of the pokemon that is owned by the same trainer */
  RETURN partner_id;
END $$

DELIMITER ;