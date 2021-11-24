DELIMITER $$

CREATE PROCEDURE get_friends
(
  pokemon INT
)
BEGIN
  SELECT f.pokemon_2 AS id, p.name AS name
    FROM friend f
    LEFT JOIN pokemon p
      ON f.pokemon_2 = p.id
    WHERE pokemon_1 = pokemon;
END $$

DELIMITER ;