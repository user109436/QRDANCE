    <?php
    if (isset($_SESSION['account_id'])) {
        $name = getFullNameFromDB('staffs', $account_id);
        $imgPath = $staffsPath . $account_id . "." . displayFileExtension($account_id);
    }
    ?>
    <div class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-blue" href="#">
            <i class="fas fa-bars"></i>
        </a>

        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-brand">
                    <a href="./">QRDANCE &copy;</a>
                    <div id="close-sidebar">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="sidebar-header">
                    <div class="user-pic">
                        <img class="img-responsive img-rounded" src="<?php echo $imgPath ?>" alt="<?php echo $name ?>" />
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            <?php
                            if (isset($_SESSION['account_id'])) {
                                echo $name;
                            }

                            ?>
                        </span>
                        <span class="user-role">
                            <?php
                            if (isset($_SESSION['account_type'])) {
                                echo accountLabel($account_type);
                            }
                            ?>

                        </span>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        <?php
                        $el = '
                        <!-- Dashboard -->
                        <li >
                            <a href="./">
                                <i class="fa fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                       ';
                        adminOnly($el);
                        ?>
                        <!-- Appointments -->
                        <?php
                        $el = ';
                        ?>
                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fas fa-list-ol"></i>
                                <span>To Review</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="./viewAppointments.php">Appointments </a>
                                    </li>
                                    <li>
                                        <a href="#">Student Promotion' . upcomingBadge() . ' </a>
                                    </li>
                                    <li>
                                        <a href="#">Course Syllabus' . upcomingBadge() . ' </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <?php
                        ';
                        adminOnly($el);
                        ?>
                        <!-- Accounts -->

                        <?php
                        if (isset($account_type)) {
                            if ($account_type == 4 || $account_type == 3) {

                        ?>
                                <li class="sidebar-dropdown">
                                    <a href="#">
                                        <i class="fas fa-users"></i>
                                        <span>Accounts</span>
                                    </a>
                                    <div class="sidebar-submenu">
                                        <ul>
                                            <li>
                                                <a href="./viewStaffs.php">Staffs </a>
                                            </li>
                                            <li>
                                                <a href="./viewStudents.php">Students</a>
                                            </li>
                                            <?php
                                            $el = '
                                     <li>
                                        <a href="./viewAccounts.php">Credentials</a>
                                    </li>';
                                            adminOnly($el);
                                            ?>

                                        </ul>
                                    </div>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        $el = '
                            <!-- Manage -->
                            <li class="sidebar-dropdown">
                                <a href="#">
                                    <i class="fas fa-tools"></i>
                                    <span>Manage</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <li>
                                            <a href="./viewYear.php">Year </a>
                                        </li>
                                        <li>
                                            <a href="./viewCourses.php">Courses</a>
                                        </li>
                                        <li>
                                            <a href="./viewSections.php">Sections</a>
                                        </li>
                                        <li>
                                            <a href="./viewSubjects.php">Subjects</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Subject List -->
                            <li class="sidebar-dropdown">
                                <a href="#">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>Subject List</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <li>
                                            <a href="./viewProfessorSubjectList.php">Professors Subjects</a>
                                        </li>
                                        <li>
                                            <a href="./viewEnrolledSubjects.php">Students Enrolled Subjects</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        ';
                        adminOnly($el);
                        ?>
                        <!-- Attendance -->
                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fas fa-calendar-check"></i>
                                <span>Attendance</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <?php
                                    if (isset($account_type)) {

                                        if ($account_type == 4 || $account_type == 2) {

                                            echo '<li> <a href="./viewGuardAttendance.php">Guard Attendance</a> </li>';
                                        }
                                        if ($account_type == 4 || $account_type == 3) {

                                            echo '<li> <a href="./viewSubjectAttendance.php">Subject Attendance</a> </li>';
                                            echo '<li> <a href="./viewReport.php">Class Subject Performance</a> </li>';
                                        }
                                    }

                                    ?>
                                </ul>
                            </div>
                        </li>
                        <!-- List-->
                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fas fa-file-alt"></i>
                                <span>List</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="./listSubjects.php">Enrolled List & Professor Subject List</a>
                                    </li>
                                    <li>
                                        <a href="./listAttendance.php">Subject & Gate Attendance</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- My Account Settings -->
                        <li>
                            <a href="../changeCredentials.php">
                                <i class="fas fa-cogs"></i>
                                <span>My Account Settings</span>
                            </a>
                        </li>
                        <!-- Extra -->
                        <li class="header-menu">
                            <span>Extra</span>
                        </li>
                        <li>
                            <a href="../node_modules/mdbootstrap/documentation.pdf" target="_blank">
                                <i class="fa fa-book"></i>
                                <span>Documentation</span>
                            </a>
                        </li>
                        <?php
                        $el = '
                        <li>
                            <a href="#">
                                <i class="fa fa-calendar"></i>
                                <span>Calendar</span>
                                ' . upcomingBadge() . '
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-file-import"></i>
                                <span>Backup & Restore</span>
                                ' . upcomingBadge() . '
                            </a>
                        </li>
                        ';
                        adminOnly($el);
                        ?>
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-content  -->
            <!-- Notification and Settings -->
            <div class="sidebar-footer">
                <a href="./viewNotifications.php">
                    <i class="fa fa-bell"></i>
                </a>
                <?php
                $el = '
                <a href="./viewSettings.php">
                    <i class="fa fa-cog"></i>
                </a>
                ';
                adminOnly($el);
                ?>
                <a href="./?logout=1">
                    <i class="fa fa-power-off"></i>
                </a>
            </div>
        </nav>
        <!-- sidebar-wrapper  -->
        <main class="page-content">