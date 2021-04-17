<?php
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    after_successful_logout(); //security checks
    header('location:./');
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Online QR Code Attendance Tracking Sytem Project in Software Design 2020-2021 URS">
    <meta name="keywords" content="QRDANCE, URS, University of Rizal System, Software Design, Attendance ">
    <meta name="author" content="SD Team">
    <title>QRDANCE</title>
    <!-- MDB icon -->
    <link rel="icon" href="./node_modules/mdbootstrap/img/logo/favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="./node_modules/aos/dist/aos.css" />
    <link rel="stylesheet" type="text/css" href="./node_modules/mdbootstrap/css/client.css" />
    <link rel="stylesheet" type="text/css" href="./node_modules/mdbootstrap/css/mdb.min.css" />
    <?php
    if (isset($account_type) && !empty($account_type)) {
    ?>
        <link rel="stylesheet" type="text/css" href="./node_modules/mdbootstrap/DataTables/datatables.css" />
    <?php
    }
    ?>
</head>