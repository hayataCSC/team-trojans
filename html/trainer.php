<?php

  /* Check if the query parameter is set */
  if (!isset($_GET['id']))
    die('You need to specify the trainer\'s id in the query parameter');

  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');

  /* Get the entry for the request trainer from the trainers table */
  $query = 'SELECT * FROM trainer WHERE id = ?;';
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  /* Get the first row as an associative array */
  $trainer = $result->fetch_assoc();

  /* Get the entry for the request trainer from the trainers table */
  $query = 'SELECT name, species, level, is_female FROM pokemon WHERE trainer_id = ?;';
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $_GET['id']);
  if (!$stmt->execute()) die();
  $result = $stmt->get_result();
  /* Get each row as an associative array */
  $pokemons = $result->fetch_all(MYSQLI_ASSOC);

  /* Close the connection to the database */
  $conn->close();

?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<h1><?php echo $trainer['name'] ?></h1>
<p>Phone Number: <?php echo $trainer['phone'] ?></p>
<p>Email Address: <?php echo $trainer['email'] ?></p>
<div style="margin-top:3rem;">
  <?php if(count($pokemons) === 0): ?>
    <div class="alert alert-info" role="alert">
      <?php echo $trainer['name'] . ' does not have any pokemons'; ?>
    </div>
  <?php else: ?>
  <h3>Pokemons Under Care: </h3>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Nickname</th>
        <th scope="col">Spiecies</th>
        <th scope="col">Current Level</th>
        <th scope="col">Sex</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($pokemons as $pokemon): ?>
        <tr>
          <th scope="row"><?php echo $pokemon['name']; ?></th>
          <td><?php echo $pokemon['species']; ?></td>
          <td><?php echo $pokemon['level']; ?></td>
          <td><?php echo $pokemon['is_female'] ? 'M' : 'F'; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>