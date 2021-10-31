DROP TABLE IF EXISTS event;
CREATE TABLE event (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pokemon_id INT,
  happened_at DATETIME,
  FOREIGN KEY (pokemon_id)
    REFERENCES pokemon(id)
    ON DELETE RESTRICT
);