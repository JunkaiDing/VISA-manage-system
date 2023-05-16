<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Border Officer</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<header>
    <div>Border Management System</div>
</header>

<body>
    <div class="default">

        <h1>Welcome to Border Management System</h1>

        <?php

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        if (isset($_POST['project']) || isset($_POST['admit'])) {
            handlePOSTRequest();
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handlePOSTRequest() {
            connectToDB();
            if (array_key_exists('projectionRequest', $_POST)) {
                handleProjectionRequest();
            } else if (array_key_exists('admitRequest', $_POST)) {
                handleAdmitRequest();
            }
            disconnectFromDB();
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handleProjectionRequest() {

            // Adapted from https://stackoverflow.com/questions/25933138/implode-array-for-php-checkbox-in-form
            $attributesSelected = implode(", ", $_POST['attributesSelected']);

            createTable(executeSQL("SELECT {$attributesSelected} FROM Applicants"));
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handleAdmitRequest() {
            $tuples = array(
                array(
                    ":aplctID" => $_POST['aplctID'],
                    ":boID" => $_POST['boID'],
                    ":entryPoint" => $_POST['entryPoint']
                )
            );
            executeBoundSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (:aplctID, :boID, SYSDATE, :entryPoint)", $tuples);
        }

        ?>

        <h2>Info of all applicants</h2>

        <form method="POST" action="index.php">

            Please select attributes to include: </br>

            <?php
            $attributes = array("name", "passport", "gender", "birthdate", "country");
            
            foreach ($attributes as $attribute) {

                // Adapted from https://www.w3schools.com/tags/att_input_type_checkbox.asp
                // and https://www.hashbangcode.com/article/html-checkbox-php-array
                echo "<input type='checkbox' name='attributesSelected[]' value={$attribute}>";
                createLabel($attribute, $attribute);

            }
            ?>

            <br>

            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="projectionRequest" name="projectionRequest">

            <input type="submit" class = "buttons" name="project" value="Show">

        </form>

        <h2>Grant admission</h2>

        <form method="POST" action="index.php">

            <label for="boID">Border Officer ID:</label>
            <input type="text" id="boID" name="boID" placeholder="boID"> <br>

            Admission info:

            <input type="text" id="aplctID" name="aplctID" placeholder="Applicant ID">
            <input type="text" id="entryPoint" name="entryPoint" placeholder="Point of Entry">
            
            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="admitRequest" name="admitRequest">

            <input type="submit" class = "buttons" value="Admit" name="admit"></p>

        </form>

    </div>

</body>

<?php require_once("../footer.php") ?>

</html>