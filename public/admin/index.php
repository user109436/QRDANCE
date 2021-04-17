<?php
include('../../private/config.php');
include('./shared/header.php');
?>

<body>
    <?php
    include('./shared/sidebar.php');
    $accounts = findAll('accountlist');
    $users = count($accounts);
    /*
    0-active
    1-notActive
    2-student
    3-guard
    4-professor
    5-administrator

    */
    $count = [0, 0, 0, 0, 0, 0];
    foreach ($accounts as $account) {
        if ($account['active'] == 1) {
            $count[0] += 1;
        } else {
            $count[1] += 1;
        }
        if ($account['account_type'] == 1) {
            $count[2] += 1;
        } else  if ($account['account_type'] == 2) {
            $count[3] += 1;
        } else if ($account['account_type'] == 3) {
            $count[4] += 1;
        } else if ($account['account_type'] == 4) {
            $count[5] += 1;
        }
    }
    /*
    0-year
    1-courses
    2-sections
    3-subject_attendance
    guard_attendance
    */
    $tables = [0, 0, 0, 0, 0];
    $tableNames = ['year', 'courses', 'sections', 'subject_attendance', 'guard_attendance'];
    $tablePlaceholder = ['Year', 'Course', 'Section', 'Subject Attendance', 'Guard Attendance'];

    foreach ($tableNames as $i => $y) {
        $tables[$i] = countData($tableNames[$i]);
    }
    ?>

    <!-- Start your project here-->
    <div class="container-fluid">

        <h5 class="text-center font-weight-bold"><i class=" text-info fa-2x far fa-smile-beam"></i> <br> Welcome <?php
                                                                                                                    echo getFullNameFromDB('staffs', $account_id);
                                                                                                                    ?>
        </h5>
        <hr>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">Announcement:</h5>
                        <hr>
                        <div class="note note-info">

                            <?php
                            $message = openQuery("SELECT * FROM notifications ORDER by id DESC LIMIT 1");
                            if ($message) {


                            ?>
                                <p class="font-weight-light mb-0"> To: <?php echo recipient($message['account_id']) ?></p>
                                <p class="font-weight-light "> Subject: <?php echo $message['subject'] ?></p>
                                <p><?php echo $message['message'] ?></p>
                                <p class="font-weight-light mb-0">From:
                                    <?php
                                    displayAccountBadge($message['creator_id'], displayCreator($message['creator_id']));
                                    echo "<br> " . readableDate($message['date_created']);
                                    ?>
                                </p>
                            <?php

                            } else {
                                echo "<h5>No Message Found</h5>";
                            }

                            ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
    if (isset($account_type) && $account_type == 4) {

    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="row ">
                        <div class="col-12 text-center">
                            <div class="card mt-1">
                                <!-- Card content -->
                                <div class="card-body p-0">

                                    <!-- Title -->
                                    <p class="font-weight-bold mt-1"> Accounts</p>
                                    <hr>
                                    <p class="card-text m-0">
                                        <span class="text-success"> Active <?php echo $count[0]; ?></span> | <span class="text-warning">Archived <?php echo $count[1]; ?></span>
                                        | <span class="text-primary"> <?php echo accountBadge(4) . accountLabel(4) . ": " .  $count[5]; ?> </span>
                                        | <span class="text-success"> <?php echo accountBadge(3) . accountLabel(3) . ": " . $count[4]; ?></span>
                                        | <span class="text-info"><?php echo accountBadge(2) . accountLabel(2) . ": " . $count[3]; ?></span>
                                        | <span class="text-warning"><?php echo accountBadge(1) . accountLabel(1) . ": " . $count[2]; ?></span>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card mt-1">
                                <!-- Card content -->
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table large-header">

                                            <thead>

                                                <tr>
                                                    <th class="font-weight-bold"><strong>Table</strong></th>
                                                    <th class="font-weight-bold"><strong>Data</strong></th>
                                                </tr>

                                            </thead>

                                            <tbody>
                                                <?php
                                                foreach ($tablePlaceholder as $i => $table) {


                                                ?>
                                                    <tr>
                                                        <td><?php echo $table ?></td>
                                                        <td><?php echo $tables[$i] ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- stats -->

        <div class="container-fluid mt-5">
            <div class="row ">
                <div class="col-12 ">
                    <h5>SEO <?php echo upcomingBadge() ?></h5>
                    <hr>
                    <div class="row">

                        <!-- Grid column -->
                        <div class="col-md-12 col-lg-4  ">

                            <div class="card mb-4">

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <?php echo upcomingBadge() ?>
                                        <table class="table large-header">

                                            <thead>

                                                <tr>
                                                    <th class="font-weight-bold"><strong>Keywords</strong></th>
                                                    <th class="font-weight-bold"><strong>Visits</strong></th>
                                                </tr>

                                            </thead>

                                            <tbody>

                                                <tr>
                                                    <td>Attendance</td>
                                                    <td>15</td>
                                                </tr>
                                                <tr>
                                                    <td>Trancking</td>
                                                    <td>32</td>
                                                </tr>
                                                <tr>
                                                    <td>QR Code Attendance</td>
                                                    <td>41</td>
                                                </tr>
                                                <tr>
                                                    <td>QRDANCE</td>
                                                    <td>14</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <button class="btn btn-flat blue lighten-3 btn-rounded waves-effect float-right font-weight-bold btn-dash">View
                                        full report</button>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-8 col-md-12">

                            <div class="card mb-4">

                                <div class="card-body">
                                    <?php echo upcomingBadge() ?>
                                    <table class="table large-header">

                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold"><strong>Browser</strong></th>
                                                <th class="font-weight-bold"><strong>Visits</strong></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>Google Chrome</td>
                                                <td>307</td>
                                            </tr>
                                            <tr>
                                                <td>Mozilla Firefox</td>
                                                <td>504</td>
                                            </tr>
                                            <tr>
                                                <td>Safari</td>
                                                <td>613</td>
                                            </tr>
                                            <tr>
                                                <td>Opera</td>
                                                <td>208</td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    <button class="btn btn-flat blue lighten-3 btn-rounded waves-effect font-weight-bold float-right btn-dash">View
                                        full report</button>

                                </div>

                            </div>

                        </div>
                        <!-- Grid column -->

                    </div>
                </div>
                <div class="col-12">
                    <div class="row white-text text-center">
                        <!-- mode -->
                        <div class="col-6 col-sm-6 col-md-3 col-lg-3 mt-2 ">
                            <div class="card bg-info">
                                <?php echo upcomingBadge() ?>
                                <div class="card-body">
                                    <h5>Mode: <i class=" fas fa-globe-asia"></i></h5>
                                </div>
                            </div>
                        </div>
                        <!-- users -->
                        <div class="col-6 col-sm-6 col-md-3 col-lg-3 mt-2 ">
                            <div class="card bg-danger">
                                <div class="card-body">
                                    <h5>Users <i class="fas fa-users"></i> <?php echo $users ?></h5>
                                </div>
                            </div>
                        </div>
                        <!-- Sessions -->
                        <div class="col-6 col-sm-6 col-md-3 col-lg-3 mt-2 ">
                            <div class="card bg-secondary">
                                <?php echo upcomingBadge() ?>
                                <div class="card-body">
                                    <h5>Page Views <i class="fas fa-users"></i> 20,987</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3 col-lg-3 mt-2 ">
                            <div class="card bg-success">
                                <?php echo upcomingBadge() ?>
                                <div class="card-body">
                                    <h5>Sessions <i class="fas fa-users"></i> 5,457</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <h5>Staffs Activity</h5>
                                        <hr>
                                        <?php echo upcomingBadge() ?>
                                        <table class="table large-header">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold"><strong>Name</strong></th>
                                                    <th class="font-weight-bold"><strong>Account</strong></th>
                                                    <th class="font-weight-bold"><strong>Activity</strong></th>
                                                    <th class="font-weight-bold"><strong>Date</strong></th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr>
                                                    <td>Jaime S. Carpenter</td>
                                                    <td>Administrator</td>
                                                    <td>Add new Student <strong>William Parker</strong></td>
                                                    <td>March 24, 2021</td>


                                                </tr>
                                                <tr>
                                                    <td>Lana P. Alison</td>
                                                    <td>Professor</td>
                                                    <td>Marked Present <strong>Carla Go</strong> in Integral Calculus</td>
                                                    <td>March 20, 2021</td>


                                                </tr>
                                                <tr>
                                                    <td>Kevin Prowell</td>
                                                    <td>Guard</td>
                                                    <td>TIME IN <strong>Veronica Bong Hee</strong></td>
                                                    <td>March 20, 2021</td>
                                                </tr>
                                                <tr>
                                                    <td>Kennedy Wallstreet</td>
                                                    <td>Professor</td>
                                                    <td>Mark Present <strong>Janah Oxford</strong> in Physical Education</td>
                                                    <td>March 05, 2021</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <button class="btn btn-flat blue lighten-3 btn-rounded waves-effect float-right font-weight-bold btn-dash">View
                                        full report</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Pages</h5>
                                    <hr>
                                    <div class="table-responsive">
                                        <?php echo upcomingBadge() ?>
                                        <table class="table large-header">

                                            <thead>

                                                <tr>
                                                    <th class="font-weight-bold"><strong>Page</strong></th>
                                                    <th class="font-weight-bold"><strong>Public</strong></th>
                                                </tr>

                                            </thead>

                                            <tbody>

                                                <tr>
                                                    <td>public/home.php</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="1" checked />
                                                            <label class="form-check-label" for="1"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>admin/viewStaffs.php</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="2" />
                                                            <label class="form-check-label" for="2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>admin/viewStudents.php</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="3" checked />
                                                            <label class="form-check-label" for="3"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>admin/viewCourses.php</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="4" checked />
                                                            <label class="form-check-label" for="4"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-flat blue lighten-3 btn-rounded waves-effect float-right font-weight-bold btn-dash">View
                                        full report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <!-- End your project here-->

</body>

<?php
include('./shared/footer.php');
?>