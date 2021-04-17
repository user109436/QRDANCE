<?php
include('../private/config.php');
include('./shared/header.php');
if (isset($_GET['logout']) and $_GET['logout'] == 1) {
    after_successful_logout(); //security checks
    session_destroy();
}
if (isset($_SESSION['account_type'])) {
    if ($_SESSION['account_type'] >= 2 && $_SESSION['account_type'] <= 4) {
        //admin
        header('location:./admin');
        exit;
    }
} else {
    header('location:./');
}
confirm_user_logged_in();
$msg = '';
$subject = '';
$purpose = '';
$schedule = '';
if (isset($_POST['s']) && $_POST['s'] == 1 && isset($_POST['schedule']) && count($_POST['schedule']) == 3) {

    $errors = [];
    if ($error = emptyFields($_POST['schedule'])) { //check for empty fields-> show error if empty
        array_push($errors, $error . " Empty field(s)");
    }
    $date = date("Y-m-d h:i:sa");
    if (count($errors) == 0) {
        // 0-Subject 1-purpose 2-schedule
        $s = sanitizeInputs($_POST['schedule']);
        //create
        if (isset($_POST['create']) && $_POST['create'] == 1) {
            $sql = "INSERT INTO schedules (student_id, subject, purpose,schedule, date_created) VALUES(?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $account_id, $s[0], $s[1], $s[2], $date);
            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            $msg = message("Appointment:" . $s[0] . " Successfully Submitted", 1);
        }

        //update
        if (isset($_POST['update']) && $id = (int)$_POST['update']) {
            $sql = "SELECT * FROM schedules WHERE student_id=? AND id=? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $account_id, $id);
            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            $scheduleResult = $stmt->get_result();
            if ($scheduleResult->num_rows > 0) { //update only if student has this kind of schedule(id)

                $sql = "UPDATE schedules SET student_id=?, subject=?, purpose=?,schedule=?, date_created=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $account_id, $s[0], $s[1], $s[2], $date, $id);
                if (!execute($stmt)) {
                    echo message("Error:" . $stmt->error . " sql=" . $sql);
                    exit;
                }
                $msg = message("Appointment:" . $s[0] . " Successfully Updated", 1);
            }
        }
    } else {
        $msg = message(implode(", ", $errors));
    }
}

//delete 
if (isset($_GET['deleteID']) && $id = (int)$_GET['deleteID']) {
    $sql = "SELECT * FROM schedules WHERE student_id=? AND id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $account_id, $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $scheduleResult = $stmt->get_result();
    if ($scheduleResult->num_rows > 0) { //update only if student has this kind of schedule(id)
        $sql = "DELETE FROM schedules WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $msg = message("Appointment Successfully Deleted", 1);
    }
}
//edit
if (isset($_GET['editID']) && $id = (int)$_GET['editID']) {
    $sql = "SELECT * FROM schedules WHERE student_id=? AND id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $account_id, $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $scheduleResult = $stmt->get_result();
    if ($scheduleResult->num_rows > 0) { //update only if student has this kind of schedule(id)
        $schd = $scheduleResult->fetch_assoc();
        $subject = $schd['subject'];
        $purpose = $schd['purpose'];
        $schedule = str_replace(" ", "T", $schd['schedule']);
    }
}
?>

<body>
    <?php
    include('./shared/navbar.php');
    ?>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class=" message mt-5">
                            <?php
                            unset($_POST);
                            echo $msg;
                            ?>
                        </div>
                        <h5>Add Appointment</h5>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                            <div class="md-form mb-5">
                                <input type="text" id="schedule" class="form-control  input" name="schedule[0]" value="<?php echo $subject; ?>">
                                <label class="label" data-error="wrong" data-success="right" for="schedule">Subject</label>
                            </div>
                            <div class="md-form mb-5">
                                <textarea id="purpose" class="md-textarea form-control fields input" rows="2" name="schedule[1]"><?php echo  $purpose; ?></textarea>
                                <label class="label" data-error="wrong" data-success="right" for="purpose">Purpose</label>
                            </div>
                            <label class="label" data-error="wrong" data-success="right" for="date">Schedule</label>
                            <div class="md-form mb-5">
                                <input type="datetime-local" id="date" class="form-control  input" name="schedule[2]" value="<?php echo $schedule; ?>">
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <input type="hidden" name="s" value="1">
                                <button class="btn btn-success p-3" type="submit" name="create" value="1"><i class="fas fa-plus-circle"></i> Create</button>
                                <button class="btn btn-secondary p-3" type="submit" name="update" value="<?php echo isset($_GET['editID']) ? $_GET['editID'] : '' ?>"><i class="fas fa-save"></i> Update</button>
                                <input type="reset" name="reset" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                                <thead class="blue white-text">
                                    <tr>
                                        <th class="th">Manipulate</th>
                                        <th class="th">Subject</th>
                                        <th class="th">Purpose</th>
                                        <th class="th">Schedule</th>
                                        <th class="th">Approved</th>
                                        <th class="th">Message</th>
                                        <th class="th">Date Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM schedules WHERE student_id=? ORDER by date_created DESC";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("s", $account_id);
                                    if ($stmt->execute() === TRUE) {
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($schedules = $result->fetch_assoc()) {

                                    ?>
                                                <tr>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" href="
                                                        <?php echo "schedule.php?editID=" . urlencode($schedules['id']) ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a class=" btn btn-danger btn-sm" href="
                                                        <?php echo "schedule.php?deleteID=" . urlencode($schedules['id']) ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $schedules['subject']; ?></td>
                                                    <td><?php echo $schedules['purpose']; ?></td>
                                                    <td><?php echo $schedules['schedule']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($schedules['approve'] == 1) {
                                                            echo '<i class="text-success fa-lg fas fa-check"></i> Yes';
                                                        } else if ($schedules['approve'] == 0) {
                                                            echo '<i class="text-danger fa-lg fas fa-ban"></i>No';
                                                        } else {
                                                            echo '<i class="text-warning fa-lg fas fa-file-signature"></i>To Review';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $schedules['staffs_notes']; ?></td>
                                                    <td><?php echo readableDate($schedules['date_created']); ?></td>
                                                </tr>


                                    <?php
                                            }
                                        } else {
                                            echo "<h5>No Schedules Found</h5>";
                                        }
                                    } else {
                                        echo message("Error:" . $stmt->error . " sql=" . $sql);
                                        exit;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
include('./shared/footer.php');
?>