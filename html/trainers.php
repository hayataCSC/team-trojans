<?php

  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');
  /* Fetch the list of databases */
  $query = 'SELECT * FROM trainer;';
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
<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Phone</th>
      <th scope="col">Email</th>
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
        <th scope="row"><?php echo $trainer['phone'] ?></th>
        <th scope="row"><?php echo $trainer['email'] ?></th>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>