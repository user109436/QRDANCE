<?php
include('../../private/config.php');
?>

<?php
$sql = "SELECT * FROM subject_attendance WHERE student_id=? ORDER by date_created DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $account_id);
if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($subjectAttendance = $result->fetch_assoc()) {

?>
            <tr>
                <td><?php
                    $subjectResult = openQuery("SELECT name_of_subject FROM subjects WHERE id=" . $subjectAttendance['subject_id']);

                    if ($subjectResult) {
                        echo $subjectResult['name_of_subject'];
                    } else {
                        echo "Invalid Subject ID";
                    }

                    ?>


                </td>
                <td><?php echo displayAttendanceLabel($subjectAttendance['remarks']) ?></td>
                <td><?php echo displayCreator($subjectAttendance['creator_id']) ?></td>
                <td><?php echo readableDate($subjectAttendance['date_created']); ?></td>
            <tr>


    <?php
        }
    } else {
        echo "<h5>You don't have any records in Subject Attendance</h5>";
    }
} else {
    echo message("Error:" . $stmt->error . " sql=" . $sql);
    exit;
}
    ?>