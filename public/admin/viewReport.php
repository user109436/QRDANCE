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
            <h4>Class Subject Performance</h4>
            <form class="row" id="formAdd">
                <!-- subject -->
                <div class="col-12  md-form m-0 mb-2">
                    <select class=" p-2 col-12 fields" name="viewReport[0]">
                        <!-- Subject -->
                        <?php
                        $subjects = findAll('subjects');
                        if ($subjects) {
                            foreach ($subjects as $subject) {
                                echo ' <option value="' . $subject['id'] . '">' . $subject['name_of_subject'] . '</option>';
                            }
                        } else {
                            echo "<option>Please Add Subject in Database</option>";
                        }

                        ?>
                    </select>
                </div>
                <!-- Year -->
                <div class="col-6 col-sm-6 col-md-4 col-lg-4  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="viewReport[1]">
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
                <div class="col-6 col-sm-6 col-md-4 col-lg-4  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="viewReport[2]">
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
                <div class="col-6 col-sm-6 col-md-4 col-lg-4  md-form mb-1">
                    <select class=" p-2 col-12 fields" name="viewReport[3]">
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
                <div class="col-6 col-md-6  md-form mb-1">
                    <input type="date" class="p-2 col-12 fields" name="date[0]" id="startYear" value="">
                    <label for="startYear" class="mt-3">Start Year</label>
                </div>
                <!-- date -->
                <div class="col-6 col-md-6  md-form mb-1">
                    <input type="date" class="p-2 col-12 fields" name="date[1]" id="endYear" value="">
                    <label for="endYear" class="mt-3">End Year</label>

                </div>
                <input type="hidden" id="s" name="s" value="1">
                <input class="btn btn-danger btn-sm " type="reset">
                <button class="btn btn-info btn-sm col" type="submit" onclick="getData('viewReport')">View Class Performance</button>

                <div class="col-12">
                </div>
            </form>
            <div id="viewReportResult">

            </div>
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