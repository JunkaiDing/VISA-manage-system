<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Withdraw Application</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<header>
    <a href="index.php">Online Visa Application System</a> › Withdraw Application
</header>

<?php
// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
if (isset($_POST['withdraw'])) {
    handlePOSTRequest();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function handlePOSTRequest() {
    connectToDB();
    if (array_key_exists('withdrawRequest', $_POST)) {
        withdraw();
    }
    disconnectFromDB();
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function withdraw() {
    global $withdrew;
    $withdrew = false;
    $tuples = array(array(":appID" => $_POST['appID']));
    executeBoundSQL("DELETE FROM Applications WHERE appID = :appID", $tuples);
    $withdrew = true;
}
?>

<body>
    <div class="default">
        <h1>Withdraw your application</h1>
        <form method="POST" action="withdraw.php">
            <?php
            echo ("<br>");
            createForm("appID", "* Application ID (required): ");
            ?>

            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="withdrawRequest" name="withdrawRequest">

            <input type="submit" class="buttons" value="Withdraw" name="withdraw" />
        </form>
        <?php
        if ($withdrew) {
            echo ("Your application has been withdrawn. Any visa issued will also be canceled.");
        }
        ?>
        <br>
        <br>
    </div>
</body>

<?php require_once("../footer.php") ?>

</html>