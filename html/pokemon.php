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
    /* Don't use "pokemon" as a variable name here (instead use $p).
     * If you use "pokemon", it collides with $pokemon that refers to the requested pokemon */
    foreach($pokemons as $p) {
      if ((int)$p['id'] === $partnerId) {
        $partnerName = $p['name'];
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

  /* Check if the pokemon can level up */
  $query = 'SELECT can_learn_move(?)';
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('i',  $_GET['id']);
  if (!$stmt->execute()) die($conn->error);
  $result = $stmt->get_result();
  $can_learn_move = $result->fetch_all(MYSQLI_NUM)[0][0];

  /* Get all events for the pokemon */
  $query = 'CALL get_all_events(?)';
  $stmt = $conn->prepare($query);
  if (!$stmt) die($conn->error);
  $stmt->bind_param('i', $_GET['id']);
  if (!$stmt->execute()) die($conn->error);
  $result = $stmt->get_result();
  $events = $result->fetch_all(MYSQLI_ASSOC);

  /* Close the connection to the database */
  $conn->close();

?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<div class="container p-0">
  <div class="row justify-content-between">
    <div class="col-sm-auto">
      <h1><?php echo $pokemon['name'] ?></h1>
      <h3><?php echo "Species: {$pokemon['species']}"; ?></h3>
      <h3><?php echo "Current level: {$pokemon['level']}"; ?></h3>
      <h3><?php echo $pokemon['is_female'] ? 'Female' : 'Male'; ?></h3>
    </div>
    <div class="col-sm-auto">
      <div class="btn-group-vertical">
        <button
          type="submit"
          form="level-up-form"
          class="btn btn-outline-primary btn-lg"
          name="operation"
          value="level_up"
        >
          Increment pokemon's level
        </button>
        <button
          class="btn btn-outline-primary btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#moveModal"
          <?php echo $can_learn_move ? '' : 'disabled'; ?>
        >
            Log new move
        </button>
        <button
          class="btn btn-outline-primary btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#friendModal"
        >
          Add friend
        </button>
        <?php if (isset($partnerName)): ?>
          <button
            type="submit"
            form="egg-form"
            class="btn btn-outline-primary btn-lg"
            name="operation"
            value="have_egg"
          >
            <?php echo "Have an egg with $partnerName"; ?>
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if(isset($partnerName)): ?>
  <!-- If there is a partner to have eggs with, put the hidden form --->
  <form id="egg-form" action="/poke_care/api/eggs.php" method="POST">
    <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
  </form>
<?php else: ?>
  <!-- If there is no partner with have eggs with, display the message --->
  <div class="alert alert-info">
    <?php echo "{$pokemon['name']} does not have a partner to have eggs with"; ?>
  </div>
<?php endif; ?>

<hr/>

<h3><?php echo "{$pokemon['name']}'s friends"; ?></h3>
<?php if (count($friends) === 0): ?>
  <div class="alert alert-info">
    <?php echo "{$pokemon['name']} doesn't have any friend" ?>
  </div>
<?php else: ?>
  <ul class="list-group">
    <?php foreach($friends as $friend): ?>
      <li class="list-group-item"><?php echo $friend['name'] ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<hr/>

<h3><?php echo "{$pokemon['name']}'s event history"; ?></h3>
<?php foreach($events as $event): ?>
  <div class="card p-2 mb-2">
    <p class="mb-1"><?php echo '@' . $event['happened_at']; ?></p>
    <p class="m-0">
      <?php
        if (isset($event['partner_name'])):
          echo "Had an egg with {$event['partner_name']}";
        elseif (isset($event['level_reached'])):
          $new_level = (int)$event['level_reached'];
          echo 'Leveled up from ' . ($new_level - 1) . ' to ' . $new_level;
        else:
          echo "Learned {$event['move_name']}";
        endif;
      ?>
    </p>
  </div>
<?php endforeach; ?>

<form id="level-up-form" action="/poke_care/api/pokemon.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['id']; ?>">
</form>

<div class="modal fade" id="moveModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Move Learned</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="move-form" action="/poke_care/api/moves.php" method="POST">
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
      </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="move-form" class="btn btn-primary" name="query" value="PUT">Log new move</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="friendModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Move Learned</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="friend-form" action="/poke_care/api/pokemon.php" method="POST">
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
      </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="friend-form" class="btn btn-primary" name="operation" value="befriend">Add Friend</button>
      </div>
    </div>
  </div>
</div>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>

