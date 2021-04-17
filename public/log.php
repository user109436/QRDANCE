<?php
include('../private/config.php');
include('./shared/header.php');
$account_types = [1, 2, 4];
pageRestrict($account_types, "../", true, 'node_modules/mdbootstrap/img/svg/restrict.svg');
if ((isset($_GET['id']) and !empty($_GET['id'])) || (isset($account_type) && $account_type == 1)) {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $column = ' qrcode_name=?';
    }
    if ($account_type == 1) {
        $id = $account_id;
        $column = 'student_id=?';
    }
    //sanitize
    $id = sanitizeInput($id);
    $sql = "SELECT * FROM qr_codes WHERE " . $column . " LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    if (!execute($stmt)) {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {


        $row = $result->fetch_assoc();

        $student_id = $row['student_id'];
        $sql = " SELECT 
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
INNER JOIN 
staffs ON students.creator_id = staffs.id    
    WHERE students.id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $student_id);
        $stmt->execute();
        $student = $stmt->get_result();
        $data = $student->fetch_assoc();
        if (!$data) {
            echo message('Invalid ID');
            exit;
        }
        $yearCourseSection = $data['year'] . " " . $data['course_acronym'] . "-" . $data['section'];
        $d = strtotime("today");
        $date = date("Y-m-d h:i:sa", $d);
        // if already log in for this day--->
        $sql = "SELECT present, date_created FROM guard_attendance WHERE date_created LIKE ? AND student_id =? ORDER by id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dateToday, $student_id);
        if (!execute($stmt)) {
            echo message("Error:" . $stmt->error . " sql=" . $sql);
            exit;
        }
        $present = true;
        $gateAttendance = $stmt->get_result();
        $pandemicMode = getDataFromTable(1, 'pandemic', 'settings');
        $sched = '';
        if ($pandemicMode) {
            //get the appointment for this day
            $q = "SELECT subject, schedule FROM schedules WHERE student_id=? AND approve=1 AND schedule LIKE '" . $dateToday . "' LIMIT 1";
            $stmt = $conn->prepare($q);
            $stmt->bind_param("s", $student_id);
            if (!execute($stmt)) {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            $scheduleResult = $stmt->get_result();
            if ($scheduleResult->num_rows > 0) {
                $sched = $scheduleResult->fetch_assoc();
            }
        }
        //do not allow student to log in?
        if ($hasRecord = $gateAttendance->num_rows > 0) {
            if ($attendance = $gateAttendance->fetch_assoc()) {
                $time = '<h3 class="text-success gothic">' . readableDate($attendance['date_created'], true) . '</h3>';
                $status = displayGateStatus('Already Logged In' . $time, 4);

                if ($account_type == 2 or $account_type == 4) {
                    if ($attendance['present'] == 1 && isset($_GET['guardLogout']) && $_GET['guardLogout'] == 1) {
                        //guardLogout
                        $log = loggedStudent($creator_id, $student_id, false);
                        $status = displayGateStatus('Successfully Logged Out', 2);
                        $hasRecord = 0;
                    } else if ($attendance['present'] == 0) {
                        $log = loggedStudent($creator_id, $student_id);
                        $status = displayGateStatus();
                        $hasRecord = 0;
                    }
                }
            }
        } else {
            // NO record for this day then insert
            $log = loggedStudent($creator_id, $student_id);
            $status = displayGateStatus();
        }
        //else already log in or ask if, are you going to log out?
?>
        <div class="container ">
            <div class="row text-center ">
                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <h3>QRDANCE</h3>
                    <h5>Online QR Code Attendance Tracking System</h5>
                    <p class="font-weight-bold">Guard's Attendance Checking System</p>
                    <img class="img-sm" src="./node_modules/mdbootstrap/img/ursLogo.png" alt="URS LOGO">
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <div class=" card  ">
                        <div class="view overlay">
                            <img class="card-img-top img-fluid" src="./node_modules/mdbootstrap/img/students/<?php echo $student_id . "." . displayFileExtension($student_id, 1); ?>" alt="Card image cap">
                            <a>
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>
                        <div class="card-body elegant-color white-text rounded-bottom">
                            <h5 class="card-title text-center cyan-text"><?php echo fullName($data['fname'], $data['mname'], $data['lname']) . " " . $yearCourseSection ?></h5>
                            <hr class="hr-light">
                            <h5 class="card-text mb-4 text-center white-text"><?php echo $readableDate ?></h5>
                            <?php echo $status;
                            if ($sched) {

                            ?>
                                <p>Appointment: <span class="text-primary"> <?php echo $sched['subject'] ?></span></p>
                                <p>Schedule:<span class="text-primary"><?php echo readableDate($sched['subject'], true) ?> </span></p>

                            <?php
                            }

                            if ($hasRecord && ($account_type == 2 || $account_type == 4)) {
                                echo '<a href="log.php?id=' . $id . '&guardLogout=1" class="btn btn-info btn-md col-12"> Log Out</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        echo '
        <body class="text-center text-uppercase font-weight-bold" style="height:100vh; background-image: url(node_modules/mdbootstrap/img/svg/empty.svg);
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;" >
                    <h3 class="font-weight-bold text-danger">Invalid ID</h3>
                    <a href="./" class="btn btn-info"> <i class="fa-3x fas fa-arrow-left"></i></a>
        </body>
        ';
        exit;
    }
}

?>