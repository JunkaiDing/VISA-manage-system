<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Application Submitted Sucessfully</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<header>
  <a href="index.php">Online Visa Application System</a> › <a href="apply.php">New Application</a> › Success
</header>

<?php

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
if (isset($_POST['apply'])) {
  handlePOSTRequest();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function handlePOSTRequest() {
  connectToDB();
  if (array_key_exists('submitApplicationRequest', $_POST)) {
    submitApplication();
  }
  disconnectFromDB();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function submitApplication() {

  $tuples = array(
    array(
      ":aplctID" => $_POST['aplctID'],
      ":eID" => $_POST['eID'],
      ":appID" => 0
    )
  );

  // SYSDATE function adapted from https://www.oracle.com/technical-resources/articles/fuecks-dates.html
  // RETURNING function adapted from https://stackoverflow.com/questions/39797986/what-exactly-does-returning-into-do-in-oracle-sql
  executeBoundSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (:aplctID, null, :eID, SYSDATE, 'Received') RETURNING appID INTO :appID", $tuples);

  global $appID;
  $appID = $tuples[0][':appID'];

  if ($_POST['type'] == 0) {
    $tuples = array(
      array(
        ":appID" => $appID,
        ":destination" => $_POST['destination'],
      )
    );
    executeBoundSQL("INSERT INTO TouristApps VALUES (:appID, :destination)", $tuples);
  } else if ($_POST['type'] == 1) {
    $tuples = array(
      array(
        ":appID" => $appID,
        ":school" => $_POST['school'],
      )
    );
    executeBoundSQL("INSERT INTO StudentApps VALUES (:appID, :school)", $tuples);
  } else if ($_POST['type'] == 2) {
    $tuples = array(
      array(
        ":appID" => $appID,
        ":company" => $_POST['company'],
      )
    );
    executeBoundSQL("INSERT INTO WorkerApps VALUES (:appID, :company)", $tuples);
  }

}

?>

<body>
  <div class="default">
    <h1>Your application has been successfully received!</h1>
    <p>Your application ID is:<span class=error>
        <?= $appID ?>
      </span>. </br></p>
    <?php
      createButton("bigButtons", "track.php", "Track Application");
      echo "</br>";
      echo "</br>";
      ?>
  </div>

</body>
<?php require_once("../footer.php") ?>

</html>