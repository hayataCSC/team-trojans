<?php

  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');

  /* Fetch the list of databases */
  $query = 'SELECT t.id as id, t.name, t.phone, t.email, count(p.id) as pokemon_num
  FROM trainer t
  LEFT JOIN pokemon p
    ON p.trainer_id = t.id
  GROUP BY t.id
  ORDER BY t.name ASC;';
  $result = $conn->query($query);
  if (!$result) die(mysqli_error($conn));
  /* Get each row as an associative array */
  $trainers = $result->fetch_all(MYSQLI_ASSOC);
  /* Close the connection to the database */
  $conn->close();

?>

<!-- Import the header --->
<?php require(__DIR__ . '/inc/header.php'); ?>

<h1>List of All Trainers</h1>
<small style="display:block;margin-bottom:2rem;"><?php echo 'Number of trainers: ' . count($trainers); ?></small>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Add trainer
</button>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Phone</th>
      <th scope="col">Email</th>
      <th></ht>
    </tr>
  </thead>
  <tbody>
    <?php foreach($trainers as $trainer) : ?>
      <tr>
        <th scope="row">
          <a href=<?php echo 'trainer.php/?id=' . $trainer['id']; ?>>
            <?php echo $trainer['name'] ?>
          </a>
        </th>
        <td><?php echo $trainer['phone'] ?></td>
        <td><?php echo $trainer['email'] ?></td>
        <td>
          <form action="/api/trainers.php" method="POST">
            <input type="hidden" name="id" value=<?php echo $trainer['id'] ?>>
            <input class="<?php echo (int)$trainer['pokemon_num'] === 0 ? 'btn btn-outline-danger' : 'btn btn-outline-secondary'; ?>"
            type="submit"
            name="query"
            value="DELETE"
            <?php echo (int)$trainer['pokemon_num'] === 0 ? null : 'disabled'; ?>>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="modal fade" id="exampleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Trainer</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="trainer-form" action="/poke_care/api/trainers.php" method="POST">
          <input type="hidden" name="query" value="POST">
          <div class="form-group">
            <label for="name">Full name</label>
            <input id="name" name="name" class="form-control" type="text" placeholder="Name" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" class="form-control" type="tel" placeholder="Phone" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input id="emial" name="email" class="form-control" type="email" placeholder="Email" required>
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