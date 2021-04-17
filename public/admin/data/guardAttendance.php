<?php
include('../../../private/config.php');

if (isset($_POST['today'])) {
    if ((int)$_POST['today'] == 1 || (int)$_POST['today'] == 0) {
        $today = $_POST['today'];
        $id = 1;
        $sql = "UPDATE settings SET attendance_today=? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $today, $id);
        $stmt->execute();
    }
}
//settings from DB
$id = 1;
$attendanceToday;
$sql = "SELECT attendance_today FROM settings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($settingDB = $result->fetch_assoc()) {
        $attendanceToday = $settingDB['attendance_today'];
    }
}


if ($attendanceToday) {
    $sql = "SELECT * FROM guard_attendance WHERE date_created LIKE ? ORDER by id DESC ";
} else {
    $sql = "SELECT * FROM guard_attendance ORDER by id DESC ";
}
$stmt = $conn->prepare($sql);
if ($attendanceToday) {
    $stmt->bind_param("s", $dateToday);
}
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
?>


    <?php

    if ($attendanceToday) {
        echo '<h5 class="text-center mt-3">Guard Attendance as of <span class="font-weight-bold">' . $readableDate . '</span></h5>';
    } else {
        echo '<h5 class="text-center mt-3">Guard Attendance All Records</h5>';
    }

    ?>
    <h6 class=" text-center text-info font-weight-bold">Total of <?php echo $result->num_rows . " Entries" ?></h6>

    <hr>

    <div class="table-responsive">
        <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
            <thead class="blue white-text">
                <tr>
                    <th class="th">Profile</th>
                    <th class="th">Name</th>
                    <th class="th">Year Course Section</th>
                    <th class="th-sm">Guard on Duty
                    </th>
                    <th class="th-sm">Date Log
                    </th>

                </tr>
            </thead>
            <tbody>
                <?php


                while ($row = $result->fetch_assoc()) {
                    $id = $row['student_id'];

                    $sql = "SELECT 
students.id,
students.fname,
students.mname,
students.lname,
year,
section,
course_acronym
FROM `students` 
INNER JOIN 
year ON students.year_id=year.id
INNER JOIN
sections ON students.section_id=sections.id
INNER JOIN 
courses ON students.course_id = courses.id
WHERE students.id =?
";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $data = $stmt->get_result();
                    if ($data->num_rows > 0) {
                        while ($student = $data->fetch_assoc()) {

                            $imgPath =  $studentsPath . $student['id'] . "." . displayFileExtension($student['id'], 1);
                            echo "<tr>";

                ?>
                            <td><img class="profileImg" src="<?php echo $imgPath ?>" alt="<?php echo $fullname = fullName($student['fname'], $student['mname'], $student['lname']) ?>">


                            <td><?php echo $fullname;
                                echo InOrOut($row['present']);
                                ?></td>
                            <td><?php echo $student['year'] . " " . $student['course_acronym'] . "-" . $student['section']; ?></td>
                            <td><?php
                                displayCreator($row['creator_id']);
                                ?>
                            </td>
                            <td><?php $time = strtotime($row['date_created']);

                                echo date("l jS \of F Y h:i:s A", $time);
                                ?></td>
                <?php
                            echo "</tr>";
                        }
                    }
                }


                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<h1>0 Results<h1>";
}
?>