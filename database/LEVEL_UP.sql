DROP TABLE IF EXISTS level_up;
CREATE TABLE level_up (
  PRIMARY KEY (event_id),
  event_id INT,
  level_reached INT UNSIGNED,
  FOREIGN KEY (event_id)
    REFERENCES event(id)
);