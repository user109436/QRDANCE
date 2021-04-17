<?php
include('../private/config.php');
include('./shared/header.php');

?>

<body style="height:100vh; background-image: url( node_modules/mdbootstrap/img/svg/forgotPwd.svg); background-repeat: no-repeat; background-size: cover; background-position:center">


    <div class="container" style="margin-top:12rem">
        <div class="message"></div>
        <div class="row">
            <input type="email" class="form-control p-4" placeholder="Enter your account's Gmail" name="email" id="email" autocomplete="off">
        </div>
    </div>
</body>
<?php
include('./shared/footer.php');

?>