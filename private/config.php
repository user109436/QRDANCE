<?php
//private files configuration
include("db_conn.php");
include("functions.php");
include("db_functions.php");

// admin path to images
$staffsPath = "../node_modules/mdbootstrap/img/staffs/";
$qrcodesPath = "../node_modules/mdbootstrap/img/qrcodes/";
$studentsPath = "../node_modules/mdbootstrap/img/students/";
$qrcodesPath2 = "../../node_modules/mdbootstrap/img/qrcodes/"; //for settings and accounts component

//home path for images
$qrcodesPathClient = "node_modules/mdbootstrap/img/qrcodes/";
$studentsPathClient = "node_modules/mdbootstrap/img/students/";

//for testing purposes only
session_start();
$ss = ['account_id', 'account_type', 'id']; //sessions from accountlist
if (sessionExist($ss)) {
    $creator_id = $account_id = $_SESSION[$ss[0]];
    $account_type = $_SESSION[$ss[1]];
    $id = $_SESSION[$ss[2]];
}
date_default_timezone_set('Asia/Manila');
$t = time();
$date = (date("Y-m-d", $t));
$readableDate = date("l jS \of F Y h:i:s A", $t);
$readableDate2 = date("l jS \of F Y ", $t);
$dateToday = $date . "%"; //for wildcard searches only

//additional settings
//deletion of data that is required in other table
$errorDeleteMsg = "Cannot Delete because Data is Required in Other Table(s)";

session_regenerate_id();
$check_ip = true;
$check_user_agent = true;
$check_last_login = true;
// https(true); //implement for deployment only
// header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60))); // 1 hour
// define("COOKIE_DOMAIN", "https://qrdance.000webhostapp.com/");
