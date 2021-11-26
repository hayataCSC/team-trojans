<?php
  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');

  /* Check if the query parameter is set */
  if (!isset($_GET['id']))
    die('You need to specify the pokemon\'s id in the query parameter');

  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');

  /* Get the requested pokemon */
  $query = 'SELECT name, species, level, is_female FROM pokemon WHERE id = ?;';
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  $pokemon = $result->fetch_all(MYSQLI_ASSOC)[0];

  /* Get all pokemons */
  $query = 'SELECT * FROM pokemon';
  $result = $conn->query($query);
  if (!$result) die($conn->error);
  $pokemons = $result->fetch_all(MYSQLI_ASSOC);

  /* Get friends of the pokemon */
  $query = 'CALL get_friends(?)';
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('i', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  $friends = $result->fetch_all(MYSQLI_NUM);
  /* Free result set after calling stored procedure
   * (See https://stackoverflow.com/questions/14554517/php-commands-out-of-sync-error) */
  $result->close();
  $conn->next_result();
  /* Convert 2d array to 1d array */
  $friendIds = array_map(function($friend) { return $friend[0]; }, $friends);
  /* Filter friends from the pokemons */
  $friends = array_filter(
    $pokemons,
    function($pokemon) use ($friendIds) {
      return in_array($pokemon['id'], $friendIds) && $pokemon['id'] !== $_GET['id'];
    }
  );

  /* Get potential friends from the pokemons (filter out pokemons who are already friends)
   * By using "use", the callback has access to the friendIds array that exists in the outerscope */
  $potentialFriends =
    array_filter(
      $pokemons,
      function($pokemon) use ($friendIds) {
        return !in_array($pokemon['id'], $friendIds) && $pokemon['id'] !== $_GET['id'];
      }
    );

  /* Get the id of the partner */
  $query = "SELECT get_partner(?)";
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('i', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  $partnerId = $result->fetch_all()[0][0];
  /* Free result set after calling stored function
   * (See https://stackoverflow.com/questions/14554517/php-commands-out-of-sync-error) */
  $result->close();

  /* Get the name of the partner */
  $partnerName;
  if (!isset($partnerId)) $partnerName = null;
  else {
    foreach($pokemons as $pokemon) {
      if ((int)$pokemon['id'] === $partnerId) {
        $partnerName = $pokemon['name'];
        break;
      }
    }
  }
  
  /* Get all pokemon moves */
  $query = 'SELECT * FROM move';
  $result = $conn->query($query);
  if (!$result) die($conn->error);
  $moves = $result->fetch_all(MYSQLI_NUM);
  /* Get only move names */
  $moves = array_map(function($move) { return $move[0]; }, $moves);

  /* Close the connection to the database */
  $conn->close();

?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<h1><?php echo $pokemon['name'] ?></h1>
<h3><?php echo "Species: {$pokemon['species']}"; ?></h3>
<h3><?php echo "Current level: {$pokemon['level']}"; ?></h3>
<h3><?php echo $pokemon['is_female'] ? 'Female' : 'Male'; ?></h3>

<h3><?php echo "{$pokemon['name']}'s friends"; ?></h3>
<ul class="list-group">
  <?php foreach($friends as $friend): ?>
    <li class="list-group-item"><?php echo $friend['name'] ?></li>
  <?php endforeach; ?>
</ul>

<form action="/poke_care/api/moves.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
  <div class="form-group">
    <label for="moves">Move Learned</label>
    <input list="movesList" id="moves" name="move" class="form-control"/>
    <datalist id="movesList">
      <?php foreach($moves as $move): ?>
        <option value="<?php echo $move; ?>">
      <?php endforeach; ?>
    </datalist>
  </div>
  <button type="submit" class="btn btn-primary" name="query" value="PUT">Log new move</button>
</form>

<form action="/poke_care/api/pokemon.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
  <button type="submit" class="btn btn-primary" name="operation" value="level_up">Increment pokemon's level</button>
</form>

<form action="/poke_care/api/pokemon.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
  <div class="form-group">
    <label for="pokemons">Befriend with</label>
    <input list="pokemonList" id="pokemons" name="new_friend_id" class="form-control"/>
    <datalist id="pokemonList">
      <?php foreach($potentialFriends as $potentialFriend): ?>
        <option value="<?php echo $potentialFriend['id']; ?>"><?php echo $potentialFriend['name']; ?></option>
      <?php endforeach; ?>
    </datalist>
  </div>
  <button type="submit" class="btn btn-primary" name="operation" value="befriend">Add Friend</button>
</form>

<form action="/poke_care/api/eggs.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
  <button type="submit" class="btn btn-primary" name="operation" value="have_egg">
    <?php echo "Have an egg with $partnerName"; ?>
  </button>
</form>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>