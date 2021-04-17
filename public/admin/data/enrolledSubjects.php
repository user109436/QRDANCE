<?php
include('../../../private/config.php');
?>
<div class="col" style="border-right: 1px solid black">
    <h5>Subjects</h5>
    <input type="text" id="searchSubject" class="col-12" placeholder="Search For Enrolled Students">
    <hr>


    <?php
    $subjects = findAll('subjects ');
    if ($subjects) {
        foreach ($subjects as $row) {

    ?>
            <div class="subject-container">
                <button onclick="saveToSub(this, 'enrolledSubjects')" class=" btn btn-dark m-1 p-2 mask waves-effect waves-light rgba-white-slight" style="width:100%" value="<?php echo $row['id'] ?>"><?php echo $row['name_of_subject'] ?>
                </button>
                <?php

                $studentsID = getDatasFromTable($row['id'], 'student_id', 'enrolled_subjects', 'subject_id');
                if ($studentsID != -1) {

                ?>
                    <button class="accordion" onclick="accordion(this)">Enrolled Students</button>
                    <div class="panel p-0">
                        <ol class="m-0" style="font-size:.9rem">
                            <?php

                            foreach ($studentsID as $studentID) {
                                //if not active continue;
                                $active = findOne("SELECT active FROM accountlist WHERE account_id=? AND account_type=1", $studentID)['active'];
                                if (!$active) {
                                    continue;
                                }

                            ?>
                                <li><?php
                                    echo  $studentNames = getFullNameFromDB('students', $studentID);
                                    ?><button onclick="deleteData('enrolledSubjects',this.value)" class="text-danger my-btn" value="<?php echo $studentID . " " . $row['id'] ?>">
                                        x</button></li>
                            <?php
                            }

                            ?>
                        </ol>
                    </div>

                <?php } ?>
            </div>


    <?php
        }
    } else {
        echo message("No Subjects Found, Please Add First In the Subjects Table", 2);
    }
    ?>

</div>
<div class="col-sm-9 col-md-9 col-lg-9">
    <h5>Students</h5>
    <input type="text" id="searchBar" class="col-12" placeholder="Search For Students">
    <hr>
    <div class="overflow-auto container">
        <form action="#" class="row" id="subjectList">
            <?php
            $account_ids = findAllOpenQuery("SELECT account_id FROM accountlist WHERE account_type=? AND active=1", 1);

            if ($account_ids) {
                foreach ($account_ids as $students) {

                    $sql = "SELECT 
                        students.id,
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
                        WHERE students.id =?";
                    $studentResult = findAllOpenQuery($sql, $students['account_id']);

            ?>

                    <?php

                    if ($studentResult) {
                        foreach ($studentResult as $row) {
                            $imgPath =  $studentsPath . $row['id'] . "." . displayFileExtension($row['id'], 1);
                    ?>
                            <div class="col-md-3 col-lg-2 col-sm-4 col-4 m-0  p-1 card-container">
                                <!-- Card Regular -->
                                <div class="card card-cascade">
                                    <div class="view view-cascade overlay">
                                        <img class="card-img-top" data-target="#modalAdd" src="<?php echo $imgPath; ?>" alt="<?php echo $fullname = fullName($row['fname'], $row['mname'], $row['lname']) ?>" />
                                        <a>
                                            <div class="mask rgba-white-slight" onclick="selected(this, 'enrolledSubjects' )"><input type="checkbox" value="<?php echo $row['id'] ?>"></div>
                                        </a>
                                    </div>
                                    <!-- Card content -->
                                    <div class="card-body card-body-cascade text-center p-1">
                                        <!-- Title -->
                                        <h6 class="card-title"> <?php displayAccountBadge($row['id'], $fullname, true); ?></h6>
                                        </h6>
                                        <!-- Subtitle -->
                                        <p class="blue-text m-0"><?php echo $row['year'] . " " . $row['course_acronym'] . "-" . $row['section']; ?></p>
                                    </div>
                                </div>
                                <!-- Card Regular -->
                            </div>
                    <?php
                        }
                    }
                    ?>

            <?php
                }
            } else {
                echo "<h3>0 Results Found</h3>";
            }

            ?>
            <input id="sub" type="hidden" name="sub" value="">

        </form>
    </div>
</div>
<script type="text/javascript" src="../node_modules/mdbootstrap/js/search.js"></script>