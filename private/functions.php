<?php
include('db_conn.php');
//Global Functions
function printArr($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function fullName($fname = "", $mname = "", $lname = "")
{
    return $fname . " " . $mname . " " . $lname;
}

function sanitizeInputs($fields) //many
{
    global $conn;
    foreach ($fields as $field) {
        $field = stripslashes($field);
        $field = strip_tags($conn->real_escape_string($field));
        $field = trim($field);
        $fieldInfo[] = filter_var($field, FILTER_SANITIZE_STRING);
    }
    return $fieldInfo;
}
function sanitizeInput($field)
{
    global $conn;
    $field = trim($field);
    $field = stripslashes($field);
    $field = strip_tags(htmlspecialchars($conn->real_escape_string($field)));
    return filter_var($field, FILTER_SANITIZE_STRING);
}

function emptyFields($fields)
{
    global $conn;
    $emptyFields = 0;
    foreach ($fields as $field) {
        if ($field == '') {
            $emptyFields++;
        }
    }
    return $emptyFields;
}


//check if account is in the range of 1-4
/*
by default staffs = true
    staffs = true -> use only if you're creating staffs
    staffs= false -> use only for creating students
1-student
2-guard
3-professor
4-admin
*/
function accountTypeExist($accountType = '', $staffs = true)
{
    if (!$staffs) {
        if ($accountType == 1) {
            return 1; //student
        }
    } else {
        if ($accountType == 2 || $accountType == 3 || $accountType == 4) {
            return true;
        }
    }
    return false;
}

function accountBadge($accountType = '', $active = true)
{
    if ($accountType == 1) {
        $active = $active == 1 ? "text-warning" : "";
        return '<i class="p-1 fas fa-user-graduate fa-lg ' . $active . '"></i>';
    } else if ($accountType == 2) {
        $active = $active == 1 ? "text-primary" : "";
        return '<i class="p-1 fas fa-shield-alt fa-lg ' . $active . '"></i>';
    } else if ($accountType == 3) {
        $active = $active == 1 ? "text-success" : "";
        return '<i class="p-1 fas fa-chalkboard-teacher fa-lg ' . $active . '"></i>';
    } else if ($accountType == 4) {
        $active = $active == 1 ? "text-secondary" : "";
        return '<i class="p-1 fas fa-users-cog fa-lg ' . $active . '"></i>';
    } else {
        $active = $active == 1 ? "text-dark" : "";
        return '<i class="p-1 far fa-question-circle fa-lg ' . $active . '"></i>';
    }
}

//Alert Types for Debugging

//success
/*
1-success   (green)
2-warning   (yellow)
3-danger    (red)
*/

function alertTypes($type = 3)
{
    if ($type == 1) {
        $type = 'success';
    } else if ($type == 2) {
        $type = 'warning';
    } else if ($type == 3) {
        $type = 'danger';
    } else if ($type == 4) {
        $type = 'info';
    } else {
        return "Invalid class type of alert: " . $type;
    }
    return $type;
}
function message($msg = '', $type = 3)
{
    return "<div class='alert alert-" . alertTypes($type) . "' role='alert'>" . $msg; //FIXME:browser will auto complete the closing but this might bring problem in the future
}

// function toAssoc($string) //save the string to associative array named 'message'
// {
//     return $array['message'] = $string;
// }

// function toAssocMsg($msg, $type = 3)
// {
//     return toAssoc(message($msg, $type));
// }

/*IMAGE VALIDATION*/
//check $_FILE errors

function file_errors($file)
{
    $error = $_FILES[$file]['error'];
    if ($error == 1) {
        return 'Maximum File Size Exceeded in php.ini';
    } else if ($error == 2) {
        return 'Maximum File Size Exceeded in HTML form';
    } else if ($error == 3) {
        return 'The Uploaded File was partially uploaded';
    } else if ($error == 4) {
        return 'Plese Select an Image';
    } else if ($error == 6) {
        return 'Temporary Folder Missing';
    } else if ($error == 7) {
        return 'Failed to write file to disk';
    } else if ($error == 8) {
        return 'A PHP extension stopped the file upload';
    } else if ($error > 8 && $error < 0) {
        return 'Unknown Error Code in $_FILE occured';
    } else if ($error == 0) {
        return 0;
    }
}
function file_size($file)
{
    return $_FILES[$file]['size'];
}

function notImage($fileName)
{
    if (empty($_FILES[$fileName]['tmp_name']) || !getimagesize($_FILES[$fileName]['tmp_name'])) {
        return "Please Upload Image Only";
    }
}
function imageExceedMaxLimit($fileName, $limit = 2048000) //3mb by default
{
    if ($_FILES[$fileName]["size"] > $limit) {
        return 'File Exceed the limit of ' . $limit . ' bytes ';
    }
    /*
    assignment to Hazel, Edison, Juris
    Determine if limit is KB, MB, GB and so fort and so on
    1kb = 1024byte
    */
}
function getFileExtension($fileName = '')
{
    return strtolower(pathinfo($_FILES[$fileName]['name'], PATHINFO_EXTENSION)); //get's the file extension
}

function notValidFileExtension($fileName = '', $fileExtension = '', $validFileExtensions = ['jpg', 'jpeg', 'png'])
{
    //By deafult: it Accepts jpg, jpeg, png
    //OPTIONAL: allow debugger to immediately check if file is valid by using 2 parameter (fileName, validFileExtensions) 
    $msg = "Please select a :" . implode(", ", $validFileExtensions);
    if (!empty($fileName)) {
        $fileExtension = getFileExtension($fileName);
        if (!in_array($fileExtension, $validFileExtensions)) {
            return $fileExtension . "" . $msg;
        }
    }
    if (!in_array($fileExtension, $validFileExtensions)) {
        return $fileExtension . "" . $msg;
    }
}

//ID validation
function invalidID($ids, $many = true)
{
    $error = 0;
    if ($many) {
        foreach ($ids as $id) {
            $validID = (int)$id;
            if (!$validID) {
                $error++;
            }
        }
    } else {
        $validID = (int)$ids;
        if (!$validID) {
            $error++;
        }
    }

    return  $error;
}
function validatedID($ids, $many = true)
{
    if ($many) {
        foreach ($ids as $id) {
            $validIDs[] = (int)$id;
        }
    } else {
        $validIDs = (int)$ids;
    }

    return  $validIDs;
}

//SESSIONS
function sessionExist($sessionNames)
{
    if (!isset($_SESSION)) {
        return false;
    }
    $sessionsNotExist = [];
    foreach ($sessionNames as $sessionName) {
        if (!isset($_SESSION[$sessionName])) {
            $sessionsNotExist[] = $sessionName;
        }
    }
    if (count($sessionsNotExist) > 0) {
        return false;
    }
    return true;
}

//subject attendance color  coding

/*
1. present  success green
2. absent   danger  red
3. late     warning yellow
4 excuse    info    blue
*/
function subjectAttendanceBadge($attendance)
{
    if ($attendance == 1) {
        $attendance = 'success';
    } else if ($attendance == 2) {
        $attendance = 'danger';
    } else if ($attendance == 3) {
        $attendance = 'warning';
    } else if ($attendance == 4) {
        $attendance = 'info';
    } else {
        return "Invalid class type of badge color for subject Attendance: " . $attendance;
    }

    return '<i class="far fa-calendar-check text-' . $attendance . '" style="font-size:1rem;"></i>';
}
function displayAttendanceLabel($attendance)
{
    if ($attendance == 1) {
        $mark = ' Present';
    } else if ($attendance == 2) {
        $mark = ' Absent';
    } else if ($attendance == 3) {
        $mark = ' Late';
    } else if ($attendance == 4) {
        $mark = ' Excuse';
    } else {
        return "Invalid class type of badge color for subject Attendance: " . $attendance;
    }

    return '<p class="text-' .  alertTypes($attendance) . '">' . subjectAttendanceBadge($attendance) . $mark . ' </p>';
}




//upcoming
function upcomingBadge($msg = "Upcoming")
{
    return ' <span class="badge bg-warning">' . $msg . '</span>';
}

// readable date
function readableDate($t, $time = false)
{
    $t = strtotime($t);
    if ($time) {
        return  date("h:i:s A", $t);
    }
    return  date("l jS \of F Y h:i:s A", $t);
}

//recipient
function recipient($num)
{
    if ($num == 1) {
        return 'Students';
    } else if ($num == 4) {
        return 'Staffs';
    } else if ($num == 0) {
        return 'All';
    } else {
        return 'Recipient Undefined';
    }
}

// ATTENDANCE
function countTotalAttendance($attendance, $idToCompare, $col = "subject_id")
{
    $totalAttendance = [0, 0, 0, 0];
    foreach ($attendance as $mark) {
        if ($mark[$col] == $idToCompare) {
            $x = $mark['remarks'];
            // //0-P 1-A 2-L 3-E
            if ($x == 1) {
                $totalAttendance[0] += 1;
            } else if ($x == 2) {
                $totalAttendance[1] += 1;
            } else if ($x == 3) {
                $totalAttendance[2] += 1;
            } else if ($x == 4) {
                $totalAttendance[3] += 1;
            }
        }
    }
    return ($totalAttendance);
}
function totalAttendancePercentage($totalAttendance)
{
    $overAllAttendance = $totalAttendance[0] + $totalAttendance[1] + $totalAttendance[2] + $totalAttendance[3];
    $i = 0;
    foreach ($totalAttendance as $attendance) {
        if ($totalAttendance[$i] == 0) {
            $totalAttendance[$i] = 0;
        } else {
            $totalAttendance[$i] = ($totalAttendance[$i] / $overAllAttendance) * 100;
        }
        $i++;
    }
    return $totalAttendance;
}

function displayAttendancePerformance($present)
{
    if ($present >= 50 && $present <= 74) {
        echo " You're falling Behind";
    } else if ($present >= 75 && $present <= 79) {
        echo "Fairly Satisfactory";
    } else if ($present >= 80 && $present <= 84) {
        echo "Satisfactory";
    } else if ($present >= 85 && $present <= 89) {
        echo "Very Satisfactory";
    } else if ($present >= 90 && $present <= 100) {
        echo "Excellent";
    } else if ($present > 100) {
        echo "Greater than 100%";
    } else if ($present >= 0 && $present <= 49) {
        echo "Not Good :(";
    } else {
        echo "Undefined";
    }
}

//GUARD ATTENDANCE
function displayGateStatus($msg = "Successfully Logged In", $color = 1)
{
    return '<p class="m-0 text-center text-' . alertTypes($color) . '"><i class="fa-2x far fa-calendar-check"></i><br>' . $msg . '</p>';
}
function InOrOut($present)
{
    if ($present) {
        return ' <i class="text-success fa-lg fas fa-university "></i>';
    } else {
        return ' <i class="fas fa-university fa-lg"></i>';
    }
}

function InOrOutLabel($present)
{
    if ($present) {
        return InorOut($present) . 'TIME IN';
    } else {
        return InorOut($present) . ' TIME OUT';
    }
}
//Accounts
function accountLabel($account_type)
{
    if ($account_type == 1) {
        return 'Student';
    } else if ($account_type == 2) {
        return 'Guard';
    } else if ($account_type == 3) {
        return 'Professor';
    } else if ($account_type == 4) {
        return 'Administrator';
    } else {
        return 'Undefined Account Type:' . $account_type;
    }
}
function accountActive($active)
{
    if ($active) {
        return "true";
    }
    return "false";
}
//Page Restrictions
function pageRestrict($account_types = [], $page = "./", $message = false, $imgUrl = '../node_modules/mdbootstrap/img/svg/restrict.svg')
{
    global $account_type;
    if (!in_array($account_type, $account_types)) {
        if ($message) {
            echo '<body class="text-center text-uppercase font-weight-bold" style="height:100vh; background-image: url(' . $imgUrl . ');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;" >
                    <h3 class="font-weight-bold text-danger">You don\'t have Access to this Page</h3>
                    <a href="./" class="btn btn-info"> <i class="fa-3x fas fa-arrow-left"></i></a>
                </body>';
            exit;
        } else {
            header('location:' . $page);
        }
    }
}
function invalidIDSVG($msg = "Invalid ID", $imgUrl = "../node_modules/mdbootstrap/img/svg/empty.svg")
{
    return '
     <body class="text-center text-uppercase font-weight-bold" style="height:100vh; background-image: url(' . $imgUrl . ');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;" >
                    <h3 class="font-weight-bold text-danger">' . $msg . '</h3>
                    <a href="./" class="btn btn-info"> <i class="fa-3x fas fa-arrow-left"></i></a>
        </body>
    ';
}
function adminOnly($el)
{
    global $account_type;
    if (isset($account_type) && $account_type == 4) {
        echo $el;
    }
}
function isAdmin()
{
    global $account_type;
    if (isset($account_type) && $account_type != 4) {
        echo message("ERROR: YOU DON'T HAVE ADMIN RIGHTS");
        exit;
    }
}

//security
// CSRF
function csrf_token()
{
    return md5(uniqid(rand(), TRUE));
}
function create_csrf_token()
{
    $token = csrf_token();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
    return $token;
}
function destory_csrf_token()
{
    $_SESSION['csrf_token'] = null;
    $_SESSION['csrf_token_time'] = null;
    return true;
}

function csrf_token_tag()
{
    $token = create_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
function csrf_token_is_valid()
{
    if (isset($_POST['csrf_token'])) {
        $user_token = $_POST['csrf_token'];
        $stored_token = $_SESSION['csrf_token'];
        return $user_token === $stored_token;
    } else {
        return false;
    }
}

function die_on_csrf_token_failure()
{
    if (!csrf_token_is_valid()) {
        echo "<h5>CSRF Token validation Failed</h5>";
        die;
    }
}
function csrf_token_is_recent()
{
    $max_elapsed = 60 * 60; //1hour
    if (isset($_SESSION['csrf_token_time'])) {
        $stored_time = $_SESSION['csrf_token_time'];
        return ($stored_time + $max_elapsed) >= time();
    } else {
        destory_csrf_token();
        return false;
    }
}

// Session Hijacking and Session Fixation

function end_session()
{
    session_unset();
    session_destroy();
}
function request_ip_matches_session()
{
    if (!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
        return false;
    }
    if ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
        return true;
    }
    return false;
}

function request_user_agent_matches_session()
{
    if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }
    if ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
        return true;
    }
    return false;
}

