<?php
include('../../../private/config.php');

if (isset($_POST['s']) && $_POST['s'] >= 1 && count($_POST['schedule']) == 2) {
    $s = sanitizeInputs($_POST['schedule']);
    if ($s[1] > 1 || $s[1] < 0) {
        echo message("Invalid Value of Approved:" . $s[1]);
        exit;
    }
    if ($id = (int)$_POST['s']) {

        $sql = "UPDATE schedules SET staffs_notes=?, approve=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",  $s[0], $s[1], $id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        echo message("Appointment Successfully Updated", 1);
        exit;
    }
}
