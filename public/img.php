<?php
include('../private/db_conn.php');
include('../private/functions.php');
include('../private/db_functions.php');

$imgDefault = '<img class="img-fluid mb-3 rounded-circle teamImg" src=" ./node_modules/mdbootstrap/img/svg/user.svg" alt="userImage">';
if (isset($_POST['username']) && !empty($_POST['username'])) {

    $s = sanitizeInput($_POST['username']);

    $user = findAllOpenQuery('SELECT * FROM accountlist WHERE username = ? LIMIT 1', $s);
    if ($user) {
        $user = $user[0];
        if ($user['account_type'] > 1) {
            //staffs path
            $path = "./node_modules/mdbootstrap/img/staffs/" . $user['account_id'] . "." . displayFileExtension($user['account_id']);
        } else {
            //users path
            $path = "./node_modules/mdbootstrap/img/students/" . $user['account_id'] . "." . displayFileExtension($user['account_id'], 1);
        }
        echo ' <img class="img-fluid mb-3 teamImg rounded-circle" src="' . $path . '" alt="userImage">';
    } else {
        echo $imgDefault;
    }
} else {
    echo $imgDefault;
}
