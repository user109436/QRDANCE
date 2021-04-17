<?php
include('../../private/config.php');
include('./shared/header.php');

?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>


    <!-- Start your project here-->
    <div class="container-fluid">
        <div class="row p-2">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 bg-white ">
                <h6 class="mt-5">Students Enrolled Subjects</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                        <thead class="blue white-text">
                            <tr>
                                <th class="th">Student</th>
                                <th class="th">Subject</th>

                                <th class="th-sm">Last Modified by
                                </th>
                                <th class="th-sm">Last Modified Date
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $enrolled = findAll('enrolled_subjects');
                            if ($enrolled) {
                                foreach ($enrolled as $enroll) {


                            ?>
                                    <tr>
                                        <td><?php echo getFullNameFromDB('students', $enroll['student_id']) ?></td>
                                        <td>
                                            <?php
                                            $subject = findOne("SELECT name_of_subject FROM subjects WHERE id=?", $enroll['subject_id']);
                                            if ($subject) {
                                                echo $subject['name_of_subject'];
                                            } else {
                                                echo "Invalid Subject ID";
                                            }

                                            ?></td>
                                        <td><?php echo displayCreator($enroll['creator_id']) ?></td>
                                        <td><?php echo readableDate($enroll['date_created']); ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h5>No Record Found in Enrolled Subjects</h5>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 bg-white ">
                <h6 class="mt-5">Professors Subject List</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                        <thead class="blue white-text">
                            <tr>
                                <th class="th">Professor</th>
                                <th class="th">Subject</th>

                                <th class="th-sm">Last Modified by
                                </th>
                                <th class="th-sm">Last Modified Date
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $profList = findAll('professors_subject_list');
                            if ($profList) {
                                foreach ($profList as $list) {


                            ?>
                                    <tr>
                                        <td><?php echo getFullNameFromDB('staffs', $list['professor_id']) ?></td>
                                        <td>
                                            <?php
                                            $subject = findOne("SELECT name_of_subject FROM subjects WHERE id=?", $list['subject_id']);
                                            if ($subject) {
                                                echo $subject['name_of_subject'];
                                            } else {
                                                echo "Invalid Subject ID";
                                            }

                                            ?></td>
                                        <td><?php echo displayCreator($list['creator_id']) ?></td>
                                        <td><?php echo readableDate($list['date_created']); ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<h5>No Record Found in Professors Subject List</h5>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

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