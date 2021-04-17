<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
if (isset($_GET['staff_id']) && (int)$_GET['staff_id']) {
    $account_id = $_GET['staff_id'];
    $staff = findAll('staffs', $account_id);
    if (!$staff) {
        echo invalidIDSVG();
        exit;
    }
    unset($staff);
} else {
    echo invalidIDSVG("Staff ID is not Set");
    exit;
}
$column = "creator_id";
$tables = [
    'staffs',
    'professors_subject_list',
    'subjects',
    'subject_attendance',
    'enrolled_subjects',
    'courses',
    'year',
    'sections',
    'students',
    'guard_attendance',
    'qr_codes',
    'accountlist',
    'accounts_photos',
    'notifications',
    'settings'
];
$tablePlaceholder = [
    'Staffs',
    'Professor Subject List',
    'Subjects',
    'Subject Attendance',
    'Enrolled Subjects',
    'Courses',
    'Year',
    'Sections',
    'Students',
    'Guard Attendance',
    'QR Codes',
    'Accountlist',
    'Accounts Photos',
    'Notifications',
    'Settings'
];

$count = countDatas($tables, $account_id, $column);
$totalRecordsInDB = sumRecords($count);
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>

    <!-- Start your project here-->
    <div class="container-fluid">
        <h5 class="mt-5 font-weight-bold"><?php echo getFullNameFromDB('staffs', $account_id) ?> Records</h5>
    </div>
    <div class="container message"></div>
    <!-- all data in other tables could be deleted or not? -->
    <div class="container-fluid">
        <div class="row bg-white p-4">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">

                <?php

                for ($i = 0; $i < count($count); $i++) {
                ?>
                    <h6 class="<?php echo $count[$i] >= 1 ? 'text-danger' : 'text-success' ?>">
                        <i class="fas fa-database"></i>
                        <?php
                        echo $count[$i] . " in " . $tablePlaceholder[$i];
                        ?>
                    </h6>
                <?php

                }
                ?>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <?php

                $accountResult = findOne("SELECT active, email, account_type FROM accountlist WHERE account_id=? AND account_type>1", $account_id);
                ?>
                <h6 class="<?php echo $totalRecordsInDB == 0 ? 'text-success' : 'text-danger' ?>">Deletable: <i class="fas fa-trash-alt"></i> </h6>
                <!-- if account is active, archivable-> yes  -->
                <h6 class="<?php
                            echo isset($accountResult['active']) && $accountResult['active'] == 1 ? "text-success" : "";
                            ?>"> Archivable: <i class="fas fa-archive"></i></h6>

                <h6 class="<?php
                            echo isset($accountResult['active']) && $accountResult['active'] == 1 ? "text-warning" : "";
                            ?>"> Active:
                    <?php
                    echo accountBadge($accountResult['account_type'], $accountResult['active'])
                    ?>

                </h6>
                <h6 class="<?php
                            echo isset($accountResult['email']) && !empty($accountResult['email']) ? "text-info" : "";
                            ?>"> Email:<i class="fas fa-envelope"></i> <?php echo $accountResult['email'] ?> </h6>
            </div>
        </div>
    </div>

    <!-- for prof and administrator only -->
    <!-- subjects_assigned, and show how many records for each subject -->
    <div class="container-fluid">
        <h6 class="mt-5 font-weight-bold">Subjects Assigned</h6>
        <hr>
        <div class="row">
            <?php
            $subject_ids = findAllOpenQuery("SELECT subject_id FROM professors_subject_list WHERE professor_id=? ORDER by date_created DESC", $account_id);
            if ($subject_ids) {
                foreach ($subject_ids as $subject_id) {
                    $subject = findAllOpenQuery('SELECT name_of_subject FROM subjects WHERE id=?', $subject_id['subject_id']);

                    if ($subject) {
                        //display data
                        foreach ($subject as $subName) {

            ?>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 text-center">
                                <!-- Card -->
                                <div class="card mt-3">
                                    <!-- Card content -->
                                    <div class="card-body">

                                        <!-- Title -->
                                        <p class="font-weight-bold">
                                            <?php
                                            echo $subName['name_of_subject']
                                            ?>
                                        </p>
                                        <hr>
                                        <p class="font-weight-bold text-info">
                                            <?php
                                            echo findAllOpenQuery("SELECT * FROM subject_attendance WHERE creator_id=$account_id AND subject_id=?", $subject_id['subject_id'], true);
                                            ?>
                                            Record(s) Found
                                        </p>
                                    </div>

                                </div>
                                <!-- Card -->
                            </div>
            <?php
                        }
                    }
                }
            } else {
                echo "Invalid Subject ID";
            }

            ?>
        </div>

    </div>

    <div class="container-fluid">
        <div class="row p-2">
            <!-- subject attendance -->
            <?php
            $account_type = getAccountTypeById($account_id)->fetch_assoc()['account_type'];

            if ($account_type == 3 || $account_type == 4) {

            ?>
                <div class="col-12 bg-white ">
                    <h6 class="mt-5 font-weight-bold">Subject Attendance</h6>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                            <thead class="blue white-text">
                                <tr>
                                    <th class="th">Subject</th>
                                    <th class="th-sm">Remarks</th>
                                    <th class="th-sm">Mark by</th>
                                    <th class="th-sm">Date</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                $sql = "SELECT subjects.name_of_subject, subject_attendance.remarks,subject_attendance.creator_id,subject_attendance.date_created 
                        FROM subject_attendance 
                        INNER JOIN subjects ON subjects.id = subject_attendance.subject_id WHERE subject_attendance.creator_id = ? ORDER by subject_attendance.date_created DESC";

                                $subjectAttendanceRecords = findAllOpenQuery($sql, $account_id);
                                if ($subjectAttendanceRecords) {
                                    foreach ($subjectAttendanceRecords as $subjectAttendance) {

                                ?>
                                        <tr>
                                            <td><?php echo $subjectAttendance['name_of_subject'] ?></td>
                                            <td><?php echo displayAttendanceLabel($subjectAttendance['remarks']) ?></td>
                                            <td><?php echo displayCreator($subjectAttendance['creator_id']) ?></td>
                                            <td><?php echo readableDate($subjectAttendance['date_created']) ?></td>

                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<h4>Professor has no record in Subject Attendance</h4>";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } else {


            ?>
                <!-- for guard only -->
                <div class="col-12 bg-white ">
                    <h6 class="mt-5 font-weight-bold">Gate Attendance</h6>
                    <hr>
                    <div class="table-responsive">
                        <table class=" DataFromDB table table-striped DataFromDB" cellspacing="0" width="100%">
                            <thead class="blue white-text">
                                <tr>
                                    <th class="th-sm">Remarks</th>
                                    <th class="th-sm">Guard on Duty</th>
                                    <th class="th-sm">Date</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                $sql = "SELECT  present,creator_id,date_created 
                        FROM guard_attendance  WHERE creator_id = ? ORDER by date_created DESC";
                                $gateAttendanceResults = findAllOpenQuery($sql, $account_id);

                                if ($gateAttendanceResults) {
                                    foreach ($gateAttendanceResults as $gateAttendance) {

                                ?>
                                        <tr>
                                            <td><?php echo InOrOutLabel($gateAttendance['present']) ?></td>
                                            <td><?php echo displayCreator($gateAttendance['creator_id']) ?></td>
                                            <td><?php echo readableDate($gateAttendance['date_created']) ?></td>

                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<h4>Guard has no record of Gate Attendance</h4>";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            }

            ?>
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