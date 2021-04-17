<?php
include('../private/config.php');
include('./shared/header.php');
$account_types = [1, 2, 3, 4];
pageRestrict($account_types, "../", true, 'node_modules/mdbootstrap/img/svg/restrict.svg');
$username = '';
$message = '';
if (empty($id) && !(int)$id) {
    echo "<h5 class='text-center'>INVALID Account ID</h5>";
    exit;
} else {
    $username = findOne("SELECT username FROM accountlist WHERE id=?", $id);
    if (!$username) {
        echo "<h5 class='text-center'>INVALID Account ID</h5>";
        exit;
    }
    $username = $username['username'];
}

if (isset($_POST['s']) && $_POST['s'] == 1 && count($_POST['credential']) == 4) {
    $errors = [];
    //sanitize
    $s = $_POST['credential'];
    if (!empty($s[2]) && !empty($s[3]) && !empty($s[1])) {
        if (strlen($s[2]) <= 8) {
            array_push($errors, "New Password should be atleast 9 Characters");
        }
        //check if new and confirm matched
        if ($s[2] != $s[3]) {
            array_push($errors, "New Password Doesn't Match");
        } else {
            //check if old password is correct
            $passwordCorrect = findOne("SELECT password FROM accountlist WHERE id=?", $id);
            if ($passwordCorrect && count($errors) == 0) {
                //change password
                $sql = "UPDATE accountlist SET password=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $s[2], $id);
                if (!execute($stmt)) {
                    array_push($errors, "Unknown Error Occured, Update Password Failed");
                } else {
                    $msg = "Update Password Successful";
                }
            } else {
                array_push($errors, "Old Password is Incorrect");
            }
        }
    } else { //update username
        if (empty($s[0])) {
            array_push($errors, "Username is Empty");
        }
        // username already taken
        $usernameExist = findOne("SELECT username,id FROM accountlist WHERE username=? LIMIT 1", $s[0]);
        if ($usernameExist && $usernameExist['id'] != $id) {
            array_push($errors, "Username Already Taken");
        } //update            
        if (count($errors) == 0) {
            $sql = "UPDATE accountlist SET username=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $s[0], $id);
            if (!execute($stmt)) {
                array_push($errors, "Unknown Error Occured, Update Username Failed");
            } else {
                $msg = "Update Username Successful";
            }
        }
    }

    if (count($errors) > 0) {
        $message = message(implode(", ", $errors));
    }
    if (!empty($msg)) {
        $message = message($msg, 1);
    }
}
?>

<body style="background-image:url(node_modules/mdbootstrap/img/svg/bg.svg); height:100vh">

    <div class="container">

        <div class="row">
            <div class="col-12  mt-5">
                <div class="card">
                    <div class="card-body">
                        <a href="./?return=true"> <i class=" fa-lg fas fa-long-arrow-alt-left"></i>Back </a>
                        <h5 class="text-center"> Change Credentials</h5>
                        <hr>
                        <div class="msg">
                            <?php
                            echo $message;
                            unset($message);
                            ?>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <!-- username -->
                            <div class="md-form mb-5">
                                <input type="text" id="username" class="form-control validate fields input" name="credential[0]" value="<?php echo $username ?>">
                                <label class="label" data-error="wrong" data-success="right" for="username">Username</label>
                            </div>
                            <!-- old password -->
                            <div class="md-form mb-5">
                                <input type="password" id="password" class="form-control validate fields input" name="credential[1]">
                                <label class="label" data-error="wrong" data-success="right" for="password">Old Password</label>
                            </div>
                            <!-- new password -->
                            <div class="md-form mb-5">
                                <input type="password" id="oldPassword" class="form-control validate fields input" name="credential[2]">
                                <label class="label" data-error="wrong" data-success="right" for="oldPassword">New Password</label>
                            </div>
                            <!-- confirm password -->
                            <div class="md-form mb-5">
                                <input type="password" id="confirmPassword" class="form-control validate fields input" name="credential[3]">
                                <label class="label" data-error="wrong" data-success="right" for="confirmPassword">Confirm Password</label>
                            </div>
                            <button type="submit" class="btn btn-info col-12">Save</button>
                            <input type="hidden" name="s" value="1">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<?php
unset($_POST);
include('./shared/footer.php');
?>