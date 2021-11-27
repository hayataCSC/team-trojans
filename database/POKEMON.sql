DROP TABLE IF EXISTS pokemon;
CREATE TABLE pokemon (
  PRIMARY KEY (id),
  id INT AUTO_INCREMENT,
  name VARCHAR(50),
  species VARCHAR(50),
  trainer_id INT,
  level INT,
  is_female Boolean,
  FOREIGN KEY (species)
    REFERENCES species(name)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (trainer_id)
    REFERENCES trainer(id)
    ON DELETE RESTRICT
);

CREATE Function get_party_size (t_id INT)
RETURNS Int 
Return (Select count(trainer_id) from pokemon
    where trainer_id = t_id );

Drop TRIGGER if EXISTS pokemon_limit;
delimiter $$
CREATE TRIGGER pokemon_limit before INSERT on pokemon
for each row
Begin
Declare size int;
Set size = get_party_size(new.trainer_id);
if size >=2 then 
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Party limit exceeded";
End if;
End;
$$
delimiter ;

