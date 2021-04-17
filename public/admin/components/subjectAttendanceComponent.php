<?php
include('../../../private/config.php');
if (isset($_POST['mark']) && isset($_POST['student']) && isset($_POST['subject_id']) && count($_POST['mark']) === count($_POST['student']) && count($_POST['classDetails']) == 3) {
    //validate id
    $errors = [];
    if ($error = invalidID($_POST['subject_id'], false)) {
        array_push($errors, $error . " Invalid Subject ID");
    }
    if ($error = invalidID($_POST['student'])) {
        array_push($errors, $error . " Invalid Students ID");
    }
    if ($error = invalidID($_POST['mark'])) {
        array_push($errors, $error . " Invalid Attendance ID");
    }

    if ($error = invalidID($_POST['classDetails'])) {
        array_push($errors, $error . " Invalid Hidden IDs");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors));
        exit;
    }
    //assign id
    $subject_id = (int)$_POST['subject_id'];
    $marks = validatedID($_POST['mark']);
    //0-year 1-course 2-section
    $classIDs = validatedID($_POST['classDetails']);
    $students_id = validatedID($_POST['student']);
    //loop through all id and check if student has already attendance this day for this subject, if not insert
    $i = 0;
    $updated = 0;
    $save = 0;
    foreach ($students_id as $stud_id) {

        if ($date = (isset($_POST['date']) and !empty($_POST['date']))) {
            $dateToday = $_POST['date'] . "%";
        }
        //select
        $data = wildCardFindAllSubjectAttendance($dateToday, $stud_id, $subject_id);
        if ($data) { //update
            $subjectAttendance = $data[0];
            if ($date) {
                $dateToday = $_POST['date'];
                $sql = "UPDATE subject_attendance SET remarks=?, date_created=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sss', $marks[$i], $dateToday, $subjectAttendance['id']);
            } else {
                $sql = "UPDATE subject_attendance SET remarks=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $marks[$i], $subjectAttendance['id']);
            }

            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            $updated++;
        } else { //insert

            if ($date) {
                $dateToday = $_POST['date'];
                $sql = "INSERT INTO subject_attendance (subject_id, student_id,year_id,course_id,section_id, remarks, creator_id, date_created) VALUES(?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssssss', $subject_id, $stud_id, $classIDs[0], $classIDs[1], $classIDs[2], $marks[$i], $creator_id, $dateToday);
            } else {
                $sql = "INSERT INTO subject_attendance (subject_id, student_id,year_id,course_id,section_id, remarks, creator_id) VALUES(?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssss', $subject_id, $stud_id, $classIDs[0], $classIDs[1], $classIDs[2], $marks[$i], $creator_id);
            }
            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            $save++;
        }
        $i++;
    }
    $msg = '';
    if ($updated > 0) {
        $msg .= $updated . " Record(s) Updated ";
    }
    if ($save > 0) {
        $msg .= $save . " Record(s) Saved";
    }
    echo message($msg, 1);
    exit;
} else {
    echo message("Data Empty or The number of students and marks doesn't match", 2);
}
