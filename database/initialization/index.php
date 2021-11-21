<?php

  $config = parse_ini_file(__DIR__ . '/../../mysql.ini');

  $dbname = 'pokemon';
  $conn = new mysqli(
    $config['mysqli.default_host'],
    $config['mysqli.default_user'],
    $config['mysqli.default_pw'],
    $dbname);

  /* Connect to the database */
  if ($conn->connect_errno) die($conn->connect_error . "\n");
  
  /* Import GenerateTrainer class from Trainer.php */
  require(__DIR__ . '/Trainer.php');
  /* Generate the array of trainers */
  $trainerGenerator = new TrainerGenerator();
  $trainers = $trainerGenerator->getTrainers();
  /* Insert each trainer to the trainer table */
  foreach ($trainers as $trainer) {
    $query = "INSERT INTO trainer(name, phone, email)
      VALUES (\"{$trainer->name}\", \"{$trainer->phone}\", \"{$trainer->email}\");";
    if (!$conn->query($query)) die($conn->error . "\n");
  }

  /* Import getTypes from Type.php */
  require(__DIR__ . '/Type.php');
  /* Get the array of pokemon types */
  $types = getTypes();
  /* Insert each type to the type table */
  foreach ($types as $type) {
    $query = "INSERT INTO type(name) VALUES (\"{$type}\");";
    if (!$conn->query($query)) die($conn->error . "\n");
  }

  /* Import getSpecies from Species.php */
  require(__DIR__ . '/Species.php');
  /* Get the array of species */
  $species = getSpecies();
  /* Insert each species to the species table */
  foreach ($species as $item) {
    insertSpecies($conn, $item['Name']);
    insertSpeciesType($conn, $item['Name'], $item['Type 1']);
    /* If there is a second type for the species, add another entry */
    if ($item['Type 2'] !== "") {
      insertSpeciesType($conn, $item['Name'], $item['Type 2']);
    }
  }
  /* Helper func for inserting species and type combinatioin into species_type table */
  function insertSpecies($conn, $name) {
    $query = "INSERT INTO species(name)
      VALUES (\"{$name}\");";
    if (!$conn->query($query)) die($conn->error . "\n");
  }
  /* Helper func for inserting species into the species table */
  function insertSpeciesType($conn, $name, $type) {
    $query = "INSERT INTO species_type(name, type)
      VALUES (\"{$name}\", \"{$type}\");";
    if (!$conn->query($query)) die($conn->error . "\n");
  }

  /* Import generatePokemons form Pokemon.php */
  require(__DIR__ . '/Pokemon.php');
  $pokemons = generatePokemons($species);

  /* Insert each species to the species table */
  foreach ($pokemons as $pokemon) {
    $is_female = $pokemon->is_female ? 'True' : 'False';

    $query = "INSERT INTO pokemon(name, species, trainer_id, level, is_female)
      VALUES (\"{$pokemon->name}\",
        \"{$pokemon->species}\",
        {$pokemon->trainer_id},
        {$pokemon->level},
        {$is_female})";

    if (!$conn->query($query)) die($conn->error . "\n");
  }
  
  /* Import getMoves from Move.php */
  require(__DIR__ . '/Move.php');
  $moves = getMoves();
  foreach ($moves as $move) {
    $query = "INSERT INTO move(name) VALUES (\"{$move['Name']}\")";
    if (!$conn->query($query)) die($conn->error);
  }
?>