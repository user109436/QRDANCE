<?php
include('../../../private/config.php');
if (isset($_POST['date']) && emptyFields($_POST['date']) == 0) {
    $d = $_POST['date'];
    echo '<h5 class="text-center mt-3">Class Performance <span class="font-weight-bold">' . $d[0] . ' to ' . $d[1] . '</span></h5>';
}
?>
<div class="table-responsive">
    <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
        <thead class="blue white-text">
            <tr>
                <th class="th">Profile</th>
                <th class="th">Name</th>
                <th class="th-sm">Percentage</th>
                <th class="th-sm">P</th>
                <th class="th-sm">A</th>
                <th class="th-sm">L</th>
                <th class="th-sm">E</th>
            </tr>
        </thead>
        <tbody>

            <?php

            if (isset($_POST['s']) && $_POST['s'] == 1 && count($_POST['viewReport']) == 4) {

                //validate id
                $errors = [];
                if ($error = invalidID($_POST['viewReport'])) {
                    array_push($errors, $error . " Invalid ID");
                }

                if (count($errors) > 0) {
                    echo message(implode(", ", $errors));
                    exit;
                }
                $s = validatedID($_POST['viewReport']);
                //subject[0] year[1] course[2] section[3]
                $studentsEnrolledSubject = findAllEnrolledStudents($s);
                if ($studentsEnrolledSubject) {
                    //loop through each student_id
                    $overAllClassPerformance = [0, 0, 0, 0];
                    foreach ($studentsEnrolledSubject as $student) {
                        $sql = "SELECT * FROM subject_attendance WHERE student_id=" . $student['id'] . " AND subject_id=?";
                        if (isset($_POST['date']) && emptyFields($_POST['date']) == 0) {
                            $d = $_POST['date'];
                            $student_id = $student['id'];
                            $sql = "SELECT * FROM subject_attendance WHERE subject_id=? AND student_id=" . $student_id .
                                " AND year_id=" . $s[1] . " AND course_id=" . $s[2] . " AND section_id=" . $s[3] . " AND date_created BETWEEN '$d[0]' AND '$d[1]' ";
                        }
                        $data = findAllOpenQuery($sql, $s[0]);
                        $totalAttendance = [0, 0, 0, 0];
                        $presentPercentage = [0, 0, 0, 0];
                        if ($data) { //if student has attendance compute for percentage and display PALE
                            $totalAttendance = countTotalAttendance($data, $student['id'], 'student_id');
                            $presentPercentage = totalAttendancePercentage($totalAttendance);
                            $overAllClassPerformance[0] += $totalAttendance[0];
                            $overAllClassPerformance[1] += $totalAttendance[1];
                            $overAllClassPerformance[2] += $totalAttendance[2];
                            $overAllClassPerformance[3] += $totalAttendance[3];
                        }

                        $imgPath =  $studentsPath . $student['id'] . "." . displayFileExtension($student['id'], 1);
            ?>
                        <tr>
                            <td class="font-weight-bold">
                                <img class="profileImg" src="<?php echo $imgPath ?>" alt="<?php echo $fullname = fullName($student['fname'], $student['mname'], $student['lname']) ?>">
                            </td>
                            <td><a href="viewRecords.php?stud_id=<?php echo $student['id'] ?>" target="blank"><?php echo $fullname ?></a></td>
                            <td>
                                <?php
                                echo "<h5 class='text-success font-weight-bold'>" . round($presentPercentage[0], 2) . "%</h5>";
                                ?>
                            </td>
                            <?php
                            foreach ($totalAttendance as $attendance) {
                                echo "<td>" . $attendance . "</td>";
                            }
                            ?>
                        </tr>

            <?php
                    }
                } else {
                    echo message('No Students Found In Enrolled Subjects', 2);
                }
            }
            ?>

        </tbody>
    </table>
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Overall Class Performance</h5>
                        <hr>
                        <?php
                        $overAllPercentage = totalAttendancePercentage($overAllClassPerformance);
                        echo "<h5 class='text-success'>Percentage:" . round($overAllPercentage[0], 2) . "%</h5><br><h5 class='text-info'> Performance:";
                        displayAttendancePerformance($overAllPercentage[0]);
                        echo "</h5>";

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../node_modules/mdbootstrap/js/tableLoad.js"></script>