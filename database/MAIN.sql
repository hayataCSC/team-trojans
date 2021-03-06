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

SOURCE functions/get_level.sql;
SOURCE functions/can_learn_move.sql;
SOURCE functions/get_partner.sql;
SOURCE functions/total_eggs.sql;
SOURCE functions/total_levelups.sql;
SOURCE functions/total_moves.sql;
SOURCE functions/get_party_size.sql;
SOURCE functions/get_pokemon_gender.sql;

SOURCE procedures/move_event.sql;
SOURCE procedures/level_up.sql;
SOURCE procedures/befriend.sql;
SOURCE procedures/get_friends.sql;
SOURCE procedures/have_egg.sql;
SOURCE procedures/get_all_events.sql;

SOURCE triggers/before_pokemon_insert.sql;