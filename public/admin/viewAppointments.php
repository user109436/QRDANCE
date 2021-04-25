<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
$staffs_notes = '';
$active = 0;
$purpose = '';
if (isset($_GET['editID']) && $id = (int)$_GET['editID']) {
    $sql = "SELECT * FROM schedules WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $schd = $result->fetch_assoc();
        $subject = $schd['subject'];
        $purpose = $schd['purpose'];
        $active = $schd['approve'];
        $staff_notes = $schd['staffs_notes'];
    }
}

?>

<body>
    <?php include('./shared/sidebar.php');    ?>

    <div class="container">
        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class=" message mt-5">
                            <?php
                            unset($_POST);
                            ?>
                        </div>
                        <h5>Review Appointment</h5>
                        <?php

                        if (isset($subject) && $subject != '') {
                        ?>
                            <p><span class="font-weight-bold">Subject:</span><?php echo $subject ?></p>
                            <p><span class="font-weight-bold">Purpose:</span><?php echo $purpose ?></p>
                            <p><span class="font-weight-bold">Student:</span><?php echo getFullNameFromDB('students', $schd['student_id']) ?></p>

                        <?php
                        }
                        ?>
                        <form id="formAdd">
                            <div class="md-form mb-5">
                                <textarea id="purpose" class="md-textarea form-control fields input" rows="2" name="schedule[0]"><?php echo  $staffs_notes; ?></textarea>
                                <label class="label" data-error="wrong" data-success="right" for="purpose">Message</label>
                            </div>
                            <h6 class="text-center">Approve</h6>
                            <div class="md-form mb-5">
                                <div class="md-form mb-5 text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input radioBtn" type="radio" id="yes" value="1" name="schedule[1]" <?php echo $active == 1 ? 'checked' : '' ?> />
                                        <label class=" form-check-label" for="yes">YES</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input radioBtn" type="radio" id="no" value="0" name="schedule[1]" <?php echo $active == 3 ? 'checked' : '' ?> />
                                        <label class="form-check-label" for="no">NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <input type="hidden" name="s" value="<?php echo isset($_GET['editID']) ? $_GET['editID'] : 1 ?>">
                                <input type="hidden" id="s" value="1">
                                <button class="btn btn-secondary p-3" type="submit" name="update" onclick="updateData('appointments')"><i class="fas fa-save"></i> Update</button>
                                <input type="reset" name="reset" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12" id="appointmentResult">

            </div>
        </div>
    </div>
    </main>
    <!-- page-content" -->
    </div>
</body>
<?php
include('./shared/footer.php');
?>