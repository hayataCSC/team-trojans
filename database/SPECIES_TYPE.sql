DROP TABLE IF EXISTS species_type;
CREATE TABLE species_type (
  PRIMARY KEY (name, type),
  name VARCHAR(50),
  type VARCHAR(50),
  FOREIGN KEY (name)
    REFERENCES species(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (type)
    REFERENCES type(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);