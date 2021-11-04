DROP TABLE IF EXISTS event;
CREATE TABLE event (
  PRIMARY KEY (id),
  id INT AUTO_INCREMENT,
  pokemon_id INT,
  happened_at DATETIME,
  FOREIGN KEY (pokemon_id)
    REFERENCES pokemon(id)
    ON DELETE RESTRICT
);