<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [2, 4];
pageRestrict($account_types, "../", true);
$row = findOne("SELECT attendance_today FROM settings WHERE id=? ", 1)
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>
    <div class="col-12">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="today" value="0" <?php echo isset($row['attendance_today']) && $row['attendance_today'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="today">Attendance Today</label>
        </div>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="realTime" value="0">
            <label class="custom-control-label" for="realTime">Real Time</label>
        </div>
    </div>
    <div class="container-fluid" id="guardAttendanceResult">

    </div>
    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>