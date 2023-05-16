<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>New Application</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<header>
  <a href="index.php">Online Visa Application System</a> › New Application
</header>

<body>
  <div class="default">
    <h1 class="center">Please enter your application info</h1>
    <p class="error">* required field</p>
    <form method="POST" action="applyOK.php">

      <label for="choose">* Choose an embassy to process your application:</label>

      <!-- HTML <select> elements adapted from https://www.w3schools.com/tags/tag_select.asp -->
      <select name="eID" id="eID">

        <?php

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        connectToDB();
        $result = executeSQL("SELECT * FROM Embassies");
        while ($row = oci_fetch_array($result, OCI_BOTH)) {
          echo ("<option value='{$row[0]}'>{$row[1]}</option>");
        }
        disconnectFromDB();

        ?>

      </select>

      </br>
      <label for="aplctID">* Applicant ID:</label>
      <input type="text" id="aplctID" name="aplctID" /><br />
      <p>Choose an application type:</p>

      <?php
      createRadioForm("0", "type", "Tourist", "setTourtistApp()", true);
      createRadioForm("1", "type", "Student", "setStudentApp()");
      createRadioForm("2", "type", "Worker", "setWorkerApp()");
      ?>

      <div class="destination">
        <?php createForm("destination", "Destination: "); ?>
      </div>
      <div class="school" style="display:none;">
        <?php createForm("school", "School: "); ?>
      </div>
      <div class="company" style="display:none;">
        <?php createForm("company", "Company: "); ?>
      </div>
      <br />

      <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
      <input type="hidden" id="submitApplicationRequest" name="submitApplicationRequest">

      <input type="submit" class="buttons" value="Submit Application" name="apply" />
    </form>
    <br />
  </div>
</body>
<script>
  function setTourtistApp() {
    document.querySelector(".destination").style.display = "block";
    document.querySelector(".school").style.display = "none";
    document.querySelector(".company").style.display = "none";
  }
  function setStudentApp() {
    document.querySelector(".destination").style.display = "none";
    document.querySelector(".school").style.display = "block";
    document.querySelector(".company").style.display = "none";
  }
  function setWorkerApp() {
    document.querySelector(".destination").style.display = "none";
    document.querySelector(".school").style.display = "none";
    document.querySelector(".company").style.display = "block";
  }
</script>
<?php require_once("../footer.php") ?>

</html>