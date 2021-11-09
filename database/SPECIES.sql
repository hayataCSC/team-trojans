DROP TABLE IF EXISTS species;
CREATE TABLE species (
  PRIMARY KEY (name, type),
  name VARCHAR(50),
  type VARCHAR(50),
  FOREIGN KEY (type)
    REFERENCES type(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);