DROP TABLE IF EXISTS trainer;
CREATE TABLE trainer (
  PRIMARY KEY (id),
  id INT AUTO_INCREMENT,
  name VARCHAR(50),
  phone VARCHAR(50),
  email VARCHAR(50)
);