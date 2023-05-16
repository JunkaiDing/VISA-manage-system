<?php require_once("../functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Embassy</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<header>
    <div>Embassy Management System</div>
</header>

<body>

    <div class="default">

        <h1>Welcome to Embassy Management System</h1>

        <?php

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        if (isset($_POST['checkInfo'])) {
            if (empty($_POST['table'])) {
                echo "<span class = 'error'>Please select a table.</span>";
            } else {
                if (empty($_POST['embassyID'])) {
                    echo "<span class = 'error'>Please enter an embassy ID.</span>";
                } else {
                    handlePOSTRequest();
                }
            }
        } else if (isset($_POST['signin']) || isset($_POST['assign'])) {
            handlePOSTRequest();
        }


        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handlePOSTRequest() {
            connectToDB();
            if (array_key_exists('checkInfoRequest', $_POST)) {
                handleCheckInfo();
            } else if (array_key_exists('assignApp', $_POST)) {
                handleAssignApp();
            } else if (array_key_exists('signIn', $_POST)) {
                handleSignIn();
            }
            disconnectFromDB();
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt    
        function handleCheckInfo() {
            $attribute = $_POST['attribute'];
            $table = $_POST['table'];
            $tuples = array(array(":eID" => $_POST['embassyID']));
            createTable(executeBoundSQL("SELECT {$attribute} FROM {$table} WHERE eID = :eID", $tuples));
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handleSignIn() {
            $tuples = array(array(":eID" => $_POST['eID']));
            echo "<span class = 'error'>Unassigned Applications</span>";
            createTable(executeBoundSQL("SELECT appID, aplctID, voID, receiveDate, status FROM Applications WHERE eID = :eID AND voID IS NULL", $tuples));
            echo "<span class = 'error'>Available Visa Officers</span>";
            createTable(executeBoundSQL("SELECT voID, name FROM VisaOfficers WHERE eID = :eID", $tuples));
        }

        // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
        function handleAssignApp() {
            $tuples = array(
                array(
                    ":appID" => $_POST['appID'],
                    ":voID" => $_POST['voID']
                )
            );
            executeBoundSQL("UPDATE Applications SET voID = :voID WHERE appID = :appID", $tuples);
        }

        ?>

        <h2>Applications/VO info at a glance:</h2>

        <form action="index.php" method="POST">

            <label for="table">Category: </label>

            <!-- HTML <select> elements adapted from https://www.w3schools.com/tags/tag_select.asp -->
            <select name="table" id="table" onchange="showAttributes()">
                <option value="" selected>Select</option>
                <option value="Applications">Applications</option>
                <option value="VisaOfficers">Visa Officers</option>
            </select>

            <label for="attribute">Info: </label>

            <!-- HTML <select> elements adapted from https://www.w3schools.com/tags/tag_select.asp -->
            <select name="attribute" id="attribute"></select>

            </br>

            <label for="embassyID">Embassy ID: </label>
            <input name="embassyID" id="embassyID"></input>

            <script>

                function showAttributes() {

                    // selectedIndex property adapted from https://developer.mozilla.org/en-US/docs/Web/API/HTMLSelectElement/selectedIndex
                    // HTML <select> elements adapted from https://www.w3schools.com/tags/tag_select.asp
                    if (document.getElementById('table').selectedIndex == 1) {
                        document.getElementById('attribute').innerHTML = '';
                        document.getElementById('attribute').innerHTML += "<option value='appID'>appID</option>";
                        document.getElementById('attribute').innerHTML += "<option value='aplctID'>aplctID</option>";
                        document.getElementById('attribute').innerHTML += "<option value='voID'>voID</option>";
                        document.getElementById('attribute').innerHTML += "<option value='receiveDate'>Receive Date</option>";
                        document.getElementById('attribute').innerHTML += "<option value='status'>status</option>";
                    } else if (document.getElementById('table').selectedIndex == 2) {
                        document.getElementById('attribute').innerHTML = '';
                        document.getElementById('attribute').innerHTML += "<option value='voID'>voID</option>";
                        document.getElementById('attribute').innerHTML += "<option value='name'>name</option>";
                    }

                }

            </script>

            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="checkInfoRequest" name="checkInfoRequest">

            <input type="submit" class = "buttons" name="checkInfo" value="Show">

        </form>

        <h2>View tasks:</h2>
        <form method="POST" action="index.php">

            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="signIn" name="signIn">

            Embassy ID: <input type='number' name="eID">
            <input type="submit" class = "buttons" value="Sign In" name="signin"></p>

        </form>

        <h2>Assign VO:</h2>
        <form method="POST" action="index.php">

            <!-- Hidden input adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
            <input type="hidden" id="assign" name="assignApp">

            Application ID: <input type="text" name="appID">
            Visa Officer ID: <input type="text" name="voID">

            <input type="submit" class = "buttons" value="Assign" name="assign"></p>

        </form>

    </div>

</body>

<?php require_once("../footer.php") ?>

</html>