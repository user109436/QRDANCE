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

    <!-- Start your project here-->
    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Account</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message"></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formAdd" enctype="multipart/form-data">

                    <div class="modal-body mx-3">
                        <!-- Account username -->
                        <div class="md-form mb-5">
                            <i class="far fa-user prefix active"></i>
                            <input type="text" id="accountUsername" class="form-control validate fields input" name="account[0]">
                            <label class="active" data-error="wrong" data-success="right" for="accountUsername">Username</label>
                        </div>
                        <!-- Password -->
                        <div class="md-form mb-5">
                            <i id="visiblePwd" class="far fa-eye-slash prefix active"></i>
                            <input type="password" id="pwd" class="form-control validate fields input" name="account[1]">
                            <label class="active" data-error="wrong" data-success="right" for="pwd">Password</label>
                        </div>
                        <!-- email -->
                        <div class="md-form mb-5">
                            <i class="far fa-envelope prefix active"></i>
                            <input type="email" id="email" class="form-control validate fields" name="account[2]">
                            <label class="active" data-error="wrong" data-success="right" for="email">Email</label>
                        </div>
                        <!-- active state -->
                        <div class="col-12">
                            <h5>Active</h5>
                            <div class="md-form mb-5 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input radioBtn" type="radio" id="yes" value="1" name="account[3]" />
                                    <label class=" form-check-label" for="yes">YES</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input radioBtn" type="radio" id="no" value="0" name="account[3]" />
                                    <label class="form-check-label" for="no">NO</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button id="update" class="btn btn-secondary p-3" type="submit" onclick="updateData('accounts')"><i class="fas fa-save"></i> Update</button>
                        <button class="btn btn-danger p-3" id="resetForm" type="reset"><i class="fas fa-eraser"></i> Reset</button>
                        <input type="hidden" name="s" value="-1" id="s">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container message"></div>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                <thead class="blue white-text">
                    <tr>
                        <th class="th">Manipulate</th>
                        <th class="th">Name</th>
                        <th class="th">Account Type</th>
                        <th class="th">Active</th>
                        <th class="th">Username</th>
                        <th class="th">Password</th>
                        <th class="th">Email</th>
                        <th class="th-sm">Last Modified by
                        </th>
                        <th class="th-sm">Last Modified Date
                        </th>

                    </tr>
                </thead>
                <tbody id="accountResult">

                </tbody>
            </table>
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