<?php
include('../../../private/config.php');
isAdmin();

//delete  
if (isset($_POST['deleteCourse']) && (int)$_POST['deleteCourse'] > 0) {
    $id = $_POST['deleteCourse'];
    $name = $_POST['name'];
    //check if data is required in other table
    $courseCount = countData('students', $id, 'course_id');

    if ($courseCount > 0) {
        echo message($errorDeleteMsg);
        exit;
    }
    //check if id exist
    $sql = "SELECT * FROM courses WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();
    if ($rows->num_rows == 1) {
        //staffs
        $sql = "DELETE FROM courses WHERE id =?";
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
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['course'])) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['course'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['course']);
    //check if data already exist
    $course = openQuery("SELECT * FROM courses WHERE  course_acronym='$s[0]' OR course='$s[1]' LIMIT 1");
    if ($course) {
        $error = '';
        if ($course['id'] != $last_id) {
            if (strtolower($course['course_acronym']) == strtolower($s[0])) {
                $error .= " Course Acronym Already Exist";
            }
            if (strtolower($course['course']) == strtolower($s[1])) {
                $error .= " Course Name Already Exist ";
            }
            echo message($error);
            exit;
        }
    }
    //save to database
    $sql = "UPDATE courses SET course_acronym=?,course=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $s[0], $s[1], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Course: " . $s[1] . " successfully Updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && isset($_POST['course']) && count($_POST['course']) == 2) {
    $errors = [];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['course'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['course']);
    $course = openQuery("SELECT * FROM courses WHERE  course_acronym='$s[0]' OR course='$s[1]' LIMIT 1");
    if ($course) {
        $error = '';
        if (strtolower($course['course_acronym']) == strtolower($s[0])) {
            $error .= "Course Acronym Already Exist";
        }
        if (strtolower($course['course']) == strtolower($s[1])) {
            $error .= "Course Name Already Exist";
        }
        echo message($error);
        exit;
    }
    //save to database
    $sql = "INSERT INTO courses (course_acronym, course, creator_id) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $s[0], $s[1], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $msg = "Course: " . $s[1] . " successfully created";
    echo message($msg, 1);
    exit;
}
