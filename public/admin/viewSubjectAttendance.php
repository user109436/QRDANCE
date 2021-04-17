<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [3, 4];
pageRestrict($account_types, "../", true);
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>
    <div class="container-fluid">
        <div class="col-12 mt-5">
            <h4>Subject Attendance</h4>
            <form class="row" id="formAdd">
                <!-- subject -->
                <div class="col-12  md-form m-0 mb-2">
                    <select class=" p-2 col-12 fields" name="subjectAttendance[0]">
                        <!-- Subject -->
                        <?php $result = findAll('professors_subject_list', $account_id, 'professor_id');
                        if ($result) {
                            echo '<option selected>Subject</option>';
                            foreach ($result as $subject) {
                                $subject_id = $subject['subject_id'];
                                $subjectData = findAll('subjects', $subject_id);
                                // //display all subjects
                                $sub = $subjectData[0];
                                echo ' <option value="' . $sub['id'] . '">' . $sub['name_of_subject'] . '</option>';
                            }
                        } else {
                            echo "<option> You have no Subjects Assigned</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Year -->
                <div class="col-6 col-sm-6 col-md-3 col-lg-3  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="subjectAttendance[1]">
                        <?php
                        $years = findAll('year');
                        if ($years) {
                            echo '<option selected>Year</option>';
                            foreach ($years as $year) {
                                echo ' <option value="' . $year['id'] . '">' . $year['year'] . '</option>';
                            }
                        } else {
                            echo ' <option value="">Please Add Year in Database</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- Course -->
                <div class="col-6 col-sm-6 col-md-3 col-lg-3  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="subjectAttendance[2]">
                        <?php
                        $courses = findAll('courses');
                        if ($courses) {
                            echo '<option selected>Course</option>';
                            foreach ($courses as $course) {
                                echo ' <option value="' . $course['id'] . '">' . $course['course_acronym'] . '</option>';
                            }
                        } else {
                            echo ' <option value="">Please Add Course in Database</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- Section  -->
                <div class="col-6 col-sm-6 col-md-3 col-lg-3  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="subjectAttendance[3]">
                        <?php
                        $sections = findAll('sections');
                        if ($sections) {
                            echo '<option selected>Section</option>';
                            foreach ($sections as $section) {
                                echo ' <option value="' . $section['id'] . '">' . $section['section'] . '</option>';
                            }
                        } else {
                            echo ' <option value="">Please Add Section in Database</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- date -->
                <div class="col-6 col-sm-6 col-md-3 col-lg-3  md-form mb-1">
                    <input type="date" class=" p-2 col-12 fields" name="date" id="subjectAttendanceDate" value="">
                </div>
                <input type="hidden" name="s" value="1">
                <button class="btn btn-danger btn-sm p-3 col-12 col-sm-5 col-md-5 col-lg-5 " id="resetForm" type="reset"><i class="fas fa-eraser"></i> Reset</button>
                <button class="btn btn-info btn-sm col" type="submit" onclick="getData()">Find my Students</button>
            </form>
            <form action="#!" method="POST" id="formAttendance" class="mb-5">
                <div id="subjectAttendanceResult"></div>
                <input type="hidden" name="date" id="dateSet" value="">
                <button class="btn btn-md btn-success col-12" type="submit" value="1" name="x" id="saveAttendance">Save</button>
            </form>
            <div id="msg"></div>
        </div>

    </div>

    <!-- //display all subjects for this prof -->
    <!-- //use get for the subject id -->



    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>