function last_login_is_recent()
{
    $max_elapsed = 60 * 60 * 24; //1 day
    if (!isset($_SESSION['last_login'])) {
        return false;
    }
    if (($_SESSION['last_login'] + $max_elapsed) >= time()) {
        return true;
    }
    return false;
}
function is_session_valid()
{
    global $check_ip, $check_user_agent, $check_last_login;


    if ($check_ip && !request_ip_matches_session()) {
        return false;
    }
    if ($check_user_agent && !request_user_agent_matches_session()) {
        return false;
    }
    if ($check_last_login && !last_login_is_recent()) {
        return false;
    }
    return true;
}

function is_logged_in()
{
    return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
}

function confirm_user_logged_in($page = "./")
{
    if (!is_logged_in() && !is_session_valid()) {
        end_session();
        header('location:' . $page);
        exit;
    }
}

function after_successful_login()
{
    session_regenerate_id();
    $_SESSION['logged_in'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['last_login'] = time();
}
function after_successful_logout()
{
    $_SESSION['logged_in'] = false;
    end_session();
}

//USE HTTPS Connection
function https($https = false)
{
    if (!$https) {
        return false;
    }
    if (!isset($_SERVER['HTTPS']) or empty($_SERVER['HTTPS']) or ($_SERVER['SERVER_PORT'] !== 443)) { //for deployment

        echo '<body style="text-align:center;height:100vh; background-image: url(./node_modules/mdbootstrap/img/svg/warning.svg);
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;" >
                    <h1  style="font-size:5rem;">Connection is NOT secure</h1>
                </body>';
        exit;
    }
}
