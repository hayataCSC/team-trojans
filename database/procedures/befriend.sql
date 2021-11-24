DELIMITER $$

CREATE PROCEDURE befriend(
  pokemon_1 INT,
  pokemon_2 INT
)
BEGIN
  /* Start a transaction */
  START TRANSACTION;
  /* Insert friendship in both direction */
  INSERT INTO friend(pokemon_1, pokemon_2)
    VALUES(pokemon_1, pokemon_2);
  INSERT INTO friend(pokemon_1, pokemon_2)
    VALUES(pokemon_2, pokemon_1);
  /* Commit the transaction */
  COMMIT;
END $$

DELIMITER ;