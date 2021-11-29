<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css">
</head>
<body>
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
</body>
</html>

<?php require(__DIR__ . '/inc/header.php'); ?>

<h1>Poke Care Stats</h1>

<div class="container-fluid"> 
  <div class="row">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-body" id="Row1">
          <h5 class="card-title">Total Moves learned</h5>
          <p class="card-text" style="font-size:6em;text-align:center;margin-top:-20px"><?php echo $totalmoves?></p>
        </div>
      </div>
    </div>
        <div class="col-sm-4">
      <div class="card">
        <div class="card-body" id="Row1">
          <h5 class="card-title">Total level ups</h5>
          <p class="card-text" style="font-size:6em;text-align:center;margin-top:-20px"><?php echo $levelups?><i class="fa fa-arrow-down" aria-hidden="true"></i></p>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-body" id="Row1">
          <h5 class="card-title">Total Eggs Conceived</h5>
          <p class="card-text" style="font-size:6em;text-align:center;margin-top:-20px"><?php echo $totaleggs?></p>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- Import the footer --->
<?php require(__DIR__ . '/inc/footer.php'); ?>