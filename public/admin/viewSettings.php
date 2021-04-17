<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
$settings = findAll('settings');
$lastEdit = 1;
if ($settings) {
    $setting = $settings[0];
    $lastEdit = $setting['creator_id'];
}

?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>

    <!-- Start your project here-->
    <!-- Modal Add -->

    <div class="container">
        <h3 class="mt-5 text-primary"> <i class="fas fa-cog"></i> Settings</h3>
        <hr>
        <div class="col-12 p-5 text-center">
            <?php

            if (isset($setting['qrcode_name'])  && !empty($setting['qrcode_name'])) {
                echo '<img style="height:10rem;" src="' . $qrcodesPath . '' . $setting['qrcode_name'] . '.png" alt="Company QR Code" title="Company QR Code">';
                echo '<h5>Company QR Code</h5>';
            }
            ?>
        </div>

        <div class="container-fluid message"></div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formAdd" enctype="multipart/form-data">
            <div class="col-12">
                <!-- Attendance Today -->
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="attendanceToday" name="settings[0]" <?php echo isset($setting['attendance_today']) && $setting['attendance_today'] == 1 ? 'checked' : '' ?>>
                    <label class="custom-control-label font-sm text-primary" for="attendanceToday"> <i class=" fa-lg far fa-calendar-alt"></i> Attendance Today</label>
                </div>
                <hr>
                <!-- Pandemic Mode -->
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="pandemic" name="settings[1]" <?php echo isset($setting['pandemic']) && $setting['pandemic'] == 1 ? 'checked' : '' ?>>
                    <label class="custom-control-label font-sm text-primary" for="pandemic"> <i class=" fa-lg fas fa-diagnoses"></i> Pandemic Mode <?php echo upcomingBadge('Alpha') ?></label>
                </div>
                <hr>

                <!-- Maintenance Mode -->
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="maintenance" name="settings[2]" <?php echo isset($setting['maintenance']) && $setting['maintenance'] == 1 ? 'checked' : '' ?>>
                    <label class="custom-control-label font-sm text-primary" for="maintenance"><i class=" fa-lg fas fa-bug"></i> Maintenance Mode <?php echo upcomingBadge() ?></label>
                </div>
                <hr>

            </div>
            <!-- domain name -->
            <div class="md-form mb-5">
                <input type="text" id="Domain Name" class="form-control validate " name="settings[3]" value="<?php echo isset($setting['domain_name']) ? $setting['domain_name'] : '' ?>">
                <label class="label text-primary" for="Domain Name">Domain Name <i class="fas fa-server"></i></label>
            </div>
            <div class="md-form mb-5">
                <button id="update" class="btn btn-info btn-sm col-12 m-0" type="submit" onclick="updateData('settings')"><i class="fas fa-save"></i> Save</button>
                <input type="hidden" name="s" value="1" id="s">
            </div>

        </form>
        <div class="col-12">

            <h6>Settings last update by:
                <?php displayAccountBadge($lastEdit, displayCreator($lastEdit)) ?>
            </h6>
        </div>
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