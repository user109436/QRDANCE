<?php
include('../../private/config.php');
?>
<h5 class="font-weight-bold">My Classmates</h5>
<hr>
<?php
//get section of this students
$section_id;
$year_id;
$course_id;
$students = findAll('students');
if ($students) {
    //get year, section and course id
    foreach ($students as $student) {
        if ($student['id'] == $account_id) {
            $section_id = $student['section_id'];
            $course_id = $student['course_id'];
            $year_id = $student['year_id'];
            break;
        }
    }
    echo "<ul class='list-unstyled'>";
    foreach ($students as $student) {
        if ($student['section_id'] == $section_id && $student['course_id'] == $course_id && $student['year_id'] == $year_id) {
            //display student
            //get their ids and check if they at school

            $sql = "SELECT * FROM guard_attendance WHERE student_id =? AND date_created LIKE ? ORDER by id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $student['id'], $dateToday);
            if ($stmt->execute() === TRUE) {
                $gateAttendanceResult = $stmt->get_result();
                if ($gateAttendanceResult->num_rows > 0) {
                    while ($row = $gateAttendanceResult->fetch_assoc()) {
                        if ($row['present']) {
                            //at school
                            echo ' <li ><i class="fas fa-university text-primary"></i> ' . fullName($student['fname'], $student['mname'], $student['lname']) . '</li>';
                        }
                    }
                } else {
                    echo ' <li><i class="fas fa-university"></i>  ' . fullName($student['fname'], $student['mname'], $student['lname']) . '</li>';
                }
            } else {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
        }
    }
    echo "</ul>";
} else {
    echo "No Enrolled Students";
}
?>