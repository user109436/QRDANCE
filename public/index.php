<?php
include('../private/config.php');
include('./shared/header.php');
$featuresPath = 'node_modules/mdbootstrap/img/features/';

if (isset($_SESSION['account_type'])) {
    if ($_SESSION['account_type'] == 1) {
        //students homepage
        header('location:./home.php');
        exit;
    } else if ($_SESSION['account_type'] >= 2 && $_SESSION['account_type'] <= 4) {
        //admin
        header('location:./admin/?admin=true');
        exit;
    }
}

if (isset($_POST['s']) && (int)$_POST['s'] == 1 && csrf_token_is_valid() && csrf_token_is_recent()) {
    $errors = [];
    //validation error
    if (empty($_POST['username'])) {
        //error message
        array_push($errors, 'Username is Empty');
    }
    if (empty($_POST['password'])) {
        //error message
        array_push($errors, 'Password is Empty');
    }

    if (count($errors) > 0) {
        //display error
        $alert = message(implode(", ", $errors));
    } else {
        //sanitization
        $fields[0] = $_POST['username'];
        $fields[1] = $_POST['password'];
        $s = sanitizeInputs($fields);
        //select from db -> 
        $sql = "SELECT * FROM accountlist WHERE username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $s[0]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            // if user exist
            //login

            while ($row = $result->fetch_assoc()) {
                if (password_verify($s[1], $row['encrypted_password'])) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['account_id'] = $row['account_id'];
                    $_SESSION['account_type'] = $row['account_type'];
                    break;
                }
                array_push($errors, "Invalid Credentials");
                $alert = message(implode(", ", $errors));
            }

            if (isset($_SESSION['account_type'])) {
                if ($_SESSION['account_type'] == 1) {
                    //students homepage
                    after_successful_login();
                    header('location:./home.php');
                    exit;
                } else if ($_SESSION['account_type'] >= 2 && $_SESSION['account_type'] <= 4) {
                    //admin
                    after_successful_login();
                    header('location:./admin');
                    exit;
                } else {
                    array_push($errors, "Invalid Account Type");
                    $alert = message(implode(", ", $errors));
                }
            }
        } else {
            //else error
            array_push($errors, "Invalid Credentials");
            $alert = message(implode(", ", $errors));
        }
    }
}

?>

