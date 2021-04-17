<?php
include('../../private/config.php');
?>
<p class="font-weight-bold text-center">ANNOUNCEMENT</p>
<hr>
<?php
//select all notification where announcement is for all or for students
$all = 0;
$student = 1;
$sql = "SELECT * FROM notifications WHERE account_type=? OR account_type=? ORDER by id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $all, $student);
if ($stmt->execute() === TRUE) {
    $notificationResult = $stmt->get_result();
    if ($notificationResult->num_rows > 0) {
        $message = $notificationResult->fetch_assoc();
    } else {
        echo '<div class="note note-info"><h5>No Message Found</h5></div>';
        exit;
    }
} else {
    echo message("Error:" . $stmt->error . " sql=" . $sql);
    exit;
}
?>
<div class="note note-info">
    <p class="font-weight-light mb-0"> To: <?php echo recipient($message['account_id']) ?></p>
    <p class="font-weight-light "> Subject: <?php echo $message['subject'] ?></p>
    <p><?php echo $message['message'] ?></p>
    <p class="font-weight-light mb-0">From: <?php
                                            displayAccountBadge($message['creator_id'], displayCreator($message['creator_id']));
                                            echo "<br> " . readableDate($message['date_created']);
                                            ?></p>
</div>