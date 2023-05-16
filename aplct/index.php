<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Apply for Visa - Start</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<header>
  <div>Online Visa Application System</div>
</header>

<body>
  <div class="default">
    <h1>Please choose what you would like to do</h1>
    <div>
      <?php
      createButton("bigButtons", "signup.php", "Create Account");
      echo " ";
      createButton("bigButtons", "apply.php", "New Application");
      echo " ";
      createButton("bigButtons", "track.php", "Track Application");
      echo " ";
      createButton("bigButtons", "withdraw.php", "Withdraw Application");
      ?>
    </div>
    <br>
  </div>
</body>

<?php require_once("../footer.php") ?>

</html>