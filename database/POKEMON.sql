DROP TABLE IF EXISTS type;
CREATE TABLE type (
  PRIMARY KEY (name),
  name VARCHAR(50)
);

DROP TABLE IF EXISTS species;
CREATE TABLE species (
  PRIMARY KEY (name),
  name VARCHAR(50),
  type VARCHAR(50),
  FOREIGN KEY (type)
    REFERENCES type(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

DROP TABLE IF EXISTS pokemon;
CREATE TABLE pokemon (
  PRIMARY KEY (id),
  id INT AUTO_INCREMENT,
  name VARCHAR(50),
  species VARCHAR(50),
  trainer_id INT,
  pokemon_level INT,
  is_female Boolean,
  FOREIGN KEY (species)
    REFERENCES species(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (trainer_id)
    REFERENCES trainer(id)
    ON DELETE RESTRICT
);