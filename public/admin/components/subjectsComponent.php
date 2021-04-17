<?php
include('../../../private/config.php');
isAdmin();

//delete  
if (isset($_POST['deleteSubject']) && (int)$_POST['deleteSubject'] > 0) {
    $id = $_POST['deleteSubject'];
    $name = $_POST['name'];

    $subjectCount = 0;
    $subject[0] = countData('enrolled_subjects', $id, 'subject_id');
    $subject[1] = countData('subject_attendance', $id, 'subject_id');
    $subject[2] = countData('professors_subject_list', $id, 'subject_id');
    foreach ($subject as $num) {
        $subjectCount += $num;
    }
    if ($subjectCount > 0) {
        echo message($errorDeleteMsg);
        exit;
    }

    //check if id exist
    $sql = "SELECT * FROM subjects WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();
    if ($rows->num_rows == 1) {
        //staffs
        $sql = "DELETE FROM subjects WHERE id =?";
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
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['subject'])) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['subject'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['subject']);
    $subjectExist = findOne("SELECT * FROM subjects WHERE name_of_subject=? LIMIT 1", $s[0]);
    if ($subjectExist) {
        if ($subjectExist['id'] != $last_id) {
            echo message('Subject: ' . $s[0] . ' Already Exist');
            exit;
        }
    }
    //save to database
    $sql = "UPDATE subjects SET name_of_subject=?,description=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $s[0], $s[1], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Subject: " . $s[0] . " successfully Updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && isset($_POST['subject']) && count($_POST['subject']) == 2) {
    $errors = [];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['subject'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['subject']);
    $subjectExist = findOne("SELECT * FROM subjects WHERE name_of_subject=? LIMIT 1", $s[0]);
    if ($subjectExist) {
        echo message('Subject: ' . $s[0] . ' Already Exist');
        exit;
    }
    //save to database
    $sql = "INSERT INTO subjects (name_of_subject, description, creator_id) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $s[0], $s[1], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Subject: " . $s[0] . " successfully created";
    echo message($msg, 1);
    exit;
}
