DROP TABLE IF EXISTS egg;
CREATE TABLE egg (
  PRIMARY KEY (event_id),
  event_id INT,
  partner_id INT,
  FOREIGN KEY (event_id)
    REFERENCES event(id)
    ON DELETE RESTRICT,
  FOREIGN KEY (partner_id)
    REFERENCES pokemon(id)
);