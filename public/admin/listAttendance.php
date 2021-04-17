<?php
include('../../private/config.php');
include('./shared/header.php');
// TODO: Refactor and Code Optimization
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>


    <!-- Start your project here-->
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
                                <th class="th">Student</th>
                                <th class="th-sm">Remarks</th>
                                <th class="th-sm">Mark by</th>
                                <th class="th-sm">Date</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $sql = "SELECT subjects.name_of_subject, subject_attendance.student_id, subject_attendance.remarks,subject_attendance.creator_id,subject_attendance.date_created 
                        FROM subject_attendance 
                        INNER JOIN subjects ON subjects.id = subject_attendance.subject_id ORDER by subject_attendance.date_created DESC";
                            $stmt = $conn->prepare($sql);
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
                                        <td><?php echo getFullNameFromDB('students', $subjectAttendance['student_id']) ?></td>
                                        <td><?php echo displayAttendanceLabel($subjectAttendance['remarks']) ?></td>
                                        <td><?php echo displayCreator($subjectAttendance['creator_id']) ?></td>
                                        <td><?php echo readableDate($subjectAttendance['date_created']) ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h5>No Record Found in Subject Attendance</h5>";
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
                                <th class="th-sm">Student</th>
                                <th class="th-sm">Guard on Duty</th>
                                <th class="th-sm">Date</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $sql = "SELECT * FROM guard_attendance ORDER by date_created DESC";
                            $stmt = $conn->prepare($sql);
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
                                        <td><?php echo getFullNameFromDB("students", $gateAttendance['student_id']) ?></td>
                                        <td><?php echo displayCreator($gateAttendance['creator_id']) ?></td>
                                        <td><?php echo readableDate($gateAttendance['date_created']) ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h5>No Record Found in Gate Attendance</h5>";
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