<?php
include('../../../private/config.php');
isAdmin();

//delete  
if (isset($_POST['deleteSection']) && (int)$_POST['deleteSection'] > 0) {
    $id = $_POST['deleteSection'];
    $name = $_POST['name'];
    //check if data is required in other table
    $sectionCount = countData('students', $id, 'section_id');

    if ($sectionCount > 0) {
        echo message($errorDeleteMsg);
        exit;
    }
    //check if id exist
    $sql = "SELECT * FROM sections WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();
    if ($rows->num_rows == 1) {
        //staffs
        $sql = "DELETE FROM sections WHERE id =?";
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
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['section'])) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['section'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['section']);
    $sectionExist = findOne("SELECT * FROM sections WHERE section=? LIMIT 1", $s[0]);
    if ($sectionExist) {
        if ($sectionExist['id'] != $last_id) {
            echo message('Section: ' . $s[0] . ' Already Exist');
            exit;
        }
    }
    //save to database
    $sql = "UPDATE sections SET section=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $s[0], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Section: " . $s[0] . " successfully Updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && isset($_POST['section']) && count($_POST['section']) == 1) {
    $errors = [];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['section'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['section']);
    $sectionExist = findOne("SELECT * FROM sections WHERE section=? LIMIT 1", $s[0]);
    if ($sectionExist) {
        echo message('Section: ' . $s[0] . ' Already Exist');
        exit;
    }

    //save to database
    $sql = "INSERT INTO sections (section, creator_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $s[0], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Section: " . $s[0] . " successfully created";
    echo message($msg, 1);
    exit;
}
