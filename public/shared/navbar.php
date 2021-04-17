<nav class="navbar navbar-expand-lg navbar-dark info-color fixed-top" id="navigation">

    <!-- Navbar brand -->
    <a class="navbar-brand" href="./">QRDANCE ©</a>

    <!-- Collapse button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#company" aria-controls="company" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="company">


        <?php

        if (isset($_SESSION['account_id'])) {
        ?>
            <ul class="navbar-nav ml-auto white-text">
                <li class="nav-item pr-2 waves-effect waves-light"> <a href="./schedule.php" class="white-text"> <i class="fas fa-clipboard-list"></i> Appointment </a>
                </li>
                <li class="nav-item pr-2 waves-effect waves-light" id="myClassmates"><i class="fas fa-users"></i> My Classmates
                </li>
                <li class="nav-item avatar dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" id="logout" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-graduate"></i> Student</a>
                    <div class="dropdown-menu dropdown-menu-lg-right dropdown-info" aria-labelledby="logout">
                        <a class="dropdown-item waves-effect waves-light" href="./?logout=1">Log Out</a>
                    </div>
                </li>
                <li class="nav-item  pl-2 waves-effect waves-light"><a href="./changeCredentials.php" class="white-text"><i class="fas fa-cogs"></i> Settings</a>
                </li>
            </ul>
        <?php
        } else {
        ?>
            <ul class="navbar-nav ml-auto nav-flex-icons smooth-scroll">
                <li><a href="#Login" class="nav-item white-text pr-3 waves-effect waves-light">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <li> <a href="#theTeam" class="nav-item white-text pr-3 waves-effect waves-light"><i class="fas fa-users"></i> The Team</a>
                </li>
                <li> <a href="#theProject" class="nav-item white-text pr-3 waves-effect waves-light"><i class="fas fa-project-diagram"></i> The Project</a>
                </li>
                <li> <a href="#built" class="nav-item white-text pr-3 waves-effect waves-light"><i class="fas fa-laptop-code"></i> Built</a>
                </li>
                <li> <a href="#about" class="nav-item white-text pr-3 waves-effect waves-light"><i class="far fa-copyright"></i> About</a>
                </li>
            </ul>


        <?php
        }

        ?>
        <!-- Links -->
    </div>
    <!-- Collapsible content -->

</nav>