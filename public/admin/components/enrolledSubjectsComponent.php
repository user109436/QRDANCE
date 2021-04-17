<?php
include('../../../private/config.php');
isAdmin();

if (isset($_POST['eS']) and !empty($_POST['eS']) and isset($_POST['sub']) and (int)$_POST['sub']) {
    $errors = [];
    if ($error = invalidID($_POST['eS'])) {
        array_push($errors, $error . " Invalid ID");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }
    $studentsID = $_POST['eS'];
    $subject_id = $_POST['sub'];
    $subject = getDataFromTable($subject_id, 'name_of_subject', 'subjects');
    $studentNameExist = [];
    $studentNameNotExist = [];
    foreach ($studentsID as $studentID) {
        $studentName = getFullNameFromDB('students', $studentID);
        //check if professor is already assigned to the subject
        $sql = "SELECT student_id, subject_id FROM enrolled_subjects WHERE student_id=? AND subject_id=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $studentID, $subject_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $studentNameExist[] = $studentName;
            continue;
        }

        //insert data
        $sql = "INSERT INTO enrolled_subjects (subject_id, student_id, creator_id) VALUES(?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $subject_id, $studentID, $creator_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $studentNameNotExist[] = $studentName;
    }
    $msg = '';

    if (count($studentNameExist) > 0) { //warning
        $msg .= '<div class="alert alert-warning">' . implode(", ", $studentNameExist) . ' is already assigned in ' . $subject . '</div><br>';
    }
    if (count($studentNameNotExist) > 0) { //successfully inserted
        $msg .= '<div class="alert alert-success">' . implode(", ", $studentNameNotExist) . ' Successfully assigned to ' . $subject . '</div><br>';
    }
    echo $msg;

    exit;
}
if (isset($_POST['deleteEnrolledSubject']) and count($_POST['deleteEnrolledSubject']) == 2) {
    $errors = [];
    if ($error = invalidID($_POST['deleteEnrolledSubject'])) {
        array_push($errors, $error . " Invalid ID");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }
    $id = $_POST['deleteEnrolledSubject'];
    //check if this is active or not 
    $active = findOne("SELECT active FROM accountlist WHERE account_id=? AND account_type=1", $id[0]);
    if ($active) {
        if (!$active['active']) {
            array_push($errors, "Archived Student Canot be Deleted");
        }
    } else {
        echo message('Student ID: ' . $id[0] . ' Invalid');
    }
    //check if this student has any record in subject_attendance
    $subjectAttendanceRecords =  findAllOpenQuery("SELECT * FROM subject_attendance WHERE student_id =?", $id[0]);
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

    $sql = "DELETE FROM enrolled_subjects WHERE student_id =? AND subject_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id[0], $id[1]);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    echo message("Successfully Deleted", 1);
    exit;
}
