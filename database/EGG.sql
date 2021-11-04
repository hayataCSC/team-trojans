DROP TABLE IF EXISTS egg;
CREATE TABLE egg (
  PRIMARY KEY (event_id),
  event_id INT,
  egg_num SMALLINT,
  FOREIGN KEY (event_id)
    REFERENCES event(id)
    ON DELETE RESTRICT
);