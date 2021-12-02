DELIMITER $$

CREATE FUNCTION get_party_size
(
  trainer_Id INT
)
RETURNS INT
NOT DETERMINISTIC
BEGIN
  SELECT COUNT(*) INTO @count
    FROM pokemon p
    WHERE p.trainer_id = trainer_id;
  RETURN @count;
END $$

DELIMITER ;