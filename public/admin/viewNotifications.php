<?php
include('../../private/config.php');
include('./shared/header.php');

?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>
    <a onclick="addData('notifications')" class="text-center addBtnBR" data-toggle="modal" data-target="#modalAdd"><i class="fa-3x text-success fas fa-plus"></i></a>

    <!-- Start your project here-->
    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Message</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message"></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formAdd" enctype="multipart/form-data">

                    <div class="modal-body mx-3">
                        <!-- subject -->
                        <div class="md-form mb-0">
                            <input type="text" id="subject" class="form-control validate fields input" name="message[0]">
                            <label class="label" data-error="wrong" data-success="right" for="subject">Subject</label>
                        </div>
                        <!-- Recipient -->
                        <label class="label">Recipient</label>
                        <div class="md-form m-0">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input radioBtn" type="radio" id="students" value="1" name="message[1]" />
                                <label class=" form-check-label" for="students">Students</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input radioBtn" type="radio" id="staffs" value="4" name="message[1]" />
                                <label class="form-check-label" for="staffs">Staffs</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input radioBtn" type="radio" id="all" value="0" name="message[1]" />
                                <label class="form-check-label" for="all"> All</label>
                            </div>
                        </div>
                        <div class="md-form mb-0">
                            <input type="text" class="form-control" disabled>
                            <label class="label">Specific Recipient <?php echo upcomingBadge() ?></label>
                        </div>
                        <!-- Message -->
                        <div class="md-form mb-0">
                            <textarea type="textarea" rows="4" cols="5" id="message" class="form-control validate fields" name="message[2]"></textarea>
                            <label class="label" data-error="wrong" data-success="right" for="message">Message</label>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button id="create" class="btn btn-success p-3" type="submit" onclick="createData('notifications')"><i class="fas fa-plus-circle"></i> Create</button>
                        <button id="update" class="btn btn-secondary p-3" type="submit" onclick="updateData('notifications')"><i class="fas fa-save"></i> Update</button>
                        <button class="btn btn-danger p-3" id="resetForm" type="reset"><i class="fas fa-eraser"></i> Reset</button>
                        <input type="hidden" name="s" value="-1" id="s">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Delete Message</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message">
                    <h4 class="text-danger font-weight-bold">Are you sure you want to delete this?</h4>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formDelete" enctype="multipart/form-data">
                    <div class="modal-footer d-flex justify-content-center">
                        <div class="row col-12">
                            <div id="dataDelete" class="col-12 text-center"></div>
                            <button class="btn btn-success col-5" type="submit" data-dismiss="modal" onclick="deleteData('notifications')"><i class="fas fa-sign-in-alt"></i> Process</button>
                            <button class="btn btn-secondary col-6" data-dismiss="modal"><i class="fas fa-ban"></i> Cancel</button>
                            <input type="hidden" name="x" value="" id="x">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <a onclick="addData('notifications')" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus-circle fa-lg"></i>
                    Compose Message</a>
            </div>
        </div>
    </div>
    <div class="container message"></div>
    <div class="container-fluid" id="notificationResult">

    </div>
    <!-- End your project here-->

    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>