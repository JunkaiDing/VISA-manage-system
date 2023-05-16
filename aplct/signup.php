<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Account Creation</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<header>
  <a href="index.php">Online Visa Application System</a> › Account Creation
</header>

<body>

  <div class="default">
    <h1>Please enter your account info</h1>
    <p class="error">* required field</p>
    <form method="POST" action="signupOK.php">
      <?php
      createForm("name", "* Name: ");
      echo ("<br>");
      createForm("gender", "* Gender: ", "F/M/X");
      echo ("<br>");
      createForm("birthDate", "* Date of Birth: ", "e.g. 29-AUG-2012");
      echo ("<br>");
      createForm("country", "* Country: ");
      echo ("<br>");
      createForm("passport", "* Passport: ");
      echo ("<br>");
      ?>
      <p>
        * How many accompanying family members do you have?
        <?php
        createRadioForm("0", "fm", "0", "setNoFM()", true);
        createRadioForm("1", "fm", "1", "setOneFM()");
        createRadioForm("2", "fm", "2", "setTwoFM()");
        ?>
      </p>

      <div class="fm1" style="display:none;">

        <?php
        echo ("Family Member 1<br>");
        createForm("fm1name", "* Name: ");
        echo (" ");
        createForm("fm1relation", "* Relationship: ");
        ?>
      </div>
      <div class="fm2" style="display:none;">
        <?php
        echo ("Family Member 2<br>");
        createForm("fm2name", "* Name: ");
        echo (" ");
        createForm("fm2relation", "* Relationship: ");
        ?>
      </div>
      <br />
      <p>

        <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
        <input type="hidden" id="createAccountRequest" name="createAccountRequest">
        
        <input type="submit" class="buttons" value="Create Account" name="signup" />
      </p>
    </form>
  </div>
</body>

<script>
  function setNoFM() {
    document.querySelector(".fm1").style.display = "none";
    document.querySelector(".fm2").style.display = "none";
  }
  function setOneFM() {
    document.querySelector(".fm1").style.display = "block";
    document.querySelector(".fm2").style.display = "none";
  }
  function setTwoFM() {
    document.querySelector(".fm1").style.display = "block";
    document.querySelector(".fm2").style.display = "block";
  }
</script>

<?php require_once("../footer.php") ?>

</html>