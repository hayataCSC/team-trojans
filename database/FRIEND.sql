DROP TABLE IF EXISTS friend;
CREATE TABLE friend (
  PRIMARY KEY (pokemon_1, pokemon_2),
  pokemon_1 INT,
  pokemon_2 INT,
  FOREIGN KEY (pokemon_1)
    REFERENCES pokemon(id)
    ON DELETE RESTRICT,
  FOREIGN KEY (pokemon_2)
    REFERENCES pokemon(id)
    ON DELETE RESTRICT
);