DELIMITER $$

CREATE PROCEDURE get_all_events
(
  pokemon_id INT
)
BEGIN
  SELECT ev.happened_at, l.level_reached, m.move_name, p.name AS partner_name
  FROM event ev
  LEFT JOIN egg eg
    ON ev.id = eg.event_id
  LEFT JOIN level_up l
    ON ev.id = l.event_id
  LEFT JOIN move_learned m
    ON ev.id = m.event_id
  LEFT JOIN pokemon p
    ON eg.partner_id = p.id
  WHERE ev.pokemon_id = pokemon_id
  /* The most recent event should come first */
  ORDER BY ev.happened_at DESC;
END $$

DELIMITER ;