<?php
include('../../../private/config.php');
isAdmin();

if (isset($_POST['psl']) and !empty($_POST['psl']) and isset($_POST['sub']) and (int)$_POST['sub']) {
    $errors = [];
    if ($error = invalidID($_POST['psl'])) {
        array_push($errors, $error . " Invalid ID");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }
    $profsID = $_POST['psl'];
    $subject_id = $_POST['sub'];
    $subject = getDataFromTable($subject_id, 'name_of_subject', 'subjects');
    $profNameExist = [];
    $profNameNotExist = [];
    foreach ($profsID as $profID) {
        $profName = getFullNameFromDB('staffs', $profID);
        //check if professor is already assigned to the subject
        $sql = "SELECT professor_id, subject_id FROM professors_subject_list WHERE professor_id=? AND subject_id=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $profID, $subject_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $profNameExist[] = $profName;
            continue;
        }

        //insert data
        $sql = "INSERT INTO professors_subject_list (subject_id, professor_id, creator_id) VALUES(?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $subject_id, $profID, $creator_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $profNameNotExist[] = $profName;
    }
    $msg = '';

    if (count($profNameExist) > 0) { //warning
        $msg .= '<div class="alert alert-warning">' . implode(", ", $profNameExist) . ' is already assigned in ' . $subject . '</div><br>';
    }
    if (count($profNameNotExist) > 0) { //successfully inserted
        $msg .= '<div class="alert alert-success">' . implode(", ", $profNameNotExist) . ' Successfully assigned to ' . $subject . '</div><br>';
    }
    echo $msg;

    exit;
}
if (isset($_POST['deleteProfSubList']) and count($_POST['deleteProfSubList']) == 2) {
    $errors = [];
    if ($error = invalidID($_POST['deleteProfSubList'])) {
        array_push($errors, $error . " Invalid ID");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }
    $id = $_POST['deleteProfSubList'];
    //check if this is active or not 
    $active = findOne("SELECT active FROM accountlist WHERE account_id=? AND account_type>=3", $id[0]);
    if ($active) {
        if (!$active['active']) {
            array_push($errors, "Archived Professor Canot be Deleted");
        }
    } else {
        echo message('Professor ID: ' . $id[0] . ' Invalid');
    }
    //check if this student has any record in subject_attendance
    $subjectAttendanceRecords =  findAllOpenQuery("SELECT * FROM subject_attendance WHERE creator_id =?", $id[0]);
    if ($subjectAttendanceRecords) {
        array_push($errors, $errorDeleteMsg);
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }

    /*
     note:
    [0] for professor_id
    [1] subject_id
     */

    $sql = "DELETE FROM professors_subject_list WHERE professor_id =? AND subject_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id[0], $id[1]);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    echo message("Successfully Deleted", 1);
    exit;
}
