<?php
include('../../../private/config.php');
include('../../library/phpqrcode/qrlib.php');
isAdmin();



if (isset($_POST) && $_POST['s'] == 1 && isset($_POST['settings']) && (count($_POST['settings']) >= 1 && count($_POST['settings']) <= 4)) {
    $errors = [];
    $s = $_POST['settings'];
    //domain or host
    if (empty($s[3])) {
        array_push($errors, 'Domain Name cannot be Empty');
    }
    if (count($errors) > 0) {
        echo message(implode(", ", $errors)); // convert array errors to string && enclosed it in alert
        exit;
    }
    $xhost =  sanitizeInput($_POST['settings'][3]);
    if (strpos($xhost, "/log.php?id=")) {
        $host = $xhost;
    } else {
        $host = $xhost . "/log.php?id=";
    }
    //for the company
    $companyQRcodeName = 'company';

    //attendance today
    if ($attendanceToday = (isset($_POST['settings'][0]) && !empty($_POST['settings'][0]))) {
        $s[0] = $s[0] == 'on' ? 1 : 0;
    } else {
        $s[0] = 0;
    }
    //pandemic mode
    if ($pandemic = (isset($_POST['settings'][1]) && !empty($_POST['settings'][1]))) {
        $s[1] = $s[1] == 'on' ? 1 : 0;
    } else {
        $s[1] = 0;
    }
    //maintenance mode
    if ($maintenance = (isset($_POST['settings'][2]) && !empty($_POST['settings'][2]))) {
        $s[2] = $s[2] == 'on' ? 1 : 0;
    } else {
        $s[2] = 0;
    }

    $msg = '';
    //if this is host is the same in the DB-> domain_name skip generate just update 
    $hostExist = findOne('SELECT domain_name FROM settings WHERE domain_name=?', $host);

    if ($hostExist) { //not generate 
        $sql = "UPDATE settings SET  attendance_today=" . $s[0] . ", pandemic=" . $s[1] . ", maintenance=" . $s[2] . " WHERE id=1";
        if ($conn->query($sql) === TRUE) {
            $msg .= ", Settings Successfully Updated";
            echo message($msg, 1);
        } else {
            $msg .= "Error: " . $sql . "<br>" . $conn->error;
            echo message($msg);
        }
        exit;
    }

    //before update check if students qrcode_name >0 
    // if true -> update all qrcodes
    $qrcodes = findAll('qr_codes');
    if ($qrcodes) {
        //generate new qrcodes for them
        //move img to qr_codes
        foreach ($qrcodes as $qrcode) {
            // printArr($qrcode);
            //update  qrcode_name of student
            $new_qrcode_name = $id = uniqid('std-' . $date . "-", true);
            $newHost = $host . "" . $id;
            $filename = $qrcodesPath2 . $id . ".png";
            QRcode::png($newHost, $filename, 'L', 10, 2);
            $sql = "UPDATE qr_codes SET qrcode_name=? WHERE student_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $new_qrcode_name, $qrcode['student_id']);
            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            //if query is success delete the old qrcodes
            unlink($qrcodesPath2 . "" . $qrcode['qrcode_name'] . ".png");
        }
        $msg .= count($qrcodes) . " Students QR Code Updated";
        $account_id = $account_type = 0;
        $subject = "Students & Staffs";
        $message = "QR Codes are Updated, Please use the updated ones";
        //insert a message or notif that would notify the users -> Qr Codes are updated please use the new qrcodes
        $sql = "INSERT INTO notifications (account_id, account_type,  subject, message, creator_id) VALUES(?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $account_id, $account_type, $subject, $message, $creator_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $msg .= ", Notifications Successfully Generated";
    } else {
        $msg .= "0 affected Students Data";
    }

    $filename = $qrcodesPath2 . $companyQRcodeName . ".png";
    QRcode::png($host, $filename, 'L', 10, 2);

    $sql = "UPDATE settings SET domain_name='$host', qrcode_name='$companyQRcodeName', attendance_today=" . $s[0] . ", pandemic=" . $s[1] . ", maintenance=" . $s[2] . " WHERE id=1";
    if ($conn->query($sql) === TRUE) {
        $msg .= ", Settings Successfully Updated";
        echo message($msg, 1);
    } else {
        $msg .= "Error: " . $sql . "<br>" . $conn->error;
        echo message($msg);
    }

    exit;
}
