<?php
include('../../../private/config.php');
?>
<div class="col" style="border-right: 1px solid black">
    <h5>Subjects</h5>
    <input type="text" id="searchSubject" class="col-12" placeholder="Search For Professors Subject List">
    <hr>
    <?php
    $subjects = findAll('subjects');

    if ($subjects) {
        foreach ($subjects as $row) {

    ?>
            <div class="subject-container">
                <button onclick="saveToSub(this)" class=" btn btn-dark m-1 p-2 mask waves-effect waves-light rgba-white-slight" style="width:100%" value="<?php echo $row['id'] ?>"><?php echo $row['name_of_subject'] ?>
                </button>
                <?php

                $profsID = getDatasFromTable($row['id'], 'professor_id', 'professors_subject_list', 'subject_id');
                if ($profsID != -1) {

                ?>
                    <button class="accordion" onclick="accordion(this)">Assigned Professors</button>
                    <div class="panel p-0">
                        <ol class="m-0" style="font-size:.9rem">
                            <?php


                            foreach ($profsID as $profID) {
                                $accountlist = findOne("SELECT active, account_type FROM accountlist WHERE account_id=? AND account_type>=2", $profID);
                                if ($accountlist) {
                                    if (!$accountlist['active']) {
                                        continue;
                                    }
                                }
                            ?>
                                <li><?php
                                    echo accountBadge($accountlist['account_type'], $accountlist['active']) . $profNames = getFullNameFromDB('staffs', $profID);
                                    ?><button onclick="deleteData('professorSubjectList',this.value)" class="text-danger my-btn" value="<?php echo $profID . " " . $row['id'] ?>">
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
        echo "No Subjects Found in Subjects Table";
    }
    ?>
</div>
<div class="col-sm-9 col-md-9 col-lg-9">
    <h5>Professors</h5>
    <input type="text" id="searchBar" class="col-12" placeholder="Search For Professors">
    <hr>
    <div class="overflow-auto container">
        <form action="#" class="row" id="subjectList">
            <?php
            //select only the admin and prof
            $account_ids = findAllOpenQuery("SELECT account_id FROM accountlist WHERE account_type>=? AND active=1", 3);
            if ($account_ids) {
                foreach ($account_ids as $profAndAdmin) {
                    $staff = findOne("SELECT * FROM staffs  WHERE id=?", $profAndAdmin['account_id']);

            ?>
                    <!-- Cards -->

                    <?php

                    if ($staff) {
                        $imgPath =  $staffsPath . $staff['id'] . "." . displayFileExtension($staff['id']);
                    ?>
                        <div class="col-md-3 col-lg-2 col-sm-4 col-4 m-0  p-1 card-container">
                            <!-- Card Regular -->
                            <div class="card card-cascade">
                                <div class="view view-cascade overlay">
                                    <img class="card-img-top" data-target="#modalAdd" src="<?php echo $imgPath; ?>" alt="<?php echo $fullname = fullName($staff['fname'], $staff['mname'], $staff['lname']) ?>" />
                                    <a>
                                        <div class="mask rgba-white-slight" onclick=selected(this)><input type="checkbox" value="<?php echo $staff['id'] ?>"></div>
                                    </a>
                                </div>
                                <!-- Card content -->
                                <div class="card-body card-body-cascade text-center p-1">
                                    <!-- Title -->
                                    <h6 class="card-title"> <?php displayAccountBadge($staff['id'], $fullname); ?>
                                    </h6>
                                    <!-- Subtitle -->
                                    <p class="blue-text m-0"><?php echo $staff['tags'] ?></p>
                                    <!-- Text -->
                                    <p class="card-text">
                                        <?php echo $staff['about'] ?>
                                    </p>
                                </div>
                            </div>
                            <!-- Card Regular -->
                        </div>
                    <?php
                    } else {
                        echo "Invalid Staff ID";
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