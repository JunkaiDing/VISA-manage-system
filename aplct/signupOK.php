<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Account Created Sucessfully</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<header>
  <a href="index.php">Online Visa Application System</a> › <a href="signup.php">Account Creation</a> › Success
</header>

<?php

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
if (isset($_POST['signup'])) {
  handlePOSTRequest();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function handlePOSTRequest() {
  connectToDB();
  if (array_key_exists('createAccountRequest', $_POST)) {
    createAccount();
  }
  disconnectFromDB();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function createAccount() {

  $tuples = array(
    array(
      ":passport" => $_POST['passport'],
      ":name" => $_POST['name'],
      ":gender" => $_POST['gender'],
      ":birthDate" => $_POST['birthDate'],
      ":country" => $_POST['country'],
      ":aplctID" => 0
    )
  );

  // RETURNING clause apadted from https://stackoverflow.com/questions/39797986/what-exactly-does-returning-into-do-in-oracle-sql
  executeBoundSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES (:passport, :name, :gender, :birthDate, :country) RETURNING aplctID INTO :aplctID", $tuples);

  global $aplctID;
  $aplctID = $tuples[0][':aplctID'];

  if ($_POST['fm'] != 0) {
    if ($_POST['fm'] == 1) {
      $tuples = array(
        array(
          ":aplctID" => $aplctID,
          ":fmname" => $_POST['fm1name'],
          ":fmrelation" => $_POST['fm1relation']
        )
      );
    } else if ($_POST['fm'] == 2) {
      $tuples = array(
        array(
          ":aplctID" => $aplctID,
          ":fmname" => $_POST['fm1name'],
          ":fmrelation" => $_POST['fm1relation']
        ),
        array(
          ":aplctID" => $aplctID,
          ":fmname" => $_POST['fm2name'],
          ":fmrelation" => $_POST['fm2relation']
        ),
      );
    }
    executeBoundSQL("INSERT INTO FamilyMembers VALUES (:aplctID, :fmname, :fmrelation)", $tuples);
  }

}

?>

<body>
  <div class="default">
    <h1>Your account has been successfully created!</h1>
    <p>
      Your applicant number is <span class=error>
        <?= $aplctID ?>
      </span>. </br>
      Please write it down as you'll need it later.
    </p>
    <?php
    echo " ";
    createButton("bigButtons", "apply.php", "New Application");
    echo "</br>";
    echo "</br>";
    ?>
  </div>
</body>
<?php require_once("../footer.php") ?>

</html>