<?php
include('../private/config.php');
include('./shared/header.php');
if (isset($_GET['logout']) and $_GET['logout'] == 1) {
    after_successful_logout(); //security checks
    session_destroy();
}
if (isset($_SESSION['account_type'])) {
    if ($_SESSION['account_type'] >= 2 && $_SESSION['account_type'] <= 4) {
        //admin
        header('location:./admin');
        exit;
    }
} else {
    header('location:./');
}
confirm_user_logged_in();
$img = $studentsPathClient . $account_id . "." . displayFileExtension($account_id, 1);
$sql = "SELECT 
students.id,
students.fname,
students.mname,
students.lname,
year,
section,
course,
accountlist.active
FROM `students` 
INNER JOIN 
year ON students.year_id=year.id
INNER JOIN
sections ON students.section_id=sections.id
INNER JOIN 
courses ON students.course_id = courses.id
INNER JOIN 
staffs ON students.creator_id = staffs.id
INNER JOIN 
accountlist ON accountlist.account_id= students.id
WHERE accountlist.account_type=1 AND students.id=?";
$student = findOne($sql, $account_id);
$name  = fullName($student['fname'], $student['mname'], $student['lname']);
?>

<body>
    <?php
    include('./shared/navbar.php');

    ?>
    <!-- main container -->
    <div class="container-fluid" style="margin-top:5rem;">

        <div class=" classMates bg-dark white-text" id="myClassmatesCol">
        </div>
        <div class="col-12 loading text-center" style="margin-top:10rem">
            <i class="fas fa-spinner fa-pulse fa-3x fa-fw text-primary"></i>
            <h5>Loading Resources</h5>
        </div>
        <!-- my classmates -->

        <div class="row d-none content">
            <div class="col p-3 mb-2 text-center ">
                <div class="card profileCover">
                    <div class="card-body">
                        <img class="teamImg" src="<?php echo $img ?>" alt="<?php echo $name ?>">
                        <h5 class="mt-2 font-weight-bold"><?php echo accountBadge(1, $student['active']) . "<br>" . $name ?></h5>
                        <p><?php echo $student['year'] . "-" . $student['course'] . "-" . $student['section'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col">
                <!-- announcements -->
                <div class="container-fluid bg-white p-3">
                    <div class="row">
                        <div class="col" id="announcement">
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                            <p class="font-weight-bold text-center">QR CODES</p>
                            <hr>
                            <div class="row text-center">

                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <h6>Student</h6>
                                    <?php
                                    if ($qrCodeName = hasQrcodeName($account_id)) {
                                        $studentQRCode = $qrcodesPathClient . $qrCodeName . ".png";
                                        echo '<a href="' . $studentQRCode . '"><img style="height:9rem;" src="' . $studentQRCode . '" alt="Your QrCode"> </a>';
                                    } else {
                                        echo "You don't yet have a qr code";
                                    }
                                    $companyQRCode = $qrcodesPathClient . "company.png";
                                    ?>

                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <h6>Company</h6>
                                    <a href="<?php echo $companyQRCode ?>"> <img src="<?php echo $companyQRCode ?>" alt="Company QR Code" style="height:9rem;"> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="subjectsTaken">
                </div>

                <!-- subject & gate attendance -->
                <div class="container-fluid bg-white p-5">
                    <div class="row">
                        <!-- subject -->
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                                    <thead class="blue white-text">
                                        <tr>
                                            <th class="th">Subject</th>
                                            <th class="th">Remarks</th>
                                            <th class="th">Mark By</th>
                                            <th class="th-sm">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subjectAttendance">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- gate -->
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                                    <thead class="blue white-text">
                                        <tr>
                                            <th class="th">Remarks</th>
                                            <th class="th">Guard On Duty</th>
                                            <th class="th-sm">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="guardAttendance">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
include('./shared/footer.php');

?>