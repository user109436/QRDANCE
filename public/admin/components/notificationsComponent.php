<?php
include('../../../private/config.php');
/*
[0]=subject
[1]=account_type
[2]=message
*/
// printArr($_POST);

//delete  
if (isset($_POST['deleteMessage']) && (int)$_POST['deleteMessage'] > 0) {
    $id = $_POST['deleteMessage'];
    $name = $_POST['name'];
    //check if id exist
    $sql = "SELECT * FROM notifications WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();
    if ($rows->num_rows == 1) {
        $sql = "DELETE FROM notifications WHERE id =?";
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
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['message']) && count($_POST['message']) == 3) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    $_POST['message'][1] = " " . $_POST['message'][1];
    if ($error = emptyFields($_POST['message'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if ($_POST['message'][1] >= 5 && $_POST['message'][1] <= 1) {
        array_push($errors, "Invalid Recipient ID");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['message']);

    //save to database
    $sql = "UPDATE notifications SET account_type=?,subject=?,message=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $s[1], $s[0], $s[2], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Message Successfully Updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && isset($_POST['message']) && count($_POST['message']) == 3) {
    $errors = [];
    $_POST['message'][1] = " " . $_POST['message'][1];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['message'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }

    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['message']);

    //save to database
    $account_id = 0;
    $sql = "INSERT INTO notifications (account_id, account_type, subject, message, creator_id) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $account_id, $s[1], $s[0], $s[2], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Message  Successfully created";
    echo message($msg, 1);
    exit;
}
