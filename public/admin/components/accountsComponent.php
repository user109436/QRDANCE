<?php
include('../../../private/config.php');
isAdmin();
include('../../library/phpqrcode/qrlib.php');
//update
if (isset($_POST['s']) && ((int)$_POST['s'] > 0) && isset($_POST['account']) && count($_POST['account']) == 4) {
    $errors = [];
    $last_id = $_POST['s'];
    /*CHECK FOR ERRORS*/
    if ($error = emptyFields($_POST['account'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }

    /*CLEAN THE INPUTS*/
    $s = sanitizeInputs($_POST['account']);
    //check if username and email is already existing
    $sql = "SELECT * FROM accountlist WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $s[0]);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['id'] != $last_id) {
            array_push($errors, "Username already taken");
        }
    }
    // check if email is a valid gmail address
    if (!strpos($s[2], '@gmail.com')) {
        array_push($errors, "Gmail Address is Required");
    }

    $sql = "SELECT * FROM accountlist WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $s[2]);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['id'] != $last_id) {
            array_push($errors, "Email already taken");
        }
    }
    //check errors
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    $encrypted_password = password_hash($s[1], PASSWORD_DEFAULT);
    // save to database
    $sql = "UPDATE accountlist SET username=?,password=?, encrypted_password=?,email=?, active=?, creator_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $s[0], $s[1], $encrypted_password, $s[2], $s[3], $creator_id, $last_id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $qrCodeMsg = '';
    $account_id = getDataFromAccountList($last_id, 'account_id');
    if (getDataFromAccountList($last_id) == '1' && !hasQrcodeName($account_id)) { //generate qrcode
        //get the updated domain name from settings
        $hostName = getDataFromTable(1, 'domain_name', 'settings');
        //if no existing qrcode then create
        $id = uniqid('std-' . $date . "-", true);
        $host = $hostName . $id;
        $filename = $qrcodesPath2 . $id . ".png";
        QRcode::png($host, $filename, 'L', 10, 2);
        $sql = "INSERT INTO qr_codes (student_id, qrcode_name, creator_id) VALUES(?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $account_id, $id, $creator_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $qrCodeMsg = ", QR Code Successfully Created";
    }
    $msg = "Account: " . $s[0] . " successfully Updated" . $qrCodeMsg;
    echo message($msg, 1);
    exit;
}
