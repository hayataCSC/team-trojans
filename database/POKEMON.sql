DROP TABLE IF EXISTS type;
CREATE TABLE type (
  name VARCHAR(50) PRIMARY KEY
);

DROP TABLE IF EXISTS species;
CREATE TABLE species (
  name VARCHAR(50) PRIMARY KEY,
  type VARCHAR(50),
  FOREIGN KEY (type)
    REFERENCES type(name)
);

DROP TABLE IF EXISTS pokemon;
CREATE TABLE pokemon (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50),
  species VARCHAR(50),
  trainer_id INT,
  pokemon_level INT,
  is_female Boolean,
  FOREIGN KEY (species)
    REFERENCES species(name),
  FOREIGN KEY (trainer_id)
    REFERENCES trainer(id)
);