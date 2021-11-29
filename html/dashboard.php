<?php
  /* Configure the error output level */
  require(__DIR__ . '/config/error.php');
  /* Import the connect function from db.php */
  require(__DIR__ . '/config/db.php');
  /* Connect to the pokemon database */
  $conn = connect('pokemon');
  /* Write the qeury string */
  $query = "SELECT total_levelups()";
  /* Execute the query */
  $result = $conn->query($query);
  if (!$result) die(mysqli_error($conn) . "\n");
  /* Fetch all rows with each row as an associate array */
  $levelups = $result->fetch_all(MYSQLI_NUM)[0][0];
/* Write the qeury string */
  $query = "SELECT total_moves()";
  /* Execute the query */
  $result = $conn->query($query);
  if (!$result) die(mysqli_error($conn) . "\n");
  /* Fetch all rows with each row as an associate array */
  $totalmoves = $result->fetch_all(MYSQLI_NUM)[0][0];
  /* Write the qeury string */
  $query = "SELECT total_eggs()";
  /* Execute the query */
  $result = $conn->query($query);
  if (!$result) die(mysqli_error($conn) . "\n");
  /* Fetch all rows with each row as an associate array */
  $totaleggs = $result->fetch_all(MYSQLI_NUM)[0][0];
  /* Close the connection to the database */
  $conn->close();
?>

<?php require(__DIR__ . '/inc/header.php'); ?>

<h1>Poke Care Stats</h1>

<h2>Total level ups at the Day Care: <?php echo $levelups?></h2>

<h2>Total Moves Learned at the Day Care: <?php echo $totalmoves?></h2>

<h2>Total Eggs conceived at the Day Care: <?php echo $totaleggs?></h2>





<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>