<body id="Login">
    <?php
    include('./shared/navbar.php');
    ?>
    <div class="container mt-lg head">
        <div class="row">
            <!-- Heading -->
            <div class="col-md-7 text-center animated fadeInUp">

                <h1>QRDANCE</h1>
                <h4>Online QR Code Attendance Tracking System</h4>
                <img class="img-sm" src="./node_modules/mdbootstrap/img/ursLogo.png" alt="URS LOGO">

            </div>
            <!-- Form -->
            <div class="col ">
                <form class="text-center brdr-light p-5 bg-white" action="#!" method="POST">

                    <div class="container">
                        <div id="avatar">
                            <img class="img-fluid mb-3 rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/svg/user.svg" alt="userImage">
                        </div>
                        <div class="message">
                            <?php

                            if (isset($alert) && !empty($alert)) {
                                echo $alert;
                            }

                            ?>
                        </div>
                    </div>


                    <!-- username -->
                    <input type="text" class="form-control mb-4" placeholder="Username" name="username" id="user" autocomplete="off">

                    <!-- password -->
                    <input type="password" class="form-control mb-4" placeholder="Password" name="password">

                    <!-- Log in button -->
                    <button class="btn btn-info btn-block" type="submit" name="s" value=1>Log in</button>
                    <?php echo csrf_token_tag(); ?>
                    <a href="forgotPassword.php">Forgot Your Password?</a>


                </form>
                <!-- Default form subscription -->
            </div>

        </div>
    </div>
    </div>
    <!-- The Team -->
    <div class="container-fluid team" id="theTeam">
        <h4 class="mt-5 pt-4 text-center font-weight-bold white-text" data-aos="fade-up">The Team</h4>
        <hr class="white-text">
        <div class="row text-center">
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5" data-aos="zoom-out-up" data-aos-delay="50">
                <div class="brdr bg-white p-3">
                    <img class="img-fluid rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/team/1.png" alt="Edison G. Reterta">
                    <hr>
                    <h5> <a href="https://www.linkedin.com/in/edison-reterta-36384520a/" target="_blank"><i class="fab fa-linkedin"></i> Edison G. Reterta</a></h5>
                    <hr>
                    <p>“Only those who dare to fail greatly can ever achieve greatly.”<br> – Robert F. Kennedy –</p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5" data-aos="zoom-out-up" data-aos-delay="200">
                <div class="brdr bg-white p-3">
                    <img class="img-fluid rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/team/2.png" alt="Juris P. De Guzman">
                    <hr>
                    <h5><a href="https://www.linkedin.com/in/juris-de-guzman-17b5b316a/" target="_blank"><i class="fab fa-linkedin"></i> Juris P. De Guzman</a></h5>
                    <hr>
                    <p>“Time waits for no one.”<br> – Geoffrey Chaucer – </p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5" data-aos="zoom-out-up" data-aos-delay="400">
                <div class="brdr bg-white p-3">
                    <img class="img-fluid rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/team/3.png" alt="Hazel Keith D. Anselmo">
                    <hr>
                    <h5><a href="https://www.linkedin.com/in/kate-anselmo-4baaa915a/" target="_blank"><i class="fab fa-linkedin"></i> Hazel Keith D. Anselmo</a></h5>
                    <hr>
                    <p>“Don’t worry about failures, worry about the chances you miss when you don’t even try.”<br> – Jack Canfield – </p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5" data-aos="zoom-out-up" data-aos-delay="600">
                <div class="brdr bg-white p-3">
                    <img class="img-fluid rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/team/4.png" alt="Melquizedek SJ. Felix">
                    <hr>
                    <h5> <a href="https://www.linkedin.com/in/zedek-felix-a7bb27197" target="_blank"><i class="fab fa-linkedin"></i> Melquizedek SJ. Felix</a></h5>
                    <hr>
                    <p>“I can do all things through Christ who strengtheneth me.”<br> – KJ21 Philippians 4:13 – </p>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5" data-aos="zoom-out-up">
                <div class="brdr bg-white p-3">
                    <img class="img-fluid rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/team/5.png" alt="Nina L. Berongoy">
                    <hr>
                    <h5> <a href="https://www.linkedin.com/in/niña-berongoy-71513720b" target="_blank"><i class="fab fa-linkedin"></i> Nina L. Berongoy</a></h5>
                    <hr>
                    <p>“Having a perfect life is Boring but having a happy life is Blessings”</p>
                </div>
            </div>

        </div>
    </div>
    <!-- The project -->
    <div class="container-fluid text-center " id="theProject">
        <h4 class="mt-5 pt-4 text-center font-weight-bold">The Project</h4>
        <hr>
        <div class="row">
            <!-- col-6 col-sm-3 col-md-3 col-lg-3 mb-3 -->
            <!-- QRCODE -->
            <div class="col-12">
                <div class="row d-flex align-items-center">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6" data-aos="fade-up">
                        <div class="white-text mt-4">
                            <i class="fa-3x fas fa-qrcode"></i>
                            <h5>QR Code</h5>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi dolores alias quod rem provident doloremque repellendus quidem nesciunt eos quasi beatae, corporis minima in porro, assumenda quos ea. Doloremque, sapiente.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 brdr-light bg-white " data-aos="zoom-in">
                        <img class="img-fluid" src="<?php echo $featuresPath . "qrcode.png" ?>" alt="">
                    </div>
                </div>
            </div>
            <!-- Responsive -->
            <div class="col-12 ">
                <div class="row d-flex align-items-center">

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 phoneOrder" data-aos="zoom-in">
                        <img class="img-fluid phone" src="<?php echo $featuresPath . "responsive.png" ?>" alt="">
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6" data-aos="fade-up">
                        <div class="white-text mt-4">
                            <i class="fa-3x fas fa-mobile-alt"></i>
                            <h5>Responsive</h5>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi dolores alias quod rem provident doloremque repellendus quidem nesciunt eos quasi beatae, corporis minima in porro, assumenda quos ea. Doloremque, sapiente.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Real Time Feature -->
            <div class="col-12 ">
                <div class="row d-flex align-items-center">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6" data-aos="fade-up">
                        <div class="white-text mt-4">
                            <i class="fa-3x far fa-clock"></i>
                            <h5>Real Time Feature</h5>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi dolores alias quod rem provident doloremque repellendus quidem nesciunt eos quasi beatae, corporis minima in porro, assumenda quos ea. Doloremque, sapiente.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 brdr-light bg-white " data-aos="zoom-in">
                        <img class="img-fluid" src="<?php echo $featuresPath . "realtime.png" ?>" alt="">
                    </div>
                </div>
            </div>
            <!-- Accuracy -->
            <div class="col-12">
                <div class="row d-flex align-items-center">

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 phoneOrder" data-aos="zoom-in">
                        <img class="img-fluid phone" src="<?php echo $featuresPath . "accuracy.png" ?>" alt="">
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6" data-aos="fade-up">
                        <div class="white-text mt-4">
                            <i class="fa-3x fas fa-crosshairs"></i>
                            <h5>Accuracy</h5>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi dolores alias quod rem provident doloremque repellendus quidem nesciunt eos quasi beatae, corporis minima in porro, assumenda quos ea. Doloremque, sapiente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Built With -->
    <div class="container-fluid text-center bg-white" id="built">
        <h4 class="mt-5 pt-4 text-center font-weight-bold">Built with</h4>
        <hr>
        <div class="row">
            <div class="col">
                <i class="  fa-4x fab fa-html5 p-5 text-danger" data-aos="fade-up" data-aos-delay="100"></i>
                <i class=" fa-4x fab fa-bootstrap p-5 text-secondary" data-aos="fade-up" data-aos-delay="200"></i>
                <i class=" fa-4x fab fa-js p-5 text-warning" data-aos="fade-up" data-aos-delay="200"></i>
                <i class=" fa-4x fas fa-database p-5 text-info" data-aos="fade-up" data-aos-delay="300"></i>
                <i class=" fa-4x fab fa-php p-5 text-primary" data-aos="fade-up" data-aos-delay="400"></i>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="container-fluid text-center white-text bg-dark" id="about" style="height:20rem;">
        <h6 class="pt-5"> QRDANCE &copy; 2021
            <?php
            echo date('Y') !== "2021" ? "- " . date('Y') : "";

            ?> Copyright All Rights Reserved
            <br>
            &copy; Bootstrap, &copy; MDBootstrap, &copy; Undraw Copyright All Rights Reserved
        </h6>
    </div>
</body>

<?php
include('./shared/footer.php');

?>