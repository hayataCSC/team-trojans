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

  $query = "SELECT name FROM species";
  $species = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
  /* Close the connection to the database */
  $conn->close();

?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<h1><?php echo $trainer['name'] ?></h1>
<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trainerModal">
  Edit contact information
</button>
<p>Phone Number: <?php echo $trainer['phone'] ?></p>
<p>Email Address: <?php echo $trainer['email'] ?></p>
<div style="margin-top:3rem;">
  <h3>Pokemons Under Care: </h3>
  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target='#pokemonModal' <?php count($pokemons) >= 2 ? 'disabled' : null ?>>Add pokemon</button>
  <?php if(count($pokemons) === 0): ?>
    <div class="alert alert-info" role="alert">
      <?php echo $trainer['name'] . ' does not have any pokemons'; ?>
    </div>
  <?php else: ?>
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

<div class="modal fade" id="trainerModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Contact Infomation</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="trainer-form" action="/poke_care/api/trainers.php" method="POST">
          <input type="hidden" name="query" value="PUT">
          <input type="hidden" name="id" value=<?php echo $trainer['id']; ?>>
          <div class="form-group">
            <label for="name">Full name</label>
            <input id="name" name="name" class="form-control" type="text" value="<?php echo $trainer['name']; ?>" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" class="form-control" type="tel" value="<?php echo $trainer['phone']; ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input id="emial" name="email" class="form-control" type="email" value="<?php  echo $trainer['email']; ?>" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="submit" form="trainer-form" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pokemonModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Pokemon</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="trainer-form" action="/poke_care/trainer.php" method="POST">
          <input type="hidden" name="query" value="POST">
          <input type="hidden" name="trainer_id" value="<? $trainer['id'] ?>">
          <div class="form-group">
            <label for="name">Nick name</label>
            <input id="name" name="name" class="form-control" type="text" placeholder="Nick name" required>
          </div>
          <div class="form-group">
            <label for="species">species</label>
            <input list="speciesList" id="species" name="species" class="form-control"/>
            <datalist id="speciesList">
              <?php foreach($species as $option): ?>
                <option value="<?php echo $option['name'] ; ?>">
              <?php endforeach; ?>
            </datalist>
          </div>
          <div class="form-group">
            <label for="gender">Current level</label>
            <input id="level" name="level" class="form-control" type="number" value="0" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="submit" form="trainer-form" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>