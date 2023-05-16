<?php
function createButton($class, $link, $text) {
    echo "<button class='{$class}' type='button' onclick=\"window.location.href='{$link}'\">{$text}</button>";
}

function createLabel($for, $text) {
    echo "<label for='{$for}'>{$text}</label>";
}

function createField($id, $placeholder = "") {
    echo "<input type='text' id='{$id}' name='{$id}' placeholder='{$placeholder}'>";
}

function createForm($id, $text, $placeholder = "") {
    createLabel($id, $text);
    createField($id, $placeholder);
}

function createRadioForm($id, $name, $text, $handler = "", $checked = false) {
    echo "<input type='radio' id='{$id}' name='{$name}' value ='{$id}' onchange='{$handler}'";
    if ($checked) {
        echo ("checked");
    }
    echo (">");
    createLabel($id, $text);
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function connectToDB() {
    global $conn;
    $conn = OCILogon("ora_ykzhao", "a35356435", "dbhost.students.cs.ubc.ca:1522/stu");
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function disconnectFromDB() {
    global $conn;
    OCILogoff($conn);
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
function executeSQL($sql) {
    global $conn;
    $handle = OCIParse($conn, $sql);
    OCIExecute($handle);
    return $handle;
}

// Adapted from https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/oracle-test.txt
// and https://onlinephp.io/oci-bind-by-name/manual
function executeBoundSQL($sql, &$tuples) {
    global $conn;
    $handle = OCIParse($conn, $sql);
    foreach ($tuples as &$tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($handle, $bind, $tuple[$bind]);
            unset($val); // unsure if necessary
        }
        OCIExecute($handle);
    }
    return $handle;
}

// HTML table adapted from https://www.w3schools.com/html/tryit.asp?filename=tryhtml_table_intro
// Retrieving column names adapted from https://www.php.net/manual/en/function.oci-field-name.php
// Fetching and printing query results adapted from https://www.php.net/manual/en/function.oci-fetch-array.php
function createTable($result) {
    echo "<table>\n";
    echo "<tr>";
    $ncols = oci_num_fields($result);
    for ($i = 1; $i <= $ncols; $i++) {
        $column_name = oci_field_name($result, $i);
        echo "<th>$column_name</th>";
    }
    echo "</tr>\n";
    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        foreach ($row as $item) {
            if (is_null($item)) {
                echo "<td>null</td>";
            } else {
                echo "<td>" . ($item) . "</td>";
            }
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
}
?>