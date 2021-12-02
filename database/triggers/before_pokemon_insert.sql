DELIMITER $$

CREATE TRIGGER before_pokemon_insert
BEFORE INSERT ON pokemon
FOR EACH ROW
BEGIN
  DECLARE size int;
  IF (get_party_size(NEW.trainer_id) >=2) THEN 
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Party limit exceeded";
  END IF;
END $$

DELIMITER ;




