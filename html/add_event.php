<?php
  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Get connect function */
  require(__DIR__ . '/config/db.php');
  /* Connect to the database */
  $conn = connect();
  /* Get all pokemon moves */
  $query = 'SELECT * FROM move';
  $result = $conn->query($query);
  if (!$result) die($conn->error);
  $moves = $result->fetch_all(MYSQLI_NUM);
  /* Get only move names */
  $moves = array_map(function($move) { return $move[0]; }, $moves);
?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMoveModal">Add Move Learned</button>

<div class="modal fade" id="addMoveModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Move Learned</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/poke_care/api/moves.php" method="POST">
          <input type="hidden" name="pokemon_id" value="2">
          <input type="hidden" name="query" value="POST">
          <div class="form-group">
            <label for="moves">Move Learned</label>
            <input list="movesList" id="move" name="move" class="form-control"/>
            <datalist id="movesList">
              <?php foreach($moves as $move): ?>
                <option value="<?php echo $move; ?>">
              <?php endforeach; ?>
            </datalist>
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