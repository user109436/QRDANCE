<?php
include('../../../private/config.php');
$schedules = findAll('schedules');
?>
<div class="card mt-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                <thead class="blue white-text">
                    <tr>
                        <th class="th">Manipulate</th>
                        <th class="th">Student</th>
                        <th class="th">Subject</th>
                        <th class="th">Purpose</th>
                        <th class="th">Schedule</th>
                        <th class="th">Approved</th>
                        <th class="th">Message</th>
                        <th class="th">Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($schedules) {
                        foreach ($schedules as $schedule) {

                    ?>

                            <tr>
                                <td>
                                    <a class="btn btn-info btn-sm" href="
                                      <?php echo "viewAppointments.php?editID=" . urlencode($schedule['id']) ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td><?php echo getFullNameFromDB('students', $schedule['student_id']) ?></td>
                                <td><?php echo $schedule['subject']; ?></td>
                                <td><?php echo $schedule['purpose']; ?></td>
                                <td><?php echo $schedule['schedule']; ?></td>
                                <td>
                                    <?php
                                    if ($schedule['approve'] == 1) {
                                        echo '<i class="text-success fa-lg fas fa-check"></i> Yes';
                                    } else if ($schedule['approve'] == 0) {
                                        echo '<i class="text-danger fa-lg fas fa-ban"></i>No';
                                    } else {
                                        echo '<i class="text-warning fa-lg fas fa-file-signature"></i>To Review';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $schedule['staffs_notes']; ?></td>
                                <td><?php echo readableDate($schedule['date_created']); ?></td>
                            </tr>




                    <?php
                        }
                    } else {
                        echo "<h5>No Schedules Found</h5>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>