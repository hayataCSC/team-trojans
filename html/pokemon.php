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
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  $pokemon = $result->fetch_all(MYSQLI_ASSOC)[0];

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
<h3><?php echo 'Sex' . $pokemon['is_female'] ? 'Female' : 'Male'; ?></h3>

<form action="/poke_care/api/moves.php" method="POST">
  <input type="hidden" name="pokemon_id" value="<?php echo $_GET['pokemon_id']; ?>">
  <div class="form-group">
    <label for="moves">Move Learned</label>
    <input list="movesList" id="move" name="move" class="form-control"/>
    <datalist id="movesList">
      <?php foreach($moves as $move): ?>
        <option value="<?php echo $move; ?>">
      <?php endforeach; ?>
    </datalist>
  </div>
  <button type="submit" name="query" value="PUT">Log new move</button>
</form>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>