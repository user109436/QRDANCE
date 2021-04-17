<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [3, 4];
pageRestrict($account_types, "../", true);
if (isset($_GET['stud_id']) && (int)$_GET['stud_id']) {
    $student_id = $_GET['stud_id'];
    $student = findAll('students', $student_id);
    if (!$student) {
        echo invalidIDSVG();
        exit;
    }
    unset($student);
} else {
    echo invalidIDSVG("Student ID is not Set");
    exit;
}

$tables = ['enrolled_subjects', 'guard_attendance', 'subject_attendance', 'qr_codes'];
$column = 'student_id';
$count = countDatas($tables, $student_id, $column);
$totalRecordsInDB = sumRecords($count);

?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>

    <!-- Start your project here-->
    <div class="container-fluid">
        <h5 class="mt-5"><?php echo getFullNameFromDB('students', $student_id) ?> Records</h5>
    </div>
    <div class="container message"></div>
    <div class="container-fluid">
        <div class="row bg-white p-4">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <?php
                $tablePlaceHolder = ['Enrolled Subjects', 'Guard Attendance', 'Subject Attendance', 'QR Codes'];
                for ($i = 0; $i < count($count); $i++) {
                ?>
                    <h6 class="<?php echo $count[$i] >= 1 ? 'text-danger' : 'text-success' ?>">
                        <i class="fas fa-database"></i>
                        <?php echo $count[$i] . " in " . $tablePlaceHolder[$i]; ?>
                    </h6>
                <?php
                }
                ?>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <?php
                $sql = "SELECT active, email FROM accountlist WHERE account_id=? AND account_type=1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $student_id);
                if (!execute($stmt)) {
                    echo message("Error:" . $stmt->error . " sql=" . $sql);
                    exit;
                }
                $activeResult = $stmt->get_result();
                if ($activeResult->num_rows > 0) {
                    $accountResult = $activeResult->fetch_assoc();
                } else {
                    echo message("Something went wrong on the accountlist of this student");
                }
                ?>
                <h6 class="<?php echo $totalRecordsInDB == 0 ? 'text-success' : 'text-danger' ?>">Deletable: <i class="fas fa-trash-alt"></i> </h6>
                <!-- if account is active, archivable-> yes  -->
                <h6 class="<?php
                            echo isset($accountResult['active']) && $accountResult['active'] == 1 ? "text-success" : "";
                            ?>"> Archivable: <i class="fas fa-archive"></i></h6>

                <h6 class="<?php
                            echo isset($accountResult['active']) && $accountResult['active'] == 1 ? "text-warning" : "";
                            ?>"> Active: <i class="fas fa-user-graduate"></i> </h6>
                <h6 class="<?php
                            echo isset($accountResult['email']) && !empty($accountResult['email']) ? "text-info" : "";
                            ?>"> Email:<i class="fas fa-envelope"></i> <?php echo $accountResult['email'] ?> </h6>
            </div>
        </div>
    </div>
    <!-- subjects taken -->
    <div class="container-fluid">
        <h6 class="mt-5">Subjects Taken</h6>
        <hr>
        <div class="row">
            <?php
            $attendance = findAllOpenQuery("SELECT subject_id, remarks FROM subject_attendance WHERE student_id=? ORDER by date_created DESC", $student_id);
            if (empty($attendance)) {
                $attendance = [];
            }

            $overAllAttendance = [0, 0, 0, 0];
            $sql = "SELECT subjects.name_of_subject, enrolled_subjects.subject_id FROM enrolled_subjects
                 INNER JOIN subjects ON enrolled_subjects.subject_id=subjects.id
                 WHERE enrolled_subjects.student_id=?";
            $stmtSubs = $conn->prepare($sql);
            $stmtSubs->bind_param("s", $student_id);
            if ($stmtSubs->execute() === TRUE) {
                $subjectResult = $stmtSubs->get_result();

                if ($subjectResult->num_rows > 0) {
                    //display data
                    while ($subjects = $subjectResult->fetch_assoc()) {
                        $totalAttendance = countTotalAttendance($attendance,  $subjects['subject_id']);
                        $percentageTA = totalAttendancePercentage($totalAttendance);
                        $overAllAttendance[0] += $totalAttendance[0];
                        $overAllAttendance[1] += $totalAttendance[1];
                        $overAllAttendance[2] += $totalAttendance[2];
                        $overAllAttendance[3] += $totalAttendance[3];
                        //subject performance
                        $sumAttendanceCount = sumRecords($totalAttendance);
            ?>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                            <!-- Card -->
                            <div class="card mt-3">
                                <!-- Card content -->
                                <div class="card-body">

                                    <!-- Title -->
                                    <p class="font-weight-bold"><a><?php echo $subjects['name_of_subject'] ?></a></p>
                                    <hr>
                                    <p class="card-text">


                                        <span class=" mr-4 text-success">P<?php echo $present = $percentageTA[0] ?>%</span>
                                        <span class=" mr-4 text-danger">A<?php echo $percentageTA[1] ?>%</span>
                                        <span class=" mr-4 text-warning">L<?php echo $percentageTA[2] ?>%</span>
                                        <span class=" mr-4 text-info">E<?php echo $percentageTA[3] ?>%</span>


                                    </p>
                                    <hr>
                                    <p class="card-text">


                                        <span class=" mr-4 text-success">P<?php echo $totalAttendance[0] ?></span>
                                        <span class=" mr-4 text-danger">A<?php echo $totalAttendance[1] ?></span>
                                        <span class=" mr-4 text-warning">L<?php echo $totalAttendance[2] ?></span>
                                        <span class=" mr-4 text-info">E<?php echo $totalAttendance[3] ?></span>


                                    </p>
                                    <hr>
                                    <p class="text-uppercase font-weight-bold">Performance:
                                        <?php

                                        displayAttendancePerformance($present);

                                        ?>
                                    </p>

                                </div>

                            </div>
                            <!-- Card -->
                        </div>
                    <?php
                    }
                    $overAllPercentage = totalAttendancePercentage($overAllAttendance);
                    ?>

                    <div class="col-12 mt-5 p-4">

                        <div class="row text-center bg-white p-4">
                            <div class="col-12">
                                <h5 class="mt-2">Subject OverAll Performance</h5>
                                <hr>
                            </div>
                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 text-success">
                                <h6 class="font-weight-bold">Present <?php echo $overAllPercentage[0] ?> %</h6>
                            </div>
                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 text-danger">
                                <h6 class="font-weight-bold">Absent <?php echo $overAllPercentage[1] ?> %</h6>
                            </div>
                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 text-warning">
                                <h6 class="font-weight-bold">Late <?php echo $overAllPercentage[2] ?> % </h6>
                            </div>
                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 text-info">
                                <h6 class="font-weight-bold">Excuse <?php echo $overAllPercentage[3] ?> %</h6>
                            </div>
                            <div class="col-12">
                                <hr>
                                <h5 class="font-weight-bold text-uppercase">
                                    <?php
                                    displayAttendancePerformance($overAllPercentage[0]);
                                    ?>
                                </h5>

                            </div>
                        </div>
                    </div>
            <?php
                } else {
                    echo "<h4>Doesn't have any enrolled Subjects</h4>";
                }
            } else {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            ?>
        </div>

    </div>
    <!-- subject attendance  -->
    <div class="container-fluid">
        <!-- gate attendance -->
        <div class="row p-2">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 bg-white ">
                <h6 class="mt-5">Subject Attendance</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                        <thead class="blue white-text">
                            <tr>
                                <th class="th">Subject</th>
                                <th class="th-sm">Remarks</th>
                                <th class="th-sm">Mark by</th>
                                <th class="th-sm">Date</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $sql = "SELECT subjects.name_of_subject, subject_attendance.remarks,subject_attendance.creator_id,subject_attendance.date_created 
                        FROM subject_attendance 
                        INNER JOIN subjects ON subjects.id = subject_attendance.subject_id WHERE subject_attendance.student_id = ? ORDER by subject_attendance.date_created DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $student_id);
                            if (!execute($stmt)) {
                                echo message("Error:" . $stmt->error . " sql=" . $sql);
                                exit;
                            }
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                while ($subjectAttendance = $result->fetch_assoc()) {

                            ?>
                                    <tr>
                                        <td><?php echo $subjectAttendance['name_of_subject'] ?></td>
                                        <td><?php echo displayAttendanceLabel($subjectAttendance['remarks']) ?></td>
                                        <td><?php echo displayCreator($subjectAttendance['creator_id']) ?></td>
                                        <td><?php echo readableDate($subjectAttendance['date_created']) ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h4>Student has no record of Subject Attendance</h4>";
                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 bg-white ">
                <h6 class="mt-5">Gate Attendance</h6>
                <hr>
                <div class="table-responsive">
                    <table class=" DataFromDB table table-striped DataFromDB" cellspacing="0" width="100%">
                        <thead class="blue white-text">
                            <tr>
                                <th class="th-sm">Remarks</th>
                                <th class="th-sm">Guard on Duty</th>
                                <th class="th-sm">Date</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $sql = "SELECT  present,creator_id,date_created 
                        FROM guard_attendance  WHERE student_id = ? ORDER by date_created DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $student_id);
                            if (!execute($stmt)) {
                                echo message("Error:" . $stmt->error . " sql=" . $sql);
                                exit;
                            }
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                while ($gateAttendance = $result->fetch_assoc()) {

                            ?>
                                    <tr>
                                        <td><?php echo InOrOutLabel($gateAttendance['present']) ?></td>
                                        <td><?php echo displayCreator($gateAttendance['creator_id']) ?></td>
                                        <td><?php echo readableDate($gateAttendance['date_created']) ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h4>Student has no record of Gate Attendance</h4>";
                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End your project here-->

    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>