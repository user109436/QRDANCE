<?php
include('../../private/config.php');
?>

<?php
$sql = "SELECT * FROM guard_attendance WHERE student_id=? ORDER by date_created DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $account_id);
if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($gateAttendance = $result->fetch_assoc()) {

?>
            <tr>
                <td><?php echo InOrOutLabel($gateAttendance['present']) ?></td>
                <td><?php echo displayCreator($gateAttendance['creator_id']) ?></td>
                <td><?php echo readableDate($gateAttendance['date_created']); ?></td>
            <tr>


    <?php
        }
    } else {
        echo "<h5>You don't have any records in Gate Attendance</h5>";
    }
} else {
    echo message("Error:" . $stmt->error . " sql=" . $sql);
    exit;
}
    ?>