<?php
include('../../../private/config.php');
if (isset($_POST['date']) and !empty($_POST['date'])) {
    $readableDate2 = $_POST['date'];
}
echo '<h5 class="text-center mt-3">Subject Attendance as of <span class="font-weight-bold">' . $readableDate2 . '</span></h5>';
?>
<div class="table-responsive">
    <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
        <thead class="blue white-text">
            <tr>
                <th class="th">Student</th>
                <th class="th-sm">Remarks
                </th>

            </tr>
        </thead>
        <tbody>

            <?php

            if (isset($_POST['s']) && $_POST['s'] == 1 && count($_POST['subjectAttendance']) == 4) {
                //validate id
                $errors = [];
                if ($error = invalidID($_POST['subjectAttendance'])) {
                    array_push($errors, $error . " Invalid ID");
                }

                if (count($errors) > 0) {
                    echo message(implode(", ", $errors));
                    exit;
                }
                $s = $_POST['subjectAttendance'];
                //subject[0] year[1] course[2] section[3]
                $studentsEnrolledSubject = findAllEnrolledStudents($s);
                if ($studentsEnrolledSubject) {

                    //loop through each student_id
                    $count = 0;
                    foreach ($studentsEnrolledSubject as $student) {

                        if (isset($_POST['date']) and !empty($_POST['date'])) {
                            $dateToday = $_POST['date'] . "%";
                        }
                        //check if student has already a record within this day
                        $data = wildCardFindAllSubjectAttendance($dateToday, $student['id'], $s[0]);
                        if ($data) {
                            $mark = $data[0];
                            $attendance = $mark['remarks'];
                        }
                        $imgPath =  $studentsPath . $student['id'] . "." . displayFileExtension($student['id'], 1);
            ?>
                        <tr>
                            <td class="font-weight-bold">
                                <img class="profileImg" src="<?php echo $imgPath ?>" alt="<?php echo $fullname = fullName($student['fname'], $student['mname'], $student['lname']) ?>">
                                <?php
                                echo "<br>";
                                if ($data) {
                                    echo subjectAttendanceBadge($attendance);
                                }
                                echo  $fullname;
                                ?>
                            </td>
                            <td>

                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="<?php echo $id = uniqid() ?>" name="mark[<?php echo $count ?>]" value="1" <?php
                                                                                                                                                                    echo !empty($data) && $attendance == 1 ? 'checked' : ''
                                                                                                                                                                    ?>>
                                    <label class="custom-control-label" for="<?php echo $id ?>">Present</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="<?php echo $id = uniqid()  ?>" name="mark[<?php echo $count ?>]" value="2" <?php
                                                                                                                                                                    echo !empty($data) && $attendance == 2 ? 'checked' : ''
                                                                                                                                                                    ?>>
                                    <label class="custom-control-label" for="<?php echo $id ?>">Absent</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="<?php echo $id = uniqid()  ?>" name="mark[<?php echo $count ?>]" value="3" <?php
                                                                                                                                                                    echo !empty($data) && $attendance == 3 ? 'checked' : ''
                                                                                                                                                                    ?>>
                                    <label class="custom-control-label" for="<?php echo $id ?>">Late</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="<?php echo $id = uniqid()  ?>" name="mark[<?php echo $count ?>]" value="4" <?php
                                                                                                                                                                    echo !empty($data) && $attendance == 4 ? 'checked' : ''
                                                                                                                                                                    ?>>
                                    <label class="custom-control-label" for="<?php echo $id ?>">Excuse</label>
                                </div>
                                <?php

                                ?>

                            </td>
                            <input type="hidden" value="<?php echo $student['id'] ?>" name="student[<?php echo $count ?>]">
                        </tr>

            <?php
                        $count++;
                    }
                } else {
                    echo message('No Students Found In Enrolled Subjects', 2);
                }



                //select all student id with this subject_id
            }
            //picture name remarks

            ?>

        </tbody>
    </table>
    <?php
    echo '
    <input type="hidden" value="' . $s[0] . '" name="subject_id">
    <input type="hidden" value="' . $s[1] . '" name="classDetails[0]">
    <input type="hidden" value="' . $s[2] . '" name="classDetails[1]">
    <input type="hidden" value="' . $s[3] . '" name="classDetails[2]">
    ';
    ?>
</div>