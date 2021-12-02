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
  DECLARE pokemon_gender BOOLEAN;
  DECLARE partner_gender BOOLEAN;
  /* Get the gender and trainer id of the input pokeon */
  SELECT p.trainer_id, p.is_female
    INTO trainer_id, pokemon_gender
    FROM pokemon p
    WHERE id = pokemon_id;
  /* Get the pokemon that is owned by the same trainer */
  SELECT p.id, p.is_female
    INTO partner_id, partner_gender
    FROM pokemon p
    WHERE p.trainer_id = trainer_id AND p.id != pokemon_id;
  /* If pokemons owned by the trainer is the same sex, return null.
   * Otherwise, return the id of the partner */
  RETURN IF(pokemon_gender = partner_gender, NULL, partner_id);
END $$

DELIMITER ;