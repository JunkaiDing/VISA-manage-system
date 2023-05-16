<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Track Application</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<header>
  <a href="index.php">Online Visa Application System</a> › Track Application
</header>

<?php
// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
if (isset($_POST['track'])) {
  handlePOSTRequest();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function handlePOSTRequest() {
  connectToDB();
  if (array_key_exists('trackRequest', $_POST)) {
    track();
  }
  disconnectFromDB();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function track() {
  global $status;
  $tuples = array(array(":appID" => $_POST['appID']));
  $status = oci_fetch_array(executeBoundSQL("SELECT status FROM Applications WHERE appID = :appID", $tuples), OCI_BOTH)[0];
}
?>

<body>
  <div class="default">
    <h1>Check the status of your application</h1>
    <form method="POST">
      <?php
      echo ("<br>");
      createForm("appID", "* Application ID (required): ");
      ?>

      <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
      <input type="hidden" id="trackRequest" name="trackRequest">

      <input type="submit" class="buttons" value="Track" name="track" />
    </form>
    <?php
    if (!is_null($status)) {
      echo ("Your application status is: {$status}");
    }
    ?>
    <br>
    <br>
  </div>
</body>
<?php require_once("../footer.php") ?>

</html>