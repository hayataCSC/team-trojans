DROP TABLE IF EXISTS pokemon;
CREATE TABLE pokemon (
  PRIMARY KEY (id),
  id INT AUTO_INCREMENT,
  name VARCHAR(50),
  species VARCHAR(50),
  trainer_id INT,
  level INT UNSIGNED,
  is_female Boolean,
  FOREIGN KEY (species)
    REFERENCES species(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (trainer_id)
    REFERENCES trainer(id)
    ON DELETE RESTRICT
);