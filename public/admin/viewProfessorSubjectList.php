<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>
    <div class="container message"></div>
    <div class="container-fluid">
        <h4 class="text-center mt-3">Professors Subject List</h4>
        <hr>
        <div class="row" id="pResult">

        </div>
    </div>
    <!-- End your project here-->

    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>