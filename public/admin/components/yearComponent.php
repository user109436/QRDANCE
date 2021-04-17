<?php
include('../../../private/config.php');
isAdmin();
//delete  
if (isset($_POST['deleteYear']) && (int)$_POST['deleteYear'] > 0) {
    $id = $_POST['deleteYear'];
    $name = $_POST['name'];
    //check if data is required in other table
    $yearCount = countData('students', $id, 'year_id');

    if ($yearCount > 0) {
        echo message($errorDeleteMsg);
        exit;
    }
    //check if id exist
    $sql = "SELECT * FROM year WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();

    if ($rows->num_rows == 1) {
        //staffs
        $sql = "DELETE FROM year WHERE id =?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        echo message($name . " Succesfully deleted", 1);
    } else {
        echo message('Failed to Delete ID:' . $id . " doesn't exist", 2);
    }
    exit;
}
//update
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['year'])) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['year'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (!(int)$_POST['year'][0]) {
        array_push($errors, "Numbers only allowed");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['year']);
    $yearExist = findOne("SELECT * FROM year WHERE year=? LIMIT 1", $s[0]);
    if ($yearExist) {
        if ($yearExist['id'] != $last_id) {
            echo message('Year: ' . $s[0] . ' Already Exist');
            exit;
        }
    }

    //save to database
    $sql = "UPDATE year SET year=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $s[0], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Year: " . $s[0] . " successfully Updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && isset($_POST['year']) && count($_POST['year']) == 1) {
    $errors = [];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['year'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (!(int)$_POST['year'][0]) {
        array_push($errors, " Accepts Numbers Only");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['year']);
    $yearExist = findOne("SELECT * FROM year WHERE year=? LIMIT 1", $s[0]);
    if ($yearExist) {
        echo message('Year: ' . $s[0] . ' Already Exist');
        exit;
    }
    //save to database
    $sql = "INSERT INTO year (year, creator_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $s[0], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Year: " . $s[0] . " successfully created";
    echo message($msg, 1);
    exit;
}
