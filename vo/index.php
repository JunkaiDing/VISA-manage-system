<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Visa Officer</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<header>
  <div>Visa Decision Making System</div>
</header>

<body>

  <div class="default">

    <h1>Welcome to Visa Decision Making System</h1>

    <?php

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    if (isset($_POST['signIn']) || isset($_POST['changeStatus'])) {
      handlePOSTRequest();
    }

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    function handlePOSTRequest() {
      connectToDB();
      if (array_key_exists('signInRequest', $_POST)) {
        signIn();
      } else if (array_key_exists('changeStatusRequest', $_POST)) {
        changeStatus();
      }
      disconnectFromDB();
    }

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    function signIn() {
      echo "New Applications";
      $tuples = array(array(":voID" => $_POST['voID']));
      createTable(executeBoundSQL("SELECT A.appID, P.aplctID, P.name, P.passport, P.gender, P.birthDate, P.country, A.receiveDate FROM Applicants P, Applications A WHERE P.aplctID = A.aplctID AND A.status = 'Received' AND A.voID = :voID", $tuples));
    }

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    function changeStatus() {
      $tuples = array(
        array(
          ":appID" => $_POST['appID'],
          ":status" => $_POST['status']
        )
      );
      executeBoundSQL("UPDATE Applications SET status = :status WHERE APPID = :appID", $tuples);
    }

    ?>

    </br>

    <form method="POST">

      <?php createForm("voID", "Visa Officer ID: "); ?>

      <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
      <input type="hidden" id="signInRequest" name="signInRequest">

      <input type="submit" class="buttons" value="Sign In" name="signIn">
    </form>

    <form method="POST">

      <?php
      echo ("<br>");
      createForm("appID", "Application ID and new status: ", "ID");
      ?>

      <input type="text" id="status" name="status" placeholder="status">

      <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
      <input type="hidden" id="changeStatusRequest" name="changeStatusRequest">

      <input type="submit" class="buttons" value="Submit" name="changeStatus">
    </form>

    </br>

  </div>

</body>

<?php require_once("../footer.php") ?>

</html>