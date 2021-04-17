<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
?>

<body>
    <?php
    include('./shared/sidebar.php');


    if (isset($_GET['sub_id']) && (int)$_GET['sub_id']) {
        echo "valid";

    ?>

        <div class="container">
        </div>

    <?php
    } else {
        echo "Invalid ID";
    }
    ?>
    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>