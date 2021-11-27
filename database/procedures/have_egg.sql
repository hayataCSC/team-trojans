DELIMITER $$

/* Procedure for registering an egg event for a single pokemon.
 * This procedure is used in the "have_egg" procedure that registers an egg
 * event for both parents */
CREATE PROCEDURE register_egg_event
(
  /* The id of pokemon with which to register an egg event */
  pokemon_to_register INT,
  /* Partner pokemon's id */
  partner_id INT
)
BEGIN
  /* Start a transaction. This transaction modifies the event and egg tables */
  START TRANSACTION;
  /* Insert pokemon_id and datetime into the event table */
  INSERT INTO event(pokemon_id, happened_at)
    VALUES(pokemon_to_register, NOW());
  /* Register egg event in the egg table */
  INSERT INTO egg(event_id, partner_id)
    VALUES(LAST_INSERT_ID(), partner_id);
  /* Commit the transaction */
  COMMIT;
END $$

/* Procedure for registering an egg event for both parents.
 * This procedure takes either parent's id as an input. From that id,
 * the procedure figures out the partner's id (only pokemons from the same
 * trainer can have eggs, and a trainer can leave only up to two pokemons at
 * the day care). If there is no partner (if the input pokemon is the only pokemon
 * owned by the trainer), this procedure raises an error.
 * A single egg event is duplicated for both parents. */
CREATE PROCEDURE have_egg
(
  /* Takes either parent's id */
  parent_id INT
)
BEGIN
  /* Get the id of the partner (pokemon owned by the same trainer) */
  SET @partner_id = get_partner(parent_id);
  /* If there is no partner, raise an error */
  IF (@partner_id IS NULL) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'There is no partner for this pokemon to have an egg with';
  END IF;
  /* Start a transaction. In this transaction, an egg event is registered for both parents */
  START TRANSACTION;
  CALL register_egg_event(parent_id, @partner_id);
  CALL register_egg_event(@partner_id, parent_id);
  /* Commit the transaction */
  COMMIT;
END $$

DELIMITER ;