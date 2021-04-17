<?php
include('../../../private/config.php');
isAdmin();

//delete
if (isset($_POST['deleteStudent']) && (int)$_POST['deleteStudent'] > 0) {
    $id = $_POST['deleteStudent'];
    $name = $_POST['name'];

    $totalRecordsInDB = totalStudentRecords($id);

    if ($totalRecordsInDB > 0) {
        echo message($errorDeleteMsg);
        exit;
    }

    //check if id exist
    $sql = "SELECT * FROM students WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $rows = $stmt->get_result();

    if ($rows->num_rows == 1) {

        //students
        $sql = "DELETE FROM students WHERE id =?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $accountlistID;
        $result = getAccountTypeById($id, 1);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $accountlistID = $row['id'];
            }
        }
        //account_photos
        $sql = "DELETE FROM accounts_photos WHERE id =?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $accountlistID);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        //account_list
        $sql = "DELETE FROM accountlist WHERE id =?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $accountlistID);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }

        echo message($name . " succesfully deleted", 1);
    } else {
        echo message('Failed to Delete ID:' . $id . " doesn't exist", 2);
    }
    exit;
}
//update
if (isset($_POST['s']) && (int)$_POST['s'] > 0 && isset($_POST['studentInfo'])) {
    $last_id = $_POST['s'];

    //no change to photo
    $errors = [];

    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['studentInfo'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }

    //change to photo
    $file = 'profilePicture';
    $hasFile = false;
    if (file_size($file) > 0) { //update profilePicture if image is replaced
        /*PHOTO VALIDATION*/
        if ($error = file_errors($file)) { //
            array_push($errors, $error);
        }
        if ($error = notImage($file)) { //check if file is image
            array_push($errors, $error);
        }
        if ($error = imageExceedMaxLimit($file)) {  //check if file exceed the max limit
            array_push($errors, $error);
        }
        $fileExtension = getFileExtension($file);
        if ($error = notValidFileExtension($file)) { //check if valid file extension
            array_push($errors, $error);
        }
        $hasFile = true;
    }

    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['studentInfo']);



    //1.Update staffs
    $sql = "UPDATE students SET fname=?, mname=?, lname=?,  year_id=?,section_id=?,course_id=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $accountlistID;
    $result = getAccountTypeById($last_id, 1);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $accountlistID = $row['id'];
        }
    }
    //2.Update accountlist
    $sql = "UPDATE accountlist SET creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $creator_id, $accountlistID);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    //3.Update accounts_photos
    if ($hasFile) {
        $temp = explode(
            ".",
            $_FILES["profilePicture"]["name"]
        );
        $newfilename = $last_id . '.' . end($temp);
        // move the file to staffs folder
        if (!move_uploaded_file($_FILES["profilePicture"]["tmp_name"], "../" . $studentsPath . $newfilename)) {
            echo message("Error: Failed to Add the Photo") . "</div>";
        }

        $sql = "UPDATE accounts_photos SET file_extension=?, creator_id=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fileExtension, $creator_id, $accountlistID);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
    }


    $msg = fullName($s[0], $s[1], $s[2]) . " successfully updated";
    echo message($msg, 1);
    exit;
}
//create
if (isset($_POST['s']) && ((int)$_POST['s'] === -1) && count($_POST['studentInfo']) == 6 && isset($_FILES['profilePicture'])) {
    $errors = [];
    $accountType = 1;

    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['studentInfo'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    /*PHOTO VALIDATION*/
    $file = 'profilePicture';
    if ($error = file_errors($file)) { //
        array_push($errors, $error);
    }
    if ($error = notImage($file)) { //check if file is image
        array_push($errors, $error);
    }

    if ($error = imageExceedMaxLimit($file)) {  //check if file exceed the max limit
        array_push($errors, $error);
    }

    $fileExtension = getFileExtension($file);
    if ($error = notValidFileExtension($file)) { //check if valid file extension
        array_push($errors, $error);
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['studentInfo']);
    //save to database info and photo
    //insert to staffs
    //note:$s[5] ==accountType
    $sql = "INSERT INTO students (fname, mname, lname, year_id, section_id, course_id,creator_id) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $last_id = $conn->insert_id; //get the last inserted id;

    $username = uniqid("username-");
    $password = uniqid("password-");
    $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

    //insert to accountlist (staff_id, accountType, creator_id)
    $sql = "INSERT INTO accountlist (account_id, account_type, username, password, encrypted_password, creator_id) VALUES (?,?,?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $last_id, $accountType, $username, $password, $encrypted_password, $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }

    //insert to accounts_photos ($account_id, fileExtension, creator_id)
    $sql = "INSERT INTO accounts_photos (account_id, file_extension, creator_id) VALUES(?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $last_id, $fileExtension, $creator_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }

    $temp = explode(
        ".",
        $_FILES["profilePicture"]["name"]
    );
    $newfilename = $last_id . '.' . end($temp);
    // move the file to staffs folder
    if (!move_uploaded_file($_FILES["profilePicture"]["tmp_name"], "../" . $studentsPath . $newfilename)) {
        echo message("Error: Failed to Add the Photo") . "</div>";
    }

    $msg = fullName($s[0], $s[1], $s[2]) . " successfully created";
    echo message($msg, 1);
    exit;
}
