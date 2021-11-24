DELIMITER $$

CREATE PROCEDURE get_friends
(
  pokemon INT
)
BEGIN
  SELECT pokemon_2
    FROM friend
    WHERE pokemon_1 = pokemon;
END $$

DELIMITER ;