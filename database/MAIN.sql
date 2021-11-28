DROP DATABASE IF EXISTS pokemon;
CREATE DATABASE pokemon;

USE pokemon;

SOURCE TRAINER.sql;
SOURCE TYPE.sql;
SOURCE SPECIES.sql;
SOURCE SPECIES_TYPE.sql;
SOURCE POKEMON.sql;
SOURCE FRIEND.sql;
SOURCE EVENT.sql;
SOURCE EGG.sql;
SOURCE LEVEL_UP.sql;
SOURCE MOVE.sql;
SOURCE MOVE_LEARNED.sql;
SOURCE procedures/move_event.sql;
SOURCE procedures/level_up.sql;
SOURCE procedures/befriend.sql;
SOURCE procedures/get_friends.sql;
SOURCE functions/get_partner.sql;
SOURCE procedures/have_egg.sql;
SOURCE procedures/get_all_events.sql;

create function total_moves()
Returns int
Return(Select count(event_id) from move_learned);

create function total_levelups()
Returns int
Return(Select count(event_id) from level_up);

delimiter $$
create Function total_eggs() RETURNS int
Begin
declare total int;
declare actual_total int;
set total = (Select count(event_id) from egg); 
set actual_total = (Select total div 2);
return actual_total;
End $$
delimiter ;


