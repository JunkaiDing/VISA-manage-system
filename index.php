<?php require_once("functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Visa Info Management System</title>
  <link rel="stylesheet" href="style.css" />
</head>

<header>
  <div>Visa Info Management System</div>
</header>

<body>
  <div class="default">
    <h1>Welcome to Visa Info Management System</h1>

    <?php
    createButton("bigButtons", "aplct/", "I'm an applicant");
    echo " ";
    createButton("bigButtons", "vo/", "I'm a visa officer");
    echo " ";
    createButton("bigButtons", "bo/", "I'm a border officer");
    echo " ";
    createButton("bigButtons", "e/", "I'm an embassy admin");
    echo "</br>";
    echo "</br>";
    ?>

    Buttons below are for DEMO purposes:

    <!-- Hidden inputs Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt -->
    <form method="POST">
      <input type="hidden" id="resetDBRequest" name="resetDBRequest">
      <input type="submit" class="buttons" value="Reset DB" name="reset">
    </form>
    <form method="POST">
      <input type="hidden" id="printTablesRequest" name="printTablesRequest">
      <input type="submit" class="buttons" value="Print Tables" name="print">
    </form>
    <form method="POST">
      <input type="hidden" id="groupByRequest" name="groupByRequest">
      <input type="submit" class="buttons" value="Group By" name="groupByDemo">
    </form>
    <form method="POST">
      <input type="hidden" id="havingRequest" name="havingRequest">
      <input type="submit" class="buttons" value="Having" name="havingDemo">
    </form>
    <form method="POST">
      <input type="hidden" id="nestedRequest" name="nestedRequest">
      <input type="submit" class="buttons" value="Nested" name="nestedDemo">
    </form>
    <form method="POST">
      <input type="hidden" id="divisionRequest" name="divisionRequest">
      <input type="submit" class="buttons" value="Division" name="divisionDemo">
    </form>

    <?php

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    if (isset($_POST['reset']) || isset($_POST['print']) || isset($_POST['groupByDemo']) || isset($_POST['havingDemo']) || isset($_POST['nestedDemo']) || isset($_POST['divisionDemo'])) {
      handlePOSTRequest();
    }

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    function handlePOSTRequest() {
      connectToDB();
      if (array_key_exists('resetDBRequest', $_POST)) {
        resetDB();
      } else if (array_key_exists('printTablesRequest', $_POST)) {
        printTables();
      } else if (array_key_exists('groupByRequest', $_POST)) {
        groupBy();
      } else if (array_key_exists('havingRequest', $_POST)) {
        having();
      } else if (array_key_exists('nestedRequest', $_POST)) {
        nested();
      } else if (array_key_exists('divisionRequest', $_POST)) {
        division();
      }
      disconnectFromDB();
    }

    // Aggregation with Group By: find the number of applicants for each country
    function groupBy() {
      echo "Aggregation with Group By: find the number of applicants for each country";
      createTable(executeSQL("SELECT country, COUNT(*) FROM Applicants GROUP BY country"));
    }

    // Aggregation with Having: Find the number of admissions for each entry point with at least 2 admissions
    function having() {
      echo "Aggregation with Having: </br>";
      echo "Find the number of admissions for each entry point with at least 2 admissions";
      createTable(executeSQL("SELECT entryPoint, COUNT(*) FROM Admissions GROUP BY entryPoint HAVING COUNT(*) >= 2"));
    }

    // Nested Aggregation with Group By: find embassies whose sum of visa officer experiences is the maximum over all embassies 
    function nested() {
      echo "Nested Aggregation with Group By: </br>";
      echo "find embassies whose sum of visa officer experiences is the maximum over all embassies";
      createTable(executeSQL("SELECT V.eID, SUM(V.experience) FROM VisaOfficers V GROUP BY V.eID HAVING SUM(V.experience) >= ALL (SELECT SUM(V2.experience) FROM VisaOfficers V2 GROUP BY V2.eID)"));
    }

    // Division: find all visa officers who have issued all types of visas
    function division() {
      echo "Division: find all visa officers who have issued all types of visas";
      createTable(executeSQL("SELECT voID, name FROM VisaOfficers O WHERE NOT EXISTS ((SELECT T.type FROM VisaTypes T) MINUS (SELECT V.type FROM Visas V WHERE O.voID = V.voID))"));
    }

    // Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
    function resetDB() {
      /*
       * DO NOT CHANGE ORDERS
       */
      executeSQL("DROP TABLE Admissions");
      executeSQL("DROP TABLE Visas");
      executeSQL("DROP TABLE TouristApps");
      executeSQL("DROP TABLE StudentApps");
      executeSQL("DROP TABLE WorkerApps");
      executeSQL("DROP TABLE Applications");
      executeSQL("DROP TABLE FamilyMembers");
      executeSQL("DROP TABLE Applicants");
      executeSQL("DROP TABLE VisaOfficers");
      executeSQL("DROP TABLE VisaTypes");
      executeSQL("DROP TABLE BorderOfficers");
      executeSQL("DROP TABLE AdmissionEntryPoints");
      executeSQL("DROP TABLE Embassies");

      executeSQL("CREATE TABLE Embassies (
        eID INTEGER,
        country CHAR(20) UNIQUE,
        PRIMARY KEY (eID)
      )");

      // Auto-incrementing aplctID adapted from https://www.oracletutorial.com/oracle-basics/oracle-identity-column/
      executeSQL("CREATE TABLE Applicants (
        aplctID INTEGER GENERATED ALWAYS AS IDENTITY,
        passport CHAR(9) UNIQUE,
        name CHAR(20),
        gender CHAR(1),
        birthDate DATE,
        country CHAR(20),
        PRIMARY KEY (aplctID)
      )");

      executeSQL("CREATE TABLE FamilyMembers (
        aplctID INTEGER,
        name CHAR(20),
        relationship CHAR(20),
        PRIMARY KEY (aplctID, name),
        FOREIGN KEY (aplctID)
        REFERENCES Applicants
        ON DELETE CASCADE
      )");

      executeSQL("CREATE TABLE VisaTypes (
        type CHAR(2),
        length INTEGER,
        PRIMARY KEY (type)
      )");

      executeSQL("CREATE TABLE VisaOfficers (
        voID INTEGER,
        eID INTEGER NOT NULL,
        name CHAR(20),
        experience INTEGER,
        PRIMARY KEY (voID),
        FOREIGN KEY (eID)
        REFERENCES Embassies
      )");

      executeSQL("CREATE TABLE AdmissionEntryPoints (
        entryPoint CHAR(3),
        entryType CHAR(5),
        PRIMARY KEY (entryPoint)
      )");

      executeSQL("CREATE TABLE BorderOfficers (
        boID INTEGER,
        name CHAR(20),
        PRIMARY KEY (boID)
      )");

      // Auto-incrementing aplctID adapted from https://www.oracletutorial.com/oracle-basics/oracle-identity-column/
      executeSQL("CREATE TABLE Applications (
        appID INTEGER GENERATED ALWAYS AS IDENTITY,
        aplctID INTEGER NOT NULL,
        voID INTEGER,
        eID INTEGER,
        receiveDate DATE,
        status CHAR(10),
        PRIMARY KEY (appID),
        FOREIGN KEY (aplctID)
        REFERENCES Applicants,
        FOREIGN KEY (voID)
        REFERENCES VisaOfficers,
        FOREIGN KEY (eID)
        REFERENCES Embassies
      )");

      executeSQL("CREATE TABLE TouristApps (
        appID INTEGER,
        destination CHAR(20),
        PRIMARY KEY (appID),
        FOREIGN KEY (appID)
        REFERENCES Applications
        ON DELETE CASCADE
      )");

      executeSQL("CREATE TABLE StudentApps (
        appID INTEGER,
        school CHAR(20),
        PRIMARY KEY (appID),
        FOREIGN KEY (appID)
        REFERENCES Applications
        ON DELETE CASCADE
      )");

      executeSQL("CREATE TABLE WorkerApps (
        appID INTEGER,
        company CHAR(20),
        PRIMARY KEY (appID),
        FOREIGN KEY (appID)
        REFERENCES Applications
        ON DELETE CASCADE
      )");

      executeSQL("CREATE TABLE Visas (
        vID INTEGER,
        appID INTEGER UNIQUE NOT NULL, 
        aplctID INTEGER NOT NULL,
        voID  INTEGER NOT NULL,
        issueDate DATE,
        type CHAR(2),
        PRIMARY KEY (vID),
        FOREIGN KEY (appID)
        REFERENCES Applications
        ON DELETE CASCADE,
        FOREIGN KEY (aplctID)
        REFERENCES Applicants,
        FOREIGN KEY (voID)
        REFERENCES VisaOfficers,
        FOREIGN KEY (type)
        REFERENCES VisaTypes
      )");

      // Auto-incrementing aplctID adapted from https://www.oracletutorial.com/oracle-basics/oracle-identity-column/
      executeSQL("CREATE TABLE Admissions (
        admID INTEGER GENERATED ALWAYS AS IDENTITY,
        aplctID INTEGER NOT NULL,
        boID INTEGER NOT NULL,
        grantDate DATE,
        entryPoint CHAR(3),
        PRIMARY KEY (admID),
        FOREIGN KEY (aplctID)
        REFERENCES Applicants,
        FOREIGN KEY (boID)
        REFERENCES BorderOfficers,
        FOREIGN KEY (entryPoint)
        REFERENCES AdmissionEntryPoints
      )");

      executeSQL("INSERT INTO Embassies VALUES (1, 'Spain')");
      executeSQL("INSERT INTO Embassies VALUES (2, 'China')");
      executeSQL("INSERT INTO Embassies VALUES (3, 'Australia')");
      executeSQL("INSERT INTO Embassies VALUES (4, 'USA')");
      executeSQL("INSERT INTO Embassies VALUES (5, 'Germany')");
      executeSQL("INSERT INTO Embassies VALUES (6, 'UK')");

      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000001', 'John', 'M', '01-FEB-2000', 'Spain')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000002', 'Xiaohong', 'X', '02-APR-1997', 'China')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000003', 'Julia', 'F', '16-DEC-1965', 'Australia')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000004', 'Benjamin', 'M', '22-MAY-2010', 'USA')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000005', 'Ella', 'F', '30-AUG-2002', 'Germany')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000006', 'Emma', 'F', '19-OCT-2010', 'Germany')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000007', 'Lihua', 'M', '19-OCT-1993', 'China')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000008', 'Edward', 'X', '07-JUN-1988', 'UK')");
      executeSQL("INSERT INTO Applicants(passport, name, gender, birthDate, country) VALUES ('P00000009', 'Iris', 'F', '25-DEC-2001', 'China')");

      executeSQL("INSERT INTO VisaTypes VALUES ('S1', 2)");
      executeSQL("INSERT INTO VisaTypes VALUES ('S2', 5)");
      executeSQL("INSERT INTO VisaTypes VALUES ('T1', 1)");
      executeSQL("INSERT INTO VisaTypes VALUES ('T2', 10)");
      executeSQL("INSERT INTO VisaTypes VALUES ('W1', 3)");

      executeSQL("INSERT INTO FamilyMembers VALUES (1, 'Joe', 'Father')");
      executeSQL("INSERT INTO FamilyMembers VALUES (2, 'Xiaoli', 'Mother')");
      executeSQL("INSERT INTO FamilyMembers VALUES (3, 'Peter', 'Spouse')");
      executeSQL("INSERT INTO FamilyMembers VALUES (3, 'Andy', 'Son')");
      executeSQL("INSERT INTO FamilyMembers VALUES (4, 'Daisy', 'Mother')");

      executeSQL("INSERT INTO VisaOfficers VALUES (1, 1, 'Liam', 2)");
      executeSQL("INSERT INTO VisaOfficers VALUES (2, 2, 'Noah', 10)");
      executeSQL("INSERT INTO VisaOfficers VALUES (3, 3, 'William', 5)");
      executeSQL("INSERT INTO VisaOfficers VALUES (4, 4, 'James', 7)");
      executeSQL("INSERT INTO VisaOfficers VALUES (5, 5, 'Charles', 8)");
      executeSQL("INSERT INTO VisaOfficers VALUES (6, 5, 'Samantha', 11)");
      executeSQL("INSERT INTO VisaOfficers VALUES (7, 2, 'Rachel', 9)");
      executeSQL("INSERT INTO VisaOfficers VALUES (8, 1, 'Joe', 3)");

      executeSQL("INSERT INTO AdmissionEntryPoints VALUES ('YVR', 'Air')");
      executeSQL("INSERT INTO AdmissionEntryPoints VALUES ('BLA', 'Land')");
      executeSQL("INSERT INTO AdmissionEntryPoints VALUES ('PAC', 'Water')");
      executeSQL("INSERT INTO AdmissionEntryPoints VALUES ('BCA', 'Air')");
      executeSQL("INSERT INTO AdmissionEntryPoints VALUES ('AKG', 'Air')");

      executeSQL("INSERT INTO BorderOfficers VALUES (1, 'John')");
      executeSQL("INSERT INTO BorderOfficers VALUES (2, 'David')");
      executeSQL("INSERT INTO BorderOfficers VALUES (3, 'Alfred')");
      executeSQL("INSERT INTO BorderOfficers VALUES (4, 'Justin')");
      executeSQL("INSERT INTO BorderOfficers VALUES (5, 'Amy')");
      executeSQL("INSERT INTO BorderOfficers VALUES (6, 'Melissa')");
      executeSQL("INSERT INTO BorderOfficers VALUES (7, 'Kevin')");
      executeSQL("INSERT INTO BorderOfficers VALUES (8, 'Sam')");

      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (1, 1, 1, '19-JUN-2017', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, 2, 2, '20-SEP-2018', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (3, 3, 3, '21-DEC-2015', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (4, 4, 4, '22-JUL-2018', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (5, 5, 5, '23-SEP-2020', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (1, null, 1, '07-OCT-2019', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, null, 2, '20-JAN-2013', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (3, null, 3, '11-NOV-2019', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (4, null, 4, '09-SEP-2018', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (5, null, 5, '19-JUL-2017', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (1, null, 1, '30-OCT-2020', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, null, 2, '01-SEP-2021', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (3, null, 3, '27-DEC-2016', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (4, null, 4, '18-SEP-2019', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (5, 5, 5, '23-SEP-2019', 'Received')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, 2, 2, '23-SEP-2019', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, 2, 2, '24-SEP-2019', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, 2, 2, '25-SEP-2019', 'Approved')");
      executeSQL("INSERT INTO Applications(aplctID, voID, eID, receiveDate, status) VALUES (2, 2, 2, '26-SEP-2019', 'Approved')");
      executeSQL("INSERT INTO TouristApps VALUES (1, 'Vancouver')");
      executeSQL("INSERT INTO TouristApps VALUES (2, 'Toronto')");
      executeSQL("INSERT INTO TouristApps VALUES (3, 'Calgary')");
      executeSQL("INSERT INTO TouristApps VALUES (4, 'Kelowna')");
      executeSQL("INSERT INTO TouristApps VALUES (5, 'Ottawa')");
      executeSQL("INSERT INTO StudentApps VALUES (6, 'UBCV')");
      executeSQL("INSERT INTO StudentApps VALUES (7, 'UTSG')");
      executeSQL("INSERT INTO StudentApps VALUES (8, 'UAlberta')");
      executeSQL("INSERT INTO StudentApps VALUES (9, 'UBCO')");
      executeSQL("INSERT INTO StudentApps VALUES (10, 'UOttawa')");
      executeSQL("INSERT INTO WorkerApps VALUES (11, 'Amazon')");
      executeSQL("INSERT INTO WorkerApps VALUES (12, 'Meta')");
      executeSQL("INSERT INTO WorkerApps VALUES (13, 'Microsoft')");
      executeSQL("INSERT INTO WorkerApps VALUES (14, 'Apple')");
      executeSQL("INSERT INTO WorkerApps VALUES (15, 'Walmart')");
      executeSQL("INSERT INTO StudentApps VALUES (16, 'UBCO')");
      executeSQL("INSERT INTO StudentApps VALUES (17, 'UBCV')");
      executeSQL("INSERT INTO TouristApps VALUES (18, 'Vancouver')");
      executeSQL("INSERT INTO WorkerApps VALUES (19, 'Walmart')");

      executeSQL("INSERT INTO Visas VALUES (1, 1, 1, 1, '15-NOV-2020', 'T1')");
      executeSQL("INSERT INTO Visas VALUES (2, 2, 2, 2, '03-DEC-2020', 'T1')");
      executeSQL("INSERT INTO Visas VALUES (3, 3, 2, 3, '22-OCT-2022', 'T1')");
      executeSQL("INSERT INTO Visas VALUES (4, 4, 3, 4, '31-OCT-2021', 'T1')");
      executeSQL("INSERT INTO Visas VALUES (5, 5, 5, 5, '18-NOV-2020', 'T2')");
      executeSQL("INSERT INTO Visas VALUES (6, 16, 2, 2, '06-NOV-2020', 'S1')");
      executeSQL("INSERT INTO Visas VALUES (7, 17, 2, 2, '05-NOV-2020', 'S2')");
      executeSQL("INSERT INTO Visas VALUES (8, 18, 2, 2, '14-JAN-2021', 'T2')");
      executeSQL("INSERT INTO Visas VALUES (9, 19, 2, 2, '18-MAR-2022', 'W1')");

      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (1, 1, '22-FEB-2022', 'YVR')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (2, 1, '21-SEP-2022', 'YVR')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (3, 2, '31-MAR-2022', 'BLA')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (4, 3, '04-JUL-2022', 'PAC')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (5, 4, '17-JAN-2022', 'AKG')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (2, 8, '17-JAN-2022', 'BLA')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (2, 7, '31-JAN-2022', 'AKG')");
      executeSQL("INSERT INTO Admissions(aplctID, boID, grantDate, entryPoint) VALUES (2, 6, '17-NOV-2022', 'YVR')");

      echo "Database has been reset<br>";
    }

    function printTables() {
      echo "Embassies";
      createTable(executeSQL("SELECT * FROM Embassies"));
      echo "Applicants";
      createTable(executeSQL("SELECT * FROM Applicants"));
      echo "FamilyMembers";
      createTable(executeSQL("SELECT * FROM FamilyMembers"));
      echo "VisaTypes";
      createTable(executeSQL("SELECT * FROM VisaTypes"));
      echo "VisaOfficers";
      createTable(executeSQL("SELECT * FROM VisaOfficers"));
      echo "AdmissionEntryPoints";
      createTable(executeSQL("SELECT * FROM AdmissionEntryPoints"));
      echo "BorderOfficers";
      createTable(executeSQL("SELECT * FROM BorderOfficers"));
      echo "Applications";
      createTable(executeSQL("SELECT * FROM Applications"));
      echo "Tourist Apps";
      createTable(executeSQL("SELECT * FROM TouristApps"));
      echo "Student Apps";
      createTable(executeSQL("SELECT * FROM StudentApps"));
      echo "Worker Apps";
      createTable(executeSQL("SELECT * FROM WorkerApps"));

      // Joined Applications tables
      // echo "Tourist Applications";
      // createTable(executeSQL("SELECT A.appID, A.aplctID, A.voID, A.eID, A.receiveDate, A.status, T.destination FROM Applications A, TouristApps T WHERE T.appID = A.appID"));
      // echo "Student Applications";
      // createTable(executeSQL("SELECT A.appID, A.aplctID, A.voID, A.eID, A.receiveDate, A.status, S.school FROM Applications A, StudentApps S WHERE S.appID = A.appID"));
      // echo "Worker Applications";
      // createTable(executeSQL("SELECT A.appID, A.aplctID, A.voID, A.eID, A.receiveDate, A.status, W.company FROM Applications A, WorkerApps W WHERE W.appID = A.appID"));
    
      echo "Visas";
      createTable(executeSQL("SELECT * FROM Visas"));
      echo "Admissions";
      createTable(executeSQL("SELECT * FROM Admissions"));
    }

    ?>
    <br>
  </div>
</body>

<?php require_once("footer.php") ?>

</html>