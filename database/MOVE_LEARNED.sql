DROP TABLE IF EXISTS move_learned;
CREATE TABLE move_learned (
  PRIMARY KEY (event_id),
  event_id INT,
  move_name VARCHAR(50),
  level INT,
  FOREIGN KEY (event_id)
    REFERENCES event(id)
    ON DELETE RESTRICT,
  FOREIGN KEY (move_name)
    REFERENCES move(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);