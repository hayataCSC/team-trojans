<?php
  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');
  /* Write the qeury string */
  $query = "SELECT p.id AS id, p.name AS nickname, p.species, p.level, p.is_female, t.name as trainer, t.id as trainer_id
    FROM pokemon p
    LEFT JOIN trainer t
    ON p.trainer_id = t.id;";
  /* Execute the query */
  $result = $conn->query($query);
  if (!$result) die(mysqli_error($conn) . "\n");
  /* Fetch all rows with each row as an associate array */
  $pokemons = $result->fetch_all(MYSQLI_ASSOC);
  /* Close the connection to the database */
  $conn->close();
?>

<?php require(__DIR__ . '/inc/header.php'); ?>

<h1>List of All Pokemons</h1>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Nickname</th>
      <th scope="col">Species</th>
      <th scope="col">Current Level</th>
      <th scope="col">Sex</th>
      <th scope="col">Trainer</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($pokemons as $pokemon) : ?>
      <tr>
        <th scope="row">
          <a href=<?php echo "pokemon.php/?id={$pokemon['id']}"; ?>>
            <?php echo $pokemon['nickname']; ?>
          </a>
        </th>
        <td><?php echo $pokemon['species']; ?></td>
        <td><?php echo $pokemon['level']; ?></td>
        <td><?php echo $pokemon['is_female'] ? 'F' : 'M'; ?></td>
        <td>
          <a href=<?php echo 'trainer.php/?id=' . $pokemon['trainer_id']; ?>>
            <?php echo $pokemon['trainer'] ?>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